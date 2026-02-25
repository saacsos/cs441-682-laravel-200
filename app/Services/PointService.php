<?php

namespace App\Services;

use App\Models\PointHistory;
use App\Models\PointLedger;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PointService
{
    private function cacheKey(User $user) {
        return "user_points:{$user->id}";
    }

    private function cacheKeyByQuarters(User $user) {
        return "user_points_by_quarters:{$user->id}";
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
            Cache::forget($this->cacheKeyByQuarters($user));
        });

    }

    public function redeemPoints(User $user, int $amountToRedeemed, ?string $description=null) {
        if ($this->getTotalPoints($user) < $amountToRedeemed) {
            throw new \Exception("You don't have enough points to redeem {$amountToRedeemed} point.");
        }

        DB::transaction(function() use ($user, $amountToRedeemed, $description) {
            $ledgers = PointLedger::query()
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->orderBy('expires_at')->orderBy('balance')->get();

            $amount = $amountToRedeemed;
            foreach ($ledgers as $ledger) {
                if ($amount <= 0) break;
                if ($ledger->balance < $amount) {
                    $amount -= $ledger->balance;
                    $ledger->balance = 0;
                    $ledger->used_amount = $ledger->amount;
                } else {
                    $ledger->used_amount += $amount;
                    $ledger->balance -= $amount;
                    $amount = 0;
                }
                $ledger->save();
            }

            PointHistory::query()->create([
                'user_id' => $user->id,
                'type' => 'redeemed',
                'amount' => $amountToRedeemed,
                'description' => $description,
            ]);

            Cache::forget($this->cacheKey($user));
            Cache::forget($this->cacheKeyByQuarters($user));
        });

    }

    public function getPointsByQuarters(User $user) {
        return Cache::remember($this->cacheKeyByQuarters($user), 60 * 60 * 24, function() use ($user) {
            $ledgers = PointLedger::query()
                ->select(
                    DB::raw('DATE(expires_at) as expire_date'),
                    DB::raw('SUM(balance) as total_points'),
                )
                ->where('user_id', $user->id)
                ->where('balance', '>', 0)
                ->where('expires_at', '>', now())
                ->groupBy('expire_date')
                ->orderBy('expire_date')
                ->get();

            return $ledgers->map(function($ledger) {
                $date = Carbon::parse($ledger->expire_date);
                return [
                    'expire_date' => $date->format('Y-m-d'),
                    'total_points' => $ledger->total_points,
                    'quarter_label' => "Q{$date->quarter} {$date->year}"
                ];
            });
        });
    }
}
