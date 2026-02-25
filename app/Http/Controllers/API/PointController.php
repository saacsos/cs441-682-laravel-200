<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\SendPointNotification;
use App\Mail\PointEarned;
use App\Mail\PointRedeemed;
use App\Models\PointHistory;
use App\Models\PointLedger;
use App\Services\PointService;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index(Request $request, PointService $pointService) {
        $user = $request->user();
        return response()->json([
            'data' => $pointService->getPointsByQuarters($user),
            'ledgers' => PointLedger::query()
                ->where('user_id', $user->id)
                ->where('expires_at', '>', now())
                ->orderBy('expires_at')
                ->orderBy('balance')
                ->get(),
            'histories' => PointHistory::query()
                ->where('user_id', $user->id)
                ->oldest()
                ->get()
        ]);
    }

    public function show(Request $request, PointService $pointService) {
        $user = $request->user();
        return response()->json([
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }

    public function earn(Request $request, PointService $pointService) {
        $user = $request->user();
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);
        $pointService->earnPoints($user, $request->amount, $request->description);
        SendPointNotification::dispatch($user,
            new PointEarned($request->amount, $pointService->getTotalPoints($user))
        );
        return response()->json([
            'success' => true,
            'message' => 'Points earned',
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }

    public function redeem(Request $request, PointService $pointService) {
        $user = $request->user();
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);
        try {
            $pointService->redeemPoints($user, $request->amount, $request->description);
            SendPointNotification::dispatch($user,
                new PointRedeemed($request->amount, $pointService->getTotalPoints($user))
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'total_points' => $pointService->getTotalPoints($user),
            ])->setStatusCode(400);
        }
        return response()->json([
            'success' => true,
            'message' => 'Points redeemed',
            'total_points' => $pointService->getTotalPoints($user),
        ]);
    }
}
