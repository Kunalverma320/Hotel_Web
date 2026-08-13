<?php

use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\CheckInController;
use App\Http\Controllers\Admin\CheckOutController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CrmController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\EmailController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\FloorController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\Admin\GymController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\HousekeepingController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\LaundryController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\PayrollController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ReportFinanceController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Admin\RoomCategoryController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomServiceController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\SpaController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $hotels = \App\Models\Hotel::with(['roomTypes' => function ($query) {
        $query->where('status', true);
    }, 'amenities'])->where('status', true)->get();
    return view('welcome', compact('hotels'));
});

Route::view('/about', 'about');
Route::view('/contact', 'contact');
Route::view('/privacy', 'privacy');
Route::view('/terms', 'terms');

// Customer Login Routes
Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

// Admin Login Routes (Separate)
Route::get('admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

// Admin 2FA Verification Routes
Route::post('admin/login/2fa/verify', [AuthController::class, 'verifyTwoFactor'])->name('admin.login.2fa.verify');
Route::post('admin/login/2fa/backup', [AuthController::class, 'verifyBackupCode'])->name('admin.login.2fa.backup');
Route::get('admin/login/2fa', [AuthController::class, 'showTwoFactorForm'])->name('admin.login.2fa.form');

// 2FA Route Aliases
Route::post('login/2fa/verify', [AuthController::class, 'verifyTwoFactor'])->name('login.2fa.verify');
Route::post('login/2fa/backup', [AuthController::class, 'verifyBackupCode'])->name('login.2fa.backup');
Route::get('login/2fa', [AuthController::class, 'showTwoFactorForm'])->name('login.2fa.form');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('my-bookings', [\App\Http\Controllers\CustomerBookingController::class, 'index'])->name('my-bookings');
    Route::post('guest-bookings', [\App\Http\Controllers\CustomerBookingController::class, 'store'])->name('guest-bookings.store');
});

