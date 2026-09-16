<?php
// SPDX-License-Identifier: NCSA

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Throwable;

class RequestRateLimiter
{
    public function tooMany(string $scope, Request $request, int $limit, bool $failClosed = false): bool
    {
        try {
            foreach ($this->keys($scope, $request) as $key) {
                if ($this->count(app('cache')->get($key, ['count' => 0])) >= $limit) {
                    return true;
                }
            }

            return false;
        } catch (Throwable) {
            return $failClosed;
        }
    }

    public function hit(string $scope, Request $request, int $seconds = 900): void
    {
        try {
            foreach ($this->keys($scope, $request) as $key) {
                $record = app('cache')->get($key, ['count' => 0]);
                app('cache')->put($key, ['count' => $this->count($record) + 1], Carbon::now()->addSeconds($seconds));
            }
        } catch (Throwable) {
            // Rate limiting is a defense in depth feature; do not take down public forms.
        }
    }

    public function clear(string $scope, Request $request): void
    {
        try {
            foreach ($this->keys($scope, $request) as $key) {
                app('cache')->forget($key);
            }
        } catch (Throwable) {
            // No action needed when a cache backend is unavailable.
        }
    }

    private function keys(string $scope, Request $request): array
    {
        $keys = [$this->key($scope, 'ip', (string) $request->ip())];
        $email = strtolower(trim((string) $request->input('email')));
        if ($email !== '') {
            $keys[] = $this->key($scope, 'email', $email);
        }

        return array_values(array_unique($keys));
    }

    private function key(string $scope, string $dimension, string $value): string
    {
        $material = $scope . '|' . $dimension . '|' . $value;
        $key = (string) config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);
            $key = $decoded !== false ? $decoded : $key;
        }

        return 'throttle:' . hash_hmac('sha256', $material, $key ?: 'local-fallback-key');
    }

    private function count(mixed $record): int
    {
        return is_array($record) ? (int) ($record['count'] ?? 0) : (int) $record;
    }
}
