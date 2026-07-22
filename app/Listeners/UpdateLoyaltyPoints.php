<?php

namespace App\Listeners;

use App\Events\GuestCheckedOut;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyTransaction;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateLoyaltyPoints implements ShouldQueue
{
    public $queue = 'low';

    public function handle(GuestCheckedOut $event): void
    {
        $booking = $event->booking;
        $guest = $booking->guest;

        if (!$guest) {
            return;
        }

        try {
            DB::transaction(function () use ($booking, $guest) {
                $loyaltyProgram = LoyaltyProgram::where('hotel_id', $booking->hotel_id)
                    ->where('is_active', true)
                    ->first();

                if (!$loyaltyProgram) {
                    return;
                }

                $pointsEarned = (int) floor(($booking->total_amount ?? 0) * $loyaltyProgram->points_per_currency);

                if ($pointsEarned <= 0) {
                    return;
                }

                LoyaltyTransaction::create([
                    'guest_id' => $guest->id,
                    'loyalty_program_id' => $loyaltyProgram->id,
                    'booking_id' => $booking->id,
                    'points' => $pointsEarned,
                    'type' => 'earned',
                    'description' => 'Points earned from booking #' . $booking->booking_number,
                ]);

                $guest->increment('loyalty_points', $pointsEarned);
                $guest->increment('total_stays');
                $guest->increment('total_spent', $booking->total_amount ?? 0);

                $this->updateLoyaltyTier($guest, $loyaltyProgram);
            });
        } catch (\Exception $e) {
            Log::error('Failed to update loyalty points', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function updateLoyaltyTier($guest, LoyaltyProgram $program): void
    {
        $totalPoints = $guest->loyalty_points;

        $tiers = $program->tiers ?? [
            ['name' => 'Bronze', 'min_points' => 0],
            ['name' => 'Silver', 'min_points' => 500],
            ['name' => 'Gold', 'min_points' => 1500],
            ['name' => 'Platinum', 'min_points' => 5000],
        ];

        $tier = 'regular';
        foreach ($tiers as $t) {
            if ($totalPoints >= ($t['min_points'] ?? 0)) {
                $tier = $t['name'];
            }
        }

        if ($guest->loyalty_tier !== $tier) {
            $guest->update(['loyalty_tier' => $tier]);
        }
    }
}
