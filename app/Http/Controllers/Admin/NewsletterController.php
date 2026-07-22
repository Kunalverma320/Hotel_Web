<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $newsletters = Newsletter::latest()->paginate(20);
        return view('admin.marketing.newsletters', compact('newsletters'));
    }

    public function create()
    {
        return view('admin.marketing.newsletter-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);
        $validated['status'] = 'draft';
        Newsletter::create($validated);
        return redirect()->route('admin.marketing.newsletters.index')->with('success', 'Newsletter created.');
    }

    public function show(Newsletter $newsletter)
    {
        return view('admin.marketing.newsletter-form', ['newsletter' => $newsletter]);
    }

    public function send(Newsletter $newsletter)
    {
        $newsletter->update(['status' => 'sent', 'sent_at' => now()]);
        return back()->with('success', 'Newsletter sent.');
    }
}
