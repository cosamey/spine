<?php

namespace Mey\Spine\Http\Middleware;

use Illuminate\Http\Request;
use Mey\Spine\Concerns\TracksLastPresence;
use Symfony\Component\HttpFoundation\Response;

class TrackLastPresence
{
    /**
     * This constant exists to make the TracksLastPresence trait a first-class, statically
     * referenced part of the package API.
     */
    public const EXPECTED_USER_TRAIT = TracksLastPresence::class;

    /** @param  \Closure(Request): (Response)  $next */
    public function handle(Request $request, \Closure $next): Response
    {
        $user = $request->user();

        if ($user && in_array(self::EXPECTED_USER_TRAIT, class_uses($user, true), true)) {
            /** @var mixed $user */
            \Illuminate\Support\defer(function () use ($user): void {
                // The trait guarantees this method exists.
                $user->markAsActive();
            });
        }

        return $next($request);
    }
}
