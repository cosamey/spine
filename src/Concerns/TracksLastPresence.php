<?php

namespace Mey\Spine\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;

/**
 * @mixin Authenticatable
 *
 * @property ?Carbon $last_active_at
 * @property ?string $last_active_ip
 *
 * @phpstan-ignore trait.unused
 */
trait TracksLastPresence
{
    protected int $throttle = 60;

    protected function initializeTracksLastPresence(): void
    {
        $this->casts['last_active_at'] = 'datetime';
    }

    public function markAsActive(?Carbon $timestamp = null): void
    {
        $timestamp ??= now();
        $ip = request()->ip();

        if ($this->shouldSkipUpdate($timestamp, $ip)) {
            return;
        }

        Model::withoutTimestamps(function () use ($timestamp, $ip): void {
            $this->forceFill([
                'last_active_at' => $timestamp,
                'last_active_ip' => $ip,
            ])->saveQuietly();
        });
    }

    private function shouldSkipUpdate(Carbon $timestamp, ?string $ip): bool
    {
        $lastAt = $this->last_active_at instanceof Carbon ? $this->last_active_at : null;
        $lastIp = $this->last_active_ip;

        return
            $lastAt
            && $ip === $lastIp
            && $lastAt->diffInSeconds($timestamp) < $this->throttle;
    }
}
