<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\RequestRateLimiter;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ContactController extends Controller
{
    public function __construct(private RequestRateLimiter $limiter)
    {
    }

    public function store(Request $request)
    {
        $validator = app('validator')->make($request->all(), [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:191'],
            'subject' => ['required', 'string', 'max:191'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        if ($validator->fails()) {
            $this->rememberOldInput($request);
            $this->rememberErrors($validator->errors()->all());
            $this->flash('error', 'Mohon periksa kembali data formulir.');

            return redirect('/kontak');
        }

        $limit = max(1, (int) env('CONTACT_RATE_LIMIT', 5));
        if ($this->limiter->tooMany('contact', $request, $limit)) {
            throw new HttpException(429, 'Terlalu banyak pesan dari sumber ini. Silakan coba lagi beberapa menit lagi.');
        }

        $data = $validator->validated();
        ContactMessage::query()->create([
            'name' => $this->plain($data['name']),
            'email' => strtolower(trim($data['email'])),
            'subject' => $this->plain($data['subject']),
            'message' => trim(strip_tags($data['message'])),
        ]);
        $this->limiter->hit('contact', $request);
        $this->flash('success', 'Pesan Anda telah diterima. Kami akan menindaklanjutinya secepatnya.');

        return redirect('/kontak');
    }

    private function plain(string $value): string
    {
        return trim((string) preg_replace('/\s+/', ' ', strip_tags($value)));
    }
}
