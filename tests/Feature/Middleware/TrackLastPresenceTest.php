<?php

use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;
use Mey\Spine\Http\Middleware\TrackLastPresence;
use Mey\Spine\Tests\Fixtures\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Route::get('/test', fn (): Response => response()->noContent())
        ->middleware(TrackLastPresence::class);
});

test('records activity on first request', function (): void {
    // Arrange
    Date::setTestNow($now = Carbon::parse('2025-08-18 12:00:00'));

    $user = User::factory()->create();

    // Act
    actingAs($user)->withServerVariables([
        'REMOTE_ADDR' => '1.2.3.4',
    ])->get('/test');

    // Assert
    expect($user->last_active_at)->toEqual($now);
    expect($user->last_active_ip)->toBe('1.2.3.4');
});

test('prevents activity update within throttle window for same IP', function (): void {
    // Arrange
    Date::setTestNow(Carbon::parse('2025-08-18 12:00:30'));

    $user = User::factory()->create([
        'last_active_at' => $now = Carbon::parse('2025-08-18 12:00:00'),
        'last_active_ip' => '1.2.3.4',
    ]);

    // Act
    actingAs($user)->withServerVariables([
        'REMOTE_ADDR' => '1.2.3.4',
    ])->get('/test');

    // Assert
    expect($user->last_active_at)->toEqual($now);
    expect($user->last_active_ip)->toBe('1.2.3.4');
});

test('updates activity if IP changes, even within throttle window', function (): void {
    // Arrange
    Date::setTestNow($now = Carbon::parse('2025-08-18 12:00:30'));

    $user = User::factory()->create([
        'last_active_at' => Carbon::parse('2025-08-18 12:00:00'),
        'last_active_ip' => '1.2.3.4',
    ]);

    // Act
    actingAs($user)->withServerVariables([
        'REMOTE_ADDR' => '5.6.7.8',
    ])->get('/test');

    // Assert
    expect($user->last_active_at)->toEqual($now);
    expect($user->last_active_ip)->toBe('5.6.7.8');
});

test('updates activity after throttle window', function (): void {
    // Arrange
    Date::setTestNow($now = Carbon::parse('2025-08-18 12:01:01'));

    $user = User::factory()->create([
        'last_active_at' => Carbon::parse('2025-08-18 12:00:00'),
        'last_active_ip' => '1.2.3.4',
    ]);

    // Act
    actingAs($user)->withServerVariables([
        'REMOTE_ADDR' => '1.2.3.4',
    ])->get('/test');

    // Assert
    expect($user->last_active_at)->toEqual($now);
    expect($user->last_active_ip)->toBe('1.2.3.4');
});
