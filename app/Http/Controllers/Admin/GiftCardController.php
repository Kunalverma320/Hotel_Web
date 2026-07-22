<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GiftCard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    public function index()
    {
        $giftCards = GiftCard::where('hotel_id', session('current_hotel_id'))->latest()->paginate(20);
        return view('admin.marketing.gift-cards', compact('giftCards'));
    }

    public function create()
    {
        return view('admin.marketing.gift-card-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $validated['hotel_id'] = session('current_hotel_id');
        $validated['code'] = 'GC-' . strtoupper(Str::random(10));
        $validated['balance'] = $validated['amount'];
        $validated['status'] = 'active';
        GiftCard::create($validated);
        return redirect()->route('admin.marketing.gift-cards.index')->with('success', 'Gift card created.');
    }

    public function edit(GiftCard $giftCard)
    {
        return view('admin.marketing.gift-card-form', compact('giftCard'));
    }

    public function update(Request $request, GiftCard $giftCard)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email',
            'recipient_name' => 'required|string|max:255',
            'recipient_email' => 'required|email',
            'end_date' => 'required|date',
        ]);
        $giftCard->update($validated);
        return redirect()->route('admin.marketing.gift-cards.index')->with('success', 'Gift card updated.');
    }

    public function destroy(GiftCard $giftCard)
    {
        $giftCard->delete();
        return redirect()->route('admin.marketing.gift-cards.index')->with('success', 'Gift card deleted.');
    }

    public function toggleStatus(GiftCard $giftCard)
    {
        $statuses = ['active' => 'expired', 'expired' => 'active', 'used' => 'used'];
        $giftCard->update(['status' => $statuses[$giftCard->status] ?? 'active']);
        return back()->with('success', 'Status updated.');
    }
}
