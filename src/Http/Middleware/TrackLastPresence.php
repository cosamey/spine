<?php

namespace Mey\Spine\Http\Middleware;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Mey\Spine\Concerns\TracksLastPresence;
use Symfony\Component\HttpFoundation\Response;

use function Illuminate\Support\defer;

class TrackLastPresence
{
    /** @param  \Closure(Request): (Response)  $next */
    public function handle(Request $request, \Closure $next): Response
    {
        $user = $request->user();

        if (
            $user instanceof Authenticatable
            && method_exists($user, 'markAsActive')
            && in_array(TracksLastPresence::class, class_uses_recursive($user), true)
        ) {
            defer(function () use ($user): void {
                $user->markAsActive();
            });
        }

        return $next($request);
    }
}
