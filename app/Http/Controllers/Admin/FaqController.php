<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::latest()->paginate(20);
        return view('admin.cms.faqs', compact('faqs'));
    }

    public function create()
    {
        return view('admin.cms.faqs');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        $validated['status'] = true;
        Faq::create($validated);
        return redirect()->route('admin.cms.faqs.index')->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        return view('admin.cms.faqs', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:255',
        ]);
        $faq->update($validated);
        return redirect()->route('admin.cms.faqs.index')->with('success', 'FAQ updated.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return redirect()->route('admin.cms.faqs.index')->with('success', 'FAQ deleted.');
    }
}
