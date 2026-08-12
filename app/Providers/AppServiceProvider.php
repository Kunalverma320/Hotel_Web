<?php

namespace App\Providers;

use App\Http\Middleware\AuditLog;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\SetCurrentHotel;
use App\Models\Branch;
use App\Models\Booking;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Department;
use App\Models\Employee;
use App\Models\FinanceAccount;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\InventoryItem;
use App\Models\Language;
use App\Models\Room;
use App\Models\Setting;
use App\Models\Timezone;
use App\Repositories\BranchRepository;
use App\Repositories\BookingRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\EmployeeRepository;
use App\Repositories\FinanceRepository;
use App\Repositories\GuestRepository;
use App\Repositories\HotelRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\RoomRepository;
use App\Services\BackupService;
use App\Services\BookingService;
use App\Services\ExportService;
use App\Services\FinanceService;
use App\Services\HousekeepingService;
use App\Services\InventoryService;
use App\Services\NotificationService;
use App\Services\ReportService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(HotelRepository::class, fn () => new HotelRepository(new Hotel));
        $this->app->singleton(CompanyRepository::class, fn () => new CompanyRepository(new Company));
        $this->app->singleton(BranchRepository::class, fn () => new BranchRepository(new Branch));
        $this->app->singleton(RoomRepository::class, fn () => new RoomRepository(new Room));
        $this->app->singleton(BookingRepository::class, fn () => new BookingRepository(new Booking));
        $this->app->singleton(GuestRepository::class, fn () => new GuestRepository(new Guest));
        $this->app->singleton(FinanceRepository::class, fn () => new FinanceRepository(new FinanceAccount));
        $this->app->singleton(EmployeeRepository::class, fn () => new EmployeeRepository(new Employee));
        $this->app->singleton(InventoryRepository::class, fn () => new InventoryRepository(new InventoryItem));

        $this->app->singleton(BookingService::class);
        $this->app->singleton(FinanceService::class);
        $this->app->singleton(HousekeepingService::class);
        $this->app->singleton(InventoryService::class);
        $this->app->singleton(ReportService::class);
        $this->app->singleton(NotificationService::class);
        $this->app->singleton(ExportService::class);
        $this->app->singleton(BackupService::class);
    }

    public function boot(): void
    {

        $this->app['router']->aliasMiddleware('set-current-hotel', SetCurrentHotel::class);
        $this->app['router']->aliasMiddleware('check-permission', CheckPermission::class);
        $this->app['router']->aliasMiddleware('audit-log', AuditLog::class);

        View::composer('admin.*', function ($view) {
            $currentHotelId = session('current_hotel_id');
            $currentHotel = $currentHotelId ? Hotel::find($currentHotelId) : null;
            $allHotels = Hotel::active()->orderBy('name')->get();
            if ($allHotels->isEmpty()) {
                $allHotels = Hotel::orderBy('name')->get();
            }

            $view->with('currentHotel', $currentHotel);
            $view->with('allHotels', $allHotels);
            $view->with('appName', config('app.name'));
            $view->with('appSettings', Setting::pluck('value', 'key')->toArray());
        });

        View::composer(['admin.settings.*', 'admin.companies.*'], function ($view) {
            $view->with('currencies', Currency::orderBy('name')->get());
            $view->with('timezones', Timezone::orderBy('name')->get());
            $view->with('languages', Language::orderBy('name')->get());
        });

        View::composer(['admin.layouts.partials.sidebar', 'admin.layouts.partials.header'], function ($view) {
            $view->with('departments', Department::orderBy('name')->get());
        });
    }
}
