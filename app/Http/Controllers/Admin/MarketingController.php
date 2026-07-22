<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\GiftCard;
use App\Models\Campaign;
use App\Models\LoyaltyProgram;
use App\Models\Newsletter;
use App\Models\PushNotification;
use App\Models\EmailLog;
use App\Models\SmsLog;
use App\Models\WhatsappLog;

class MarketingController extends Controller
{
    public function coupons()
    {
        $coupons = Coupon::latest()->paginate(15);

        return view('admin.marketing.coupons', compact('coupons'));
    }

    public function couponCreate()
    {
        return view('admin.marketing.coupon-form');
    }

    public function couponStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
            'status' => 'required|in:active,inactive',
        ]);

        Coupon::create($request->only('code', 'type', 'value', 'min_order', 'max_uses', 'starts_at', 'ends_at', 'status'));

        return redirect()->route('admin.marketing.coupons')->with('success', 'Coupon created successfully.');
    }

    public function couponEdit($id)
    {
        $coupon = Coupon::findOrFail($id);

        return view('admin.marketing.coupon-form', compact('coupon'));
    }

    public function couponUpdate(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:1',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after_or_equal:starts_at',
            'status' => 'required|in:active,inactive',
        ]);

        $coupon->update($request->only('code', 'type', 'value', 'min_order', 'max_uses', 'starts_at', 'ends_at', 'status'));

        return redirect()->route('admin.marketing.coupons')->with('success', 'Coupon updated successfully.');
    }

    public function couponDestroy($id)
    {
        Coupon::findOrFail($id)->delete();

        return redirect()->route('admin.marketing.coupons')->with('success', 'Coupon deleted successfully.');
    }

    public function giftCards()
    {
        $giftCards = GiftCard::latest()->paginate(15);

        return view('admin.marketing.gift-cards', compact('giftCards'));
    }

    public function giftCardCreate()
    {
        return view('admin.marketing.gift-card-form');
    }

    public function giftCardStore(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:gift_cards,code',
            'balance' => 'required|numeric|min:1',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_email' => 'nullable|email|max:255',
            'expires_at' => 'nullable|date|after:today',
        ]);

        GiftCard::create([
            'code' => strtoupper(Str::random(16)),
            'balance' => $request->balance,
            'initial_balance' => $request->balance,
            'recipient_name' => $request->recipient_name,
            'recipient_email' => $request->recipient_email,
            'expires_at' => $request->expires_at,
            'status' => 'active',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.marketing.gift-cards')->with('success', 'Gift card created successfully.');
    }

    public function campaigns()
    {
        $campaigns = Campaign::latest()->paginate(15);

        return view('admin.marketing.campaigns', compact('campaigns'));
    }

    public function campaignCreate()
    {
        return view('admin.marketing.campaign-form');
    }

    public function campaignStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:email,sms,whatsapp',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'target_audience' => 'required|string|max:255',
            'scheduled_at' => 'nullable|date',
        ]);

        Campaign::create($request->only('name', 'type', 'subject', 'message', 'target_audience', 'scheduled_at') + [
            'status' => 'draft',
        ]);

        return redirect()->route('admin.marketing.campaigns')->with('success', 'Campaign created successfully.');
    }

    public function campaignSend($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update(['status' => 'sent', 'sent_at' => now()]);

        return back()->with('success', 'Campaign sent successfully.');
    }

    public function loyaltyPrograms()
    {
        $programs = LoyaltyProgram::latest()->paginate(15);

        return view('admin.marketing.loyalty', compact('programs'));
    }

    public function loyaltyCreate()
    {
        return view('admin.marketing.loyalty-form');
    }

    public function loyaltyStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'points_per_dollar' => 'required|numeric|min:0',
            'redeem_rate' => 'required|numeric|min:0',
            'min_points_redeem' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        LoyaltyProgram::create($request->only('name', 'points_per_dollar', 'redeem_rate', 'min_points_redeem', 'description', 'status'));

        return redirect()->route('admin.marketing.loyalty')->with('success', 'Loyalty program created successfully.');
    }

    public function newsletters()
    {
        $newsletters = Newsletter::latest()->paginate(15);

        return view('admin.marketing.newsletters', compact('newsletters'));
    }

    public function newsletterCreate()
    {
        return view('admin.marketing.newsletter-form');
    }

    public function newsletterStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:published,draft',
        ]);

        Newsletter::create($request->only('title', 'subject', 'content', 'status'));

        return redirect()->route('admin.marketing.newsletters')->with('success', 'Newsletter created successfully.');
    }

    public function newsletterSend($id)
    {
        $newsletter = Newsletter::findOrFail($id);
        $newsletter->update(['status' => 'sent', 'sent_at' => now()]);

        return back()->with('success', 'Newsletter sent successfully.');
    }

    public function pushNotifications()
    {
        $notifications = PushNotification::latest()->paginate(15);

        return view('admin.marketing.push-notifications', compact('notifications'));
    }

    public function pushCreate()
    {
        return view('admin.marketing.push-form');
    }

    public function pushStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:500',
            'url' => 'nullable|url|max:500',
            'target_audience' => 'required|string|max:255',
            'status' => 'required|in:published,draft',
        ]);

        PushNotification::create($request->only('title', 'body', 'url', 'target_audience', 'status'));

        return redirect()->route('admin.marketing.push-notifications')->with('success', 'Push notification created successfully.');
    }

    public function pushSend($id)
    {
        $notification = PushNotification::findOrFail($id);
        $notification->update(['status' => 'sent', 'sent_at' => now()]);

        return back()->with('success', 'Push notification sent successfully.');
    }
}
