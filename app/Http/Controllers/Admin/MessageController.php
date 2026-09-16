<?php
// SPDX-License-Identifier: NCSA

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MessageController extends Controller
{
    public function __construct(private SettingsService $settings)
    {
    }

    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();
        $status = (string) $request->query('status', '');
        if (in_array($status, ['new', 'read', 'archived'], true)) {
            $query->where('status', $status);
        }

        return view('admin.messages.index', [
            'site' => $this->settings->all(),
            'baseUrl' => rtrim((string) config('app.url'), '/'),
            'user' => $this->adminUser($request),
            'messages' => $query->paginate(10)->appends($request->query()),
            'filters' => ['status' => $status],
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->adminUser($request);
        $validator = app('validator')->make($request->all(), [
            'status' => ['required', 'in:new,read,archived'],
        ]);
        if ($validator->fails()) {
            $this->flash('error', 'Status pesan tidak valid.');

            return redirect('/admin/messages');
        }

        $message = ContactMessage::query()->findOrFail($id);
        $status = $validator->validated()['status'];
        $message->fill([
            'status' => $status,
            'read_at' => $status === 'new' ? null : ($message->read_at ?: Carbon::now()),
        ])->save();
        $this->flash('success', 'Status pesan diperbarui.');

        return redirect('/admin/messages');
    }
}
