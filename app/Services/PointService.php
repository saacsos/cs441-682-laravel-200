<?php

namespace App\Services;

use App\Models\PointHistory;
use App\Models\PointLedger;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PointService
{
    private function cacheKey(User $user) {
        return "user_points:{$user->id}";
    }

    public function getTotalPoints(User $user) {
        return Cache::remember($this->cacheKey($user), 60 * 60 * 24, function() use ($user) {
            return PointLedger::query()
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->sum('balance');
        });
    }

    public function earnPoints(User $user, int $amount, ?string $description=null) {
        DB::transaction(function() use ($user, $amount, $description) {
            $expiresAt = now()->addQuarter()->endOfQuarter()->endOfDay();

            PointLedger::query()->create([
                'user_id' => $user->id,
                'amount' => $amount,
                'balance' => $amount,
                'expires_at' => $expiresAt,
            ]);

            PointHistory::query()->create([
                'user_id' => $user->id,
                'type' => 'earned',
                'amount' => $amount,
                'description' => $description,
            ]);

            Cache::forget($this->cacheKey($user));
        });

    }

    public function redeemPoints(User $user, int $amount, ?string $description=null) {

    }

    public function getPointsByQuarters(User $user) {

    }
}
