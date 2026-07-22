<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\FinanceAccount;
use App\Models\Guest;
use App\Models\HousekeepingTask;
use App\Models\Payment;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function dailyReport(int|string $hotelId, string $date): array
    {
        $bookings = Booking::where('hotel_id', $hotelId)
            ->whereDate('created_at', $date)
            ->get();

        return [
            'date' => $date,
            'new_bookings' => $bookings->count(),
            'revenue' => Payment::where('hotel_id', $hotelId)
                ->whereDate('payment_date', $date)
                ->sum('amount'),
            'check_ins' => $bookings->where('status', 'checked_in')->count(),
            'check_outs' => $bookings->where('status', 'checked_out')->count(),
            'cancellations' => $bookings->where('status', 'cancelled')->count(),
            'occupancy_rate' => $this->calculateOccupancy($hotelId, $date, $date),
        ];
    }

    public function monthlyReport(int|string $hotelId, int $month, int $year): array
    {
        $from = "{$year}-{$month}-01";
        $to = now()->create($year, $month)->endOfMonth()->toDateString();

        $bookings = Booking::where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $payments = Payment::where('hotel_id', $hotelId)
            ->whereBetween('payment_date', [$from, $to])
            ->get();

        return [
            'period' => "{$year}-{$month}",
            'total_bookings' => $bookings->count(),
            'total_revenue' => $payments->sum('amount'),
            'average_daily_rate' => $payments->sum('amount') / now()->create($year, $month)->daysInMonth,
            'occupancy_rate' => $this->calculateOccupancy($hotelId, $from, $to),
            'revenue_by_type' => $payments->groupBy('payment_method')->map(fn ($p) => $p->sum('amount')),
            'cancellation_rate' => $bookings->where('status', 'cancelled')->count() / max($bookings->count(), 1) * 100,
        ];
    }

    public function occupancyReport(int|string $hotelId, string $from, string $to): array
    {
        $totalRooms = Room::where('hotel_id', $hotelId)->count();

        $occupiedDays = DB::table('bookings')
            ->where('hotel_id', $hotelId)
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->where('date_from', '<=', $to)
            ->where('date_to', '>=', $from)
            ->selectRaw('SUM(DATEDIFF(LEAST(date_to, ?), GREATEST(date_from, ?)) + 1) as occupied_room_days', [$to, $from])
            ->value('occupied_room_days') ?? 0;

        $totalDays = (new \DateTime($from))->diff(new \DateTime($to))->days + 1;
        $availableRoomDays = $totalRooms * $totalDays;

        return [
            'period' => ['from' => $from, 'to' => $to],
            'total_rooms' => $totalRooms,
            'occupied_room_days' => $occupiedDays,
            'available_room_days' => $availableRoomDays,
            'occupancy_rate' => $availableRoomDays > 0 ? round($occupiedDays / $availableRoomDays * 100, 2) : 0,
        ];
    }

    public function revenueReport(int|string $hotelId, string $from, string $to): array
    {
        $payments = Payment::where('hotel_id', $hotelId)
            ->whereBetween('payment_date', [$from, $to])
            ->get();

        return [
            'period' => ['from' => $from, 'to' => $to],
            'total_revenue' => $payments->sum('amount'),
            'revenue_by_method' => $payments->groupBy('payment_method')->map(fn ($p) => $p->sum('amount')),
            'revenue_by_day' => $payments->groupBy(fn ($p) => $p->payment_date->toDateString())->map(fn ($p) => $p->sum('amount')),
            'average_payment' => $payments->avg('amount'),
            'payment_count' => $payments->count(),
        ];
    }

    public function guestReport(int|string $hotelId, string $from, string $to): array
    {
        $guests = Guest::whereHas('bookings', function ($query) use ($hotelId, $from, $to) {
            $query->where('hotel_id', $hotelId)
                ->whereBetween('created_at', [$from, $to]);
        })->get();

        return [
            'period' => ['from' => $from, 'to' => $to],
            'total_guests' => $guests->count(),
            'new_guests' => $guests->where('created_at', '>=', $from)->count(),
            'returning_guests' => $guests->where('created_at', '<', $from)->count(),
            'vip_guests' => $guests->where('is_vip', true)->count(),
            'nationality_breakdown' => $guests->groupBy('nationality')->map(fn ($g) => $g->count()),
            'average_loyalty_points' => $guests->avg('loyalty_points'),
        ];
    }

    public function bookingReport(int|string $hotelId, string $from, string $to): array
    {
        $bookings = Booking::where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return [
            'period' => ['from' => $from, 'to' => $to],
            'total_bookings' => $bookings->count(),
            'by_status' => $bookings->groupBy('status')->map(fn ($b) => $b->count()),
            'average_length_of_stay' => $bookings->avg(fn ($b) => \Carbon\Carbon::parse($b->date_from)->diffInDays($b->date_to)),
            'advance_bookings' => $bookings->filter(fn ($b) => \Carbon\Carbon::parse($b->date_from)->isFuture())->count(),
            'direct_bookings' => $bookings->where('source', 'direct')->count(),
            'online_bookings' => $bookings->where('source', 'online')->count(),
        ];
    }

    public function gstReport(int|string $hotelId, string $from, string $to): array
    {
        $invoices = DB::table('invoices')
            ->where('hotel_id', $hotelId)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        return [
            'period' => ['from' => $from, 'to' => $to],
            'total_taxable_amount' => $invoices->sum('room_charges'),
            'total_gst_collected' => $invoices->sum('tax_amount'),
            'cgst' => $invoices->sum('tax_amount') / 2,
            'sgst' => $invoices->sum('tax_amount') / 2,
            'igst' => 0,
            'invoice_count' => $invoices->count(),
        ];
    }

    public function housekeepingReport(int|string $hotelId, string $date): array
    {
        $tasks = HousekeepingTask::where('hotel_id', $hotelId)
            ->whereDate('created_at', $date)
            ->get();

        return [
            'date' => $date,
            'total_tasks' => $tasks->count(),
            'completed' => $tasks->where('status', 'completed')->count(),
            'in_progress' => $tasks->where('status', 'in_progress')->count(),
            'pending' => $tasks->where('status', 'pending')->count(),
            'by_type' => $tasks->groupBy('type')->map(fn ($t) => $t->count()),
            'average_completion_time' => $tasks->where('status', 'completed')
                ->avg(fn ($t) => $t->completed_at?->diffInMinutes($t->assigned_at)),
        ];
    }

    protected function calculateOccupancy(int|string $hotelId, string $from, string $to): float
    {
        $report = $this->occupancyReport($hotelId, $from, $to);
        return $report['occupancy_rate'];
    }
}
