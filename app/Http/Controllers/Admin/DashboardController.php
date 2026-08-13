<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index(Request $request)
    {
        $hotelId = session('current_hotel_id');
        $period = $request->get('period', 'week');
        $today = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $totalRooms = $this->getTotalRooms($hotelId);
        $occupiedRooms = $this->getOccupiedRooms($hotelId, $today, $todayEnd);
        $availableRooms = $totalRooms - $occupiedRooms;
        $maintenanceRooms = $this->getMaintenanceRooms($hotelId);
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        $stats = [
            'total_revenue' => $this->getTotalRevenue($hotelId, $period),
            'revenue_change' => $this->getRevenueChange($hotelId),
            'occupancy_rate' => $occupancyRate,
            'today_checkin' => $this->getTodayCheckins($hotelId, $today, $todayEnd),
            'today_checkout' => $this->getTodayCheckouts($hotelId, $today, $todayEnd),
            'pending_payments' => $this->getPendingPayments($hotelId),
            'pending_count' => $this->getPendingPaymentsCount($hotelId),
            'total_rooms' => $totalRooms,
            'available_rooms' => $availableRooms,
            'occupied_rooms' => $occupiedRooms,
            'maintenance_rooms' => $maintenanceRooms,
        ];

        $revenueLabels = $this->getRevenueLabels($period);
        $revenueData = $this->getRevenueData($hotelId, $period);

        $checkins = $this->getTodayCheckinList($hotelId, $today, $todayEnd);
        $checkouts = $this->getTodayCheckoutList($hotelId, $today, $todayEnd);

        $roomStatusLabels = ['Available', 'Occupied', 'Reserved', 'Maintenance', 'Out of Order'];
        $roomStatusData = $this->getRoomStatusData($hotelId, $today, $todayEnd);

        $hkLabels = ['Clean', 'Dirty', 'In Progress', 'Inspected'];
        $hkData = $this->getHousekeepingData($hotelId);

        $recentBookings = $this->getRecentBookings($hotelId);
        $topCustomers = $this->getTopCustomers($hotelId);
        $topHotels = $this->getTopHotels();
        $topRooms = $this->getTopRooms($hotelId);

        $hotels = Auth::user()->hotel ? collect([Auth::user()->hotel]) : Hotel::orderBy('name')->get();

        return view('admin.dashboard.index', compact(
            'stats', 'revenueLabels', 'revenueData',
            'checkins', 'checkouts',
            'roomStatusLabels', 'roomStatusData',
            'hkLabels', 'hkData',
            'recentBookings', 'topCustomers',
            'topHotels', 'topRooms', 'hotels'
        ));
    }

    protected function getTotalRooms($hotelId)
    {
        $query = DB::table('rooms');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getOccupiedRooms($hotelId, $today, $todayEnd)
    {
        $query = DB::table('bookings')
            ->where('status', 'checked_in')
            ->where('check_in_date', '<=', $todayEnd)
            ->where('check_out_date', '>', $today);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getMaintenanceRooms($hotelId)
    {
        $query = DB::table('rooms')->where('status', 'maintenance');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getTotalRevenue($hotelId, $period)
    {
        $query = DB::table('payments')->where('status', 'completed');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', now()->toDateString());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        return $query->sum('amount') ?? 0;
    }

    protected function getRevenueChange($hotelId)
    {
        $query = DB::table('payments')->where('status', 'completed');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        $thisMonth = (clone $query)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount');
        $lastMonth = (clone $query)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('amount');
        if ($lastMonth == 0) return $thisMonth > 0 ? 100 : 0;
        return round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1);
    }

    protected function getTodayCheckins($hotelId, $today, $todayEnd)
    {
        $query = DB::table('bookings')
            ->whereDate('check_in_date', $today->toDateString())
            ->whereNotIn('status', ['cancelled']);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getTodayCheckouts($hotelId, $today, $todayEnd)
    {
        $query = DB::table('bookings')
            ->whereDate('check_out_date', $today->toDateString())
            ->whereNotIn('status', ['cancelled']);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getPendingPayments($hotelId)
    {
        $query = DB::table('payments')->where('status', 'pending');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->sum('amount') ?? 0;
    }

    protected function getPendingPaymentsCount($hotelId)
    {
        $query = DB::table('payments')->where('status', 'pending');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->count();
    }

    protected function getRevenueLabels($period)
    {
        $labels = [];
        switch ($period) {
            case 'today':
                for ($i = 0; $i < 24; $i += 3) {
                    $labels[] = sprintf('%02d:00', $i);
                }
                break;
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $labels[] = now()->subDays($i)->format('D');
                }
                break;
            case 'month':
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i += max(1, floor($daysInMonth / 10))) {
                    $labels[] = now()->day($i)->format('d');
                }
                break;
            case 'year':
                $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                break;
        }
        return $labels;
    }

    protected function getRevenueData($hotelId, $period)
    {
        $data = [];
        $query = DB::table('payments')->where('status', 'completed');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }

        switch ($period) {
            case 'today':
                for ($i = 0; $i < 24; $i += 3) {
                    $start = now()->hour($i)->minute(0)->second(0);
                    $end = now()->hour($i + 3)->minute(0)->second(0);
                    $data[] = (clone $query)->whereBetween('created_at', [$start, $end])->sum('amount') ?? 0;
                }
                break;
            case 'week':
                for ($i = 6; $i >= 0; $i--) {
                    $date = now()->subDays($i);
                    $data[] = (clone $query)->whereDate('created_at', $date)->sum('amount') ?? 0;
                }
                break;
            case 'month':
                $daysInMonth = now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i += max(1, floor($daysInMonth / 10))) {
                    $data[] = (clone $query)->whereDay('created_at', $i)->sum('amount') ?? 0;
                }
                break;
            case 'year':
                for ($m = 1; $m <= 12; $m++) {
                    $data[] = (clone $query)->whereMonth('created_at', $m)->whereYear('created_at', now()->year)->sum('amount') ?? 0;
                }
                break;
        }
        return $data;
    }

    protected function getTodayCheckinList($hotelId, $today, $todayEnd)
    {
        $query = Booking::with('guest', 'rooms')
            ->whereDate('check_in_date', $today->toDateString())
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('check_in_date')
            ->limit(10);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->get();
    }

    protected function getTodayCheckoutList($hotelId, $today, $todayEnd)
    {
        $query = Booking::with('guest', 'rooms')
            ->whereDate('check_out_date', $today->toDateString())
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('check_out_date')
            ->limit(10);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->get();
    }

    protected function getRoomStatusData($hotelId, $today, $todayEnd)
    {
        $total = $this->getTotalRooms($hotelId);
        $occupied = $this->getOccupiedRooms($hotelId, $today, $todayEnd);
        $maintenance = $this->getMaintenanceRooms($hotelId);

        $query = DB::table('rooms')->where('status', 'reserved');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        $reserved = $query->count();

        $outOfOrder = DB::table('rooms')->where('status', 'out_of_order')
            ->when($hotelId, fn($q) => $q->where('hotel_id', $hotelId))->count();
        $available = $total - $occupied - $reserved - $maintenance - $outOfOrder;

        return [$available, $occupied, $reserved, $maintenance, $outOfOrder];
    }

    protected function getHousekeepingData($hotelId)
    {
        $query = DB::table('rooms');
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return [
            (clone $query)->where('housekeeping_status', 'clean')->count(),
            (clone $query)->where('housekeeping_status', 'dirty')->count(),
            (clone $query)->where('housekeeping_status', 'in_progress')->count(),
            (clone $query)->where('housekeeping_status', 'inspection')->count(),
        ];
    }

    protected function getRecentBookings($hotelId)
    {
        $query = Booking::with('guest', 'rooms')
            ->latest()
            ->limit(10);
        if ($hotelId) {
            $query->where('hotel_id', $hotelId);
        }
        return $query->get();
    }

    protected function getTopCustomers($hotelId)
    {
        $query = DB::table('guests')
            ->select(
                'guests.id',
                'guests.email',
                DB::raw("TRIM(CONCAT(guests.first_name, ' ', COALESCE(guests.last_name, ''))) as name"),
                DB::raw('COUNT(bookings.id) as bookings_count'),
                DB::raw('COALESCE(SUM(bookings.total_amount), 0) as total_spent')
            )
            ->leftJoin('bookings', 'guests.id', '=', 'bookings.guest_id')
            ->groupBy('guests.id', 'guests.first_name', 'guests.last_name', 'guests.email')
            ->orderByDesc('total_spent')
            ->limit(5);
        if ($hotelId) {
            $query->where('bookings.hotel_id', $hotelId);
        }
        return $query->get();
    }

    protected function getTopHotels()
    {
        return DB::table('hotels')
            ->select(
                'hotels.id',
                'hotels.name',
                DB::raw('COUNT(DISTINCT bookings.id) as bookings_count'),
                DB::raw('COALESCE(SUM(payments.amount), 0) as revenue')
            )
            ->leftJoin('bookings', 'hotels.id', '=', 'bookings.hotel_id')
            ->leftJoin('payments', 'bookings.id', '=', 'payments.booking_id')
            ->groupBy('hotels.id', 'hotels.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();
    }

    protected function getTopRooms($hotelId)
    {
        $query = DB::table('rooms')
            ->select(
                'rooms.id',
                'rooms.room_number',
                'room_types.name as room_type_name',
                DB::raw('COUNT(DISTINCT booking_rooms.booking_id) as bookings_count'),
                DB::raw('COALESCE(SUM(bookings.total_amount), 0) as revenue')
            )
            ->leftJoin('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->leftJoin('booking_rooms', 'rooms.id', '=', 'booking_rooms.room_id')
            ->leftJoin('bookings', 'booking_rooms.booking_id', '=', 'bookings.id')
            ->groupBy('rooms.id', 'rooms.room_number', 'room_types.name')
            ->orderByDesc('bookings_count')
            ->limit(5);
        if ($hotelId) {
            $query->where('rooms.hotel_id', $hotelId);
        }
        $rooms = $query->get();

        foreach ($rooms as $room) {
            $room->type = (object) ['name' => $room->room_type_name ?? 'N/A'];
        }

        return $rooms;
    }
}