Route::get('password/reset', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('password/email', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [AuthController::class, 'resetPassword'])->name('password.update');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'set-current-hotel', 'check-admin-access'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/switch-hotel/{hotel}', function ($hotelId) {
        if ($hotelId === 'all' || $hotelId === '0') {
            session()->forget('current_hotel_id');
            return redirect()->back()->with('success', 'Switched to All Hotels');
        }
        $hotel = \App\Models\Hotel::findOrFail($hotelId);
        session(['current_hotel_id' => $hotel->id]);
        return redirect()->back()->with('success', 'Switched to hotel: ' . $hotel->name);
    })->name('switch-hotel');

    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::resource('companies', CompanyController::class);
    Route::controller(CompanyController::class)->prefix('companies')->name('companies.')->group(function () {
        Route::get('{company}/branches-list', 'getBranches')->name('branches-list');
        Route::post('{company}/smtp', 'updateSmtpSettings')->name('smtp');
        Route::post('{company}/sms', 'updateSmsSettings')->name('sms');
        Route::post('{company}/whatsapp', 'updateWhatsAppSettings')->name('whatsapp');
        Route::post('{company}/payment', 'updatePaymentSettings')->name('payment');
        Route::post('{company}/google-maps', 'updateGoogleMaps')->name('google-maps');
    });

    Route::resource('branches', BranchController::class);
    Route::controller(BranchController::class)->prefix('branches')->name('branches.')->group(function () {
        Route::patch('{branch}/status/{status}', 'updateStatus')->name('update-status');
    });

    Route::resource('hotels', HotelController::class);
    Route::controller(HotelController::class)->prefix('hotels')->name('hotels.')->group(function () {
        Route::get('{hotel}/options', 'getOptions')->name('options');
        Route::get('{hotel}/images', 'images')->name('images');
        Route::post('{hotel}/images', 'uploadImage');
        Route::delete('images/{image}', 'deleteImage')->name('images.delete');
        Route::get('{hotel}/amenities', 'amenities')->name('amenities');
        Route::post('{hotel}/amenities', 'updateAmenities');
        Route::get('{hotel}/rules', 'rules')->name('rules');
        Route::get('{hotel}/nearby-places', 'nearbyPlaces')->name('nearby-places');
        Route::get('{hotel}/policies', 'policies')->name('policies');
        Route::post('{hotel}/policies', 'updatePolicies');
        Route::post('{hotel}/status/{status}', 'updateStatus')->name('status');
        Route::patch('{hotel}/status/{status}', 'updateStatus')->name('update-status');
    });

    Route::resource('room-types', RoomTypeController::class);
    Route::controller(RoomTypeController::class)->prefix('room-types')->name('room-types.')->group(function () {
        Route::post('{roomType}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    Route::resource('room-categories', RoomCategoryController::class);

    Route::resource('floors', FloorController::class);
    Route::controller(FloorController::class)->prefix('floors')->name('floors.')->group(function () {
        Route::post('{floor}/toggle-status', 'toggleStatus')->name('toggle-status');
        Route::get('hotel/{hotel}', 'getFloorsByHotel')->name('by-hotel');
    });

    Route::controller(RoomController::class)->prefix('rooms')->name('rooms.')->group(function () {
        Route::get('view-3d', 'view3D')->name('view3d');
        Route::get('availability', 'availability')->name('availability');
        Route::get('get-availability', 'getAvailability')->name('get-availability');
        Route::post('{room}/status/{status}', 'updateStatus')->name('update-status');
        Route::post('{room}/housekeeping-status/{status}', 'updateHousekeepingStatus')->name('update-housekeeping-status');
        Route::post('bulk-update-status', 'bulkUpdateStatus')->name('bulk-update-status');
    });
    Route::resource('rooms', RoomController::class);

    Route::resource('bookings', BookingController::class);
    Route::controller(BookingController::class)->prefix('bookings')->name('bookings.')->group(function () {
        Route::post('{booking}/confirm', 'confirm')->name('confirm');
        Route::post('{booking}/cancel', 'cancel')->name('cancel');
        Route::post('{booking}/no-show', 'noShow')->name('no-show');
        Route::get('{booking}/print-invoice', 'printInvoice')->name('print-invoice');
        Route::get('available-room-types', 'getAvailableRoomTypes')->name('available-room-types');
        Route::get('available-rooms', 'getAvailableRooms')->name('available-rooms');
    });

    Route::resource('check-ins', CheckInController::class)->only(['index', 'create', 'store', 'show']);
    Route::resource('check-outs', CheckOutController::class)->only(['index', 'create', 'store', 'show']);
    Route::controller(CheckInController::class)->prefix('check-ins')->name('check-ins.')->group(function () {
        Route::get('booking/{booking}', 'forBooking')->name('for-booking');
    });
    Route::controller(CheckOutController::class)->prefix('check-outs')->name('check-outs.')->group(function () {
        Route::get('check-in/{checkIn}', 'forCheckIn')->name('for-check-in');
    });

    Route::resource('guests', GuestController::class);
    Route::controller(GuestController::class)->prefix('guests')->name('guests.')->group(function () {
        Route::get('{guest}/documents', 'documents')->name('documents');
        Route::get('{guest}/preferences', 'preferences')->name('preferences');
        Route::get('{guest}/history', 'history')->name('history');
        Route::post('{guest}/blacklist', 'blacklist')->name('blacklist');
        Route::get('{guest}/loyalty', 'loyalty')->name('loyalty');
    });

    Route::prefix('crm')->name('crm.')->controller(CrmController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('leads', 'leads')->name('leads');
        Route::post('leads', 'leadsStore');
        Route::get('leads/{lead}', 'leadsShow')->name('leads.show');
        Route::post('leads/{lead}/convert', 'convertToGuest')->name('leads.convert');
        Route::post('leads/{lead}/status', 'updateLeadStatus')->name('leads.status');
        Route::get('follow-ups', 'followUps')->name('follow-ups');
        Route::post('follow-ups', 'storeFollowUp');
        Route::post('follow-ups/{followUp}/complete', 'completeFollowUp')->name('follow-ups.complete');
        Route::get('notes/{guest}', 'notes')->name('notes');
        Route::post('notes/{guest}', 'storeNote');
        Route::delete('notes/{note}', 'destroyNote')->name('notes.destroy');
    });

    Route::resource('housekeeping', HousekeepingController::class);
    Route::controller(HousekeepingController::class)->prefix('housekeeping')->name('housekeeping.')->group(function () {
        Route::get('reports', 'reports')->name('reports');
        Route::post('{id}/status/{status}', 'updateStatus')->name('update-status');
    });

    Route::resource('maintenance', MaintenanceController::class);
    Route::controller(MaintenanceController::class)->prefix('maintenance')->name('maintenance.')->group(function () {
        Route::post('{request}/assign', 'assign')->name('assign');
        Route::post('{request}/start', 'start')->name('start');
        Route::post('{request}/complete', 'complete')->name('complete');
        Route::post('{request}/approve', 'approve')->name('approve');
        Route::post('{request}/log-hours', 'logHours')->name('log-hours');
    });

    Route::prefix('restaurant')->name('restaurant.')->controller(RestaurantController::class)->group(function () {
        Route::get('/', 'dashboard')->name('dashboard');
        Route::resource('tables', \App\Http\Controllers\Admin\TablesController::class)->except(['show'])->names([
            'index' => 'tables.index',
            'create' => 'tables.create',
            'store' => 'tables.store',
            'edit' => 'tables.edit',
            'update' => 'tables.update',
            'destroy' => 'tables.destroy',
        ]);
        Route::post('tables/{table}/status', [\App\Http\Controllers\Admin\RestaurantController::class, 'updateTableStatus'])->name('tables.status');
        Route::resource('menu-categories', \App\Http\Controllers\Admin\MenuCategoryController::class)->names([
            'index' => 'menu-categories.index',
            'create' => 'menu-categories.create',
            'store' => 'menu-categories.store',
            'edit' => 'menu-categories.edit',
            'update' => 'menu-categories.update',
            'destroy' => 'menu-categories.destroy',
        ]);
        Route::resource('menu-items', \App\Http\Controllers\Admin\MenuItemController::class)->names([
            'index' => 'menu-items.index',
            'create' => 'menu-items.create',
            'store' => 'menu-items.store',
            'edit' => 'menu-items.edit',
            'update' => 'menu-items.update',
            'destroy' => 'menu-items.destroy',
        ]);
        Route::get('kitchen', [\App\Http\Controllers\Admin\RestaurantController::class, 'kitchen'])->name('kitchen');
        Route::get('kitchen/orders', [\App\Http\Controllers\Admin\RestaurantController::class, 'kitchenOrders'])->name('kitchen.orders');
        Route::post('kitchen/orders/{order}/status', [\App\Http\Controllers\Admin\RestaurantController::class, 'updateKitchenOrderStatus'])->name('kitchen.orders.status');
        Route::post('pos/order', [\App\Http\Controllers\Admin\RestaurantController::class, 'posPlaceOrder'])->name('pos.order');
        Route::get('pos', [\App\Http\Controllers\Admin\RestaurantController::class, 'posDashboard'])->name('pos');
        Route::get('orders', [\App\Http\Controllers\Admin\RestaurantController::class, 'orders'])->name('orders');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\RestaurantController::class, 'orderShow'])->name('orders.show');
        Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\RestaurantController::class, 'orderStatus'])->name('orders.status');
        Route::get('reports', [\App\Http\Controllers\Admin\RestaurantController::class, 'reports'])->name('reports');
    });

    Route::resource('room-service', RoomServiceController::class);
    Route::controller(RoomServiceController::class)->prefix('room-service')->name('room-service.')->group(function () {
        Route::post('{order}/status', 'updateStatus')->name('status');
        Route::get('kitchen', 'kitchenView')->name('kitchen');
    });

    Route::resource('laundry', LaundryController::class);
    Route::controller(LaundryController::class)->prefix('laundry')->name('laundry.')->group(function () {
        Route::post('{order}/status', 'updateStatus')->name('status');
        Route::get('{order}/items', 'items')->name('items');
        Route::post('{order}/items', 'storeItem');
    });

    Route::resource('spa', SpaController::class);
    Route::controller(SpaController::class)->prefix('spa')->name('spa.')->group(function () {
        Route::get('appointments', 'appointments')->name('appointments');
        Route::post('appointments/{appointment}/status', 'appointmentStatus')->name('appointments.status');
        Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class)->names([
            'index' => 'packages.index',
            'create' => 'packages.create',
            'store' => 'packages.store',
            'edit' => 'packages.edit',
            'update' => 'packages.update',
            'destroy' => 'packages.destroy',
        ]);
    });

    Route::prefix('gym')->name('gym.')->controller(GymController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::resource('equipment', \App\Http\Controllers\Admin\EquipmentController::class)->names([
            'index' => 'equipment.index',
            'create' => 'equipment.create',
            'store' => 'equipment.store',
            'edit' => 'equipment.edit',
            'update' => 'equipment.update',
            'destroy' => 'equipment.destroy',
        ]);
        Route::resource('memberships', \App\Http\Controllers\Admin\MembershipController::class)->names([
            'index' => 'memberships.index',
            'create' => 'memberships.create',
            'store' => 'memberships.store',
            'edit' => 'memberships.edit',
            'update' => 'memberships.update',
            'destroy' => 'memberships.destroy',
        ]);
        Route::get('schedules', 'schedules')->name('schedules');
        Route::post('schedules', 'storeSchedule');
        Route::delete('schedules/{schedule}', 'destroySchedule')->name('schedules.destroy');
    });

    Route::prefix('pool')->name('pool.')->controller(\App\Http\Controllers\Admin\PoolController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('schedules', 'schedules')->name('schedules');
        Route::post('schedules', 'storeSchedule');
        Route::delete('schedules/{schedule}', 'destroySchedule')->name('schedules.destroy');
        Route::post('schedules/{schedule}/status', 'updateScheduleStatus')->name('schedules.status');
    });

    Route::resource('events', EventController::class);
    Route::controller(EventController::class)->prefix('events')->name('events.')->group(function () {
        Route::get('calendar', 'calendar')->name('calendar');
        Route::post('{event}/status', 'updateStatus')->name('status');
    });

    Route::resource('inventory', InventoryController::class);
    Route::controller(InventoryController::class)->prefix('inventory')->name('inventory.')->group(function () {
        Route::get('categories', 'categories')->name('categories');
        Route::post('categories', 'storeCategory');
        Route::put('categories/{category}', 'updateCategory')->name('categories.update');
        Route::delete('categories/{category}', 'destroyCategory')->name('categories.destroy');
        Route::get('stock-movements', 'stockMovements')->name('stock-movements');
        Route::post('stock-adjust', 'stockAdjust')->name('stock-adjust');
        Route::get('low-stock', 'lowStock')->name('low-stock');
    });

    Route::resource('purchases', PurchaseController::class);
    Route::controller(PurchaseController::class)->prefix('purchases')->name('purchases.')->group(function () {
        Route::get('{purchase}/items', 'items')->name('items');
        Route::post('{purchase}/items', 'storeItem');
        Route::post('{purchase}/approve', 'approve')->name('approve');
        Route::post('{purchase}/receive', 'receive')->name('receive');
        Route::post('{purchase}/cancel', 'cancel')->name('cancel');
    });

    Route::resource('suppliers', SupplierController::class);
    Route::controller(SupplierController::class)->prefix('suppliers')->name('suppliers.')->group(function () {
        Route::post('{supplier}/toggle-status', 'toggleStatus')->name('toggle-status');
    });

    Route::prefix('finance')->name('finance.')->controller(FinanceController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('income', 'income')->name('income');
        Route::get('income/create', 'incomeCreate')->name('income.create');
        Route::post('income', 'incomeStore')->name('income.store');
        Route::get('income/{income}', 'incomeShow')->name('income.show');
        Route::get('expense', 'expense')->name('expense');
        Route::get('expense/create', 'expenseCreate')->name('expense.create');
        Route::post('expense', 'expenseStore')->name('expense.store');
        Route::get('expense/{expense}', 'expenseShow')->name('expense.show');
        Route::post('expense/{expense}/approve', 'expenseApprove')->name('expense.approve');
        Route::get('cashbook', 'cashBook')->name('cashbook');
        Route::get('bankbook', 'bankBook')->name('bankbook');
        Route::get('journal', 'journal')->name('journal');
        Route::get('journal/create', 'journalCreate')->name('journal.create');
        Route::post('journal', 'journalStore')->name('journal.store');
        Route::get('journal/{entry}', 'journalShow')->name('journal.show');
        Route::post('journal/{entry}/post', 'journalPost')->name('journal.post');
        Route::get('chart-of-accounts', 'chartOfAccounts')->name('coa');
        Route::get('chart-of-accounts/create', 'chartOfAccountsCreate')->name('coa.create');
        Route::post('chart-of-accounts', 'chartOfAccountsStore')->name('coa.store');
        Route::get('chart-of-accounts/{account}', 'chartOfAccountsEdit')->name('coa.edit');
        Route::post('chart-of-accounts/{account}', 'chartOfAccountsUpdate')->name('coa.update');
        Route::get('general-ledger', 'generalLedger')->name('general-ledger');
        Route::get('ledger/{account}', 'ledger')->name('ledger');
        Route::get('reports', 'reports')->name('reports');
        Route::get('reports/profit-loss', 'profitLoss')->name('reports.profit-loss');
        Route::get('reports/balance-sheet', 'balanceSheet')->name('reports.balance-sheet');
        Route::get('reports/trial-balance', 'trialBalance')->name('reports.trial-balance');
    });

    Route::resource('employees', EmployeeController::class);
    Route::controller(EmployeeController::class)->prefix('employees')->name('employees.')->group(function () {
        Route::get('{employee}/schedule', 'schedule')->name('schedule');
        Route::post('{employee}/schedule', 'updateSchedule');
    });

    Route::resource('departments', DepartmentController::class);
    Route::controller(DepartmentController::class)->prefix('departments')->name('departments.')->group(function () {
        Route::get('{department}/employees', 'employees')->name('employees');
    });

    Route::resource('designations', DesignationController::class);

    Route::resource('attendance', AttendanceController::class)->except(['show', 'edit', 'update']);
    Route::controller(AttendanceController::class)->prefix('attendance')->name('attendance.')->group(function () {
        Route::post('clock-in', 'clockIn')->name('clock-in');
        Route::post('clock-out', 'clockOut')->name('clock-out');
        Route::get('report', 'report')->name('report');
    });

    Route::resource('leaves', LeaveController::class);
    Route::controller(LeaveController::class)->prefix('leaves')->name('leaves.')->group(function () {
        Route::post('{leave}/approve', 'approve')->name('approve');
        Route::post('{leave}/reject', 'reject')->name('reject');
    });

    Route::resource('payroll', PayrollController::class);
    Route::controller(PayrollController::class)->prefix('payroll')->name('payroll.')->group(function () {
        Route::post('{payroll}/process', 'process')->name('process');
        Route::post('{payroll}/pay', 'pay')->name('pay');
        Route::get('reports', 'reports')->name('reports');
    });

    Route::resource('shifts', ShiftController::class);
    Route::controller(ShiftController::class)->prefix('shifts')->name('shifts.')->group(function () {
        Route::post('{shift}/assign', 'assign')->name('assign');
    });

    Route::resource('tasks', TaskController::class);
    Route::controller(TaskController::class)->prefix('tasks')->name('tasks.')->group(function () {
        Route::post('{task}/status', 'updateStatus')->name('status');
        Route::post('{task}/assign', 'assignUser')->name('assign');
        Route::post('{task}/comments', 'storeComment')->name('comments.store');
    });

    Route::resource('documents', DocumentController::class);

    Route::prefix('communications')->name('communications.')->group(function () {
        Route::resource('email', EmailController::class)->only(['index', 'create', 'store', 'show']);
        Route::controller(EmailController::class)->prefix('email')->name('email.')->group(function () {
            Route::post('{email}/send', 'send')->name('send');
            Route::post('{email}/resend', 'resend')->name('resend');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'storeTemplate');
            Route::get('logs', 'logs')->name('logs');
        });

        Route::resource('sms', SmsController::class)->only(['index', 'create', 'store', 'show']);
        Route::controller(SmsController::class)->prefix('sms')->name('sms.')->group(function () {
            Route::post('{sms}/send', 'send')->name('send');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'storeTemplate');
            Route::get('logs', 'logs')->name('logs');
        });

        Route::resource('whatsapp', WhatsappController::class)->only(['index', 'create', 'store', 'show']);
        Route::controller(WhatsappController::class)->prefix('whatsapp')->name('whatsapp.')->group(function () {
            Route::post('{whatsapp}/send', 'send')->name('send');
            Route::get('templates', 'templates')->name('templates');
            Route::post('templates', 'storeTemplate');
            Route::get('logs', 'logs')->name('logs');
        });
    });

    Route::prefix('cms')->name('cms.')->group(function () {
        Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->names([
            'index' => 'pages.index',
            'create' => 'pages.create',
            'store' => 'pages.store',
            'edit' => 'pages.edit',
            'update' => 'pages.update',
            'destroy' => 'pages.destroy',
        ]);
        Route::post('pages/{page}/status', [\App\Http\Controllers\Admin\CmsController::class, 'pageStatus'])->name('pages.status');
        Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class)->names([
            'index' => 'blogs.index',
            'create' => 'blogs.create',
            'store' => 'blogs.store',
            'edit' => 'blogs.edit',
            'update' => 'blogs.update',
            'destroy' => 'blogs.destroy',
        ]);
        Route::post('blogs/{blog}/status', [\App\Http\Controllers\Admin\CmsController::class, 'blogStatus'])->name('blogs.status');
        Route::resource('testimonials', \App\Http\Controllers\Admin\TestimonialController::class)->except(['show'])->names([
            'index' => 'testimonials.index',
            'create' => 'testimonials.create',
            'store' => 'testimonials.store',
            'edit' => 'testimonials.edit',
            'update' => 'testimonials.update',
            'destroy' => 'testimonials.destroy',
        ]);
        Route::post('testimonials/{testimonial}/status', [\App\Http\Controllers\Admin\CmsController::class, 'testimonialStatus'])->name('testimonials.status');
        Route::resource('faqs', \App\Http\Controllers\Admin\FaqController::class)->except(['show'])->names([
            'index' => 'faqs.index',
            'create' => 'faqs.create',
            'store' => 'faqs.store',
            'edit' => 'faqs.edit',
            'update' => 'faqs.update',
            'destroy' => 'faqs.destroy',
        ]);
        Route::post('faqs/{faq}/status', [\App\Http\Controllers\Admin\CmsController::class, 'faqStatus'])->name('faqs.status');
        Route::resource('gallery', \App\Http\Controllers\Admin\GalleryController::class)->except(['show', 'edit', 'update'])->names([
            'index' => 'gallery.index',
            'create' => 'gallery.create',
            'store' => 'gallery.store',
            'destroy' => 'gallery.destroy',
        ]);
    });

    Route::prefix('marketing')->name('marketing.')->group(function () {
        Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class)->names([
            'index' => 'coupons.index',
            'create' => 'coupons.create',
            'store' => 'coupons.store',
            'edit' => 'coupons.edit',
            'update' => 'coupons.update',
            'destroy' => 'coupons.destroy',
        ]);
        Route::post('coupons/{coupon}/status', [\App\Http\Controllers\Admin\CouponController::class, 'toggleStatus'])->name('coupons.status');

        Route::resource('gift-cards', \App\Http\Controllers\Admin\GiftCardController::class)->names([
            'index' => 'gift-cards.index',
            'create' => 'gift-cards.create',
            'store' => 'gift-cards.store',
            'edit' => 'gift-cards.edit',
            'update' => 'gift-cards.update',
            'destroy' => 'gift-cards.destroy',
        ]);
        Route::post('gift-cards/{giftCard}/status', [\App\Http\Controllers\Admin\GiftCardController::class, 'toggleStatus'])->name('gift-cards.status');

        Route::resource('campaigns', \App\Http\Controllers\Admin\CampaignController::class)->names([
            'index' => 'campaigns.index',
            'create' => 'campaigns.create',
            'store' => 'campaigns.store',
            'edit' => 'campaigns.edit',
            'update' => 'campaigns.update',
            'destroy' => 'campaigns.destroy',
        ]);

        Route::resource('loyalty-programs', \App\Http\Controllers\Admin\LoyaltyProgramController::class)->names([
            'index' => 'loyalty-programs.index',
            'create' => 'loyalty-programs.create',
            'store' => 'loyalty-programs.store',
            'edit' => 'loyalty-programs.edit',
            'update' => 'loyalty-programs.update',
            'destroy' => 'loyalty-programs.destroy',
        ]);

        Route::resource('newsletters', \App\Http\Controllers\Admin\NewsletterController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('newsletters/{newsletter}/send', [\App\Http\Controllers\Admin\NewsletterController::class, 'send'])->name('newsletters.send');

        Route::resource('push-notifications', \App\Http\Controllers\Admin\PushNotificationController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('push-notifications/{notification}/send', [\App\Http\Controllers\Admin\PushNotificationController::class, 'send'])->name('push-notifications.send');
    });

    Route::prefix('reports')->name('reports.')->controller(ReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('daily', 'dailyReport')->name('daily');
        Route::get('monthly', 'monthlyReport')->name('monthly');
        Route::get('occupancy', 'occupancy')->name('occupancy');
        Route::get('revenue', 'revenue')->name('revenue');
        Route::get('bookings', 'bookings')->name('bookings');
        Route::get('guests', 'guests')->name('guests');
        Route::get('housekeeping', 'housekeeping')->name('housekeeping');
        Route::get('inventory', 'inventory')->name('inventory');
        Route::get('restaurant', 'restaurant')->name('restaurant');
        Route::get('export/{type}', 'export')->name('export');
    });

    Route::prefix('reports/finance')->name('reports.finance.')->controller(ReportFinanceController::class)->group(function () {
        Route::get('/', 'index')->name('index');
    });

    Route::prefix('analytics')->name('analytics.')->controller(AnalyticsController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('revenue', 'revenue')->name('revenue');
        Route::get('occupancy', 'occupancy')->name('occupancy');
        Route::get('bookings', 'bookings')->name('bookings');
        Route::get('guests', 'guests')->name('guests');
    });

    Route::prefix('settings')->name('settings.')->controller(SettingController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('general', 'updateGeneral')->name('update-general');
        Route::post('company', 'updateCompany')->name('update-company');
        Route::post('hotel', 'updateHotel')->name('update-hotel');
        Route::post('email', 'updateEmail')->name('update-email');
        Route::post('sms', 'updateSms')->name('update-sms');
        Route::post('whatsapp', 'updateWhatsapp')->name('update-whatsapp');
        Route::post('payment', 'updatePayment')->name('update-payment');
        Route::post('tax', 'updateTax')->name('update-tax');
        Route::post('currency', 'updateCurrency')->name('update-currency');
        Route::post('invoice', 'updateInvoice')->name('update-invoice');
        Route::post('theme', 'updateTheme')->name('update-theme');
        Route::post('security', 'updateSecurity')->name('update-security');
        Route::post('localization', 'updateLocalization')->name('update-localization');
        Route::post('maintenance', 'toggleMaintenance')->name('maintenance');
    });

    Route::prefix('security')->name('security.')->controller(SecurityController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('roles', 'roles')->name('roles');
        Route::post('roles', 'storeRole');
        Route::put('roles/{role}', 'updateRole')->name('roles.update');
        Route::delete('roles/{role}', 'destroyRole')->name('roles.destroy');
        Route::get('permissions', 'permissions')->name('permissions');
        Route::post('permissions', 'storePermission');
        Route::get('users/{user}/permissions', 'userPermissions')->name('users.permissions');
        Route::post('users/{user}/permissions', 'updateUserPermissions');
        Route::get('users/{user}/roles', 'userRoles')->name('users.roles');
        Route::post('users/{user}/roles', 'updateUserRoles');
    });

    Route::prefix('audit')->name('audit.')->controller(\App\Http\Controllers\Admin\AuditController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('activity', 'activity')->name('activity');
        Route::get('login-history', 'loginHistory')->name('login-history');
        Route::get('exports', 'exports')->name('exports');
    });

    Route::prefix('notifications')->name('notifications.')->controller(\App\Http\Controllers\Admin\NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{id}/read', 'markAsRead')->name('read');
        Route::post('read-all', 'markAllAsRead')->name('read-all');
    });

    Route::prefix('export')->name('export.')->controller(\App\Http\Controllers\Admin\ExportController::class)->group(function () {
        Route::get('bookings', 'bookings')->name('bookings');
        Route::get('guests', 'guests')->name('guests');
        Route::get('inventory', 'inventory')->name('inventory');
        Route::get('finance', 'finance')->name('finance');
    });

    Route::prefix('import')->name('import.')->controller(\App\Http\Controllers\Admin\ImportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('guests', 'guests')->name('guests');
        Route::post('inventory', 'inventory')->name('inventory');
        Route::post('employees', 'employees')->name('employees');
    });

    Route::prefix('backup')->name('backup.')->controller(\App\Http\Controllers\Admin\BackupController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('create', 'create')->name('create');
        Route::get('download/{backup}', 'download')->name('download');
        Route::delete('{backup}', 'destroy')->name('destroy');
        Route::post('restore/{backup}', 'restore')->name('restore');
    });
});
