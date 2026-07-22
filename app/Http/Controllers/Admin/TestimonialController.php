<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->paginate(20);
        return view('admin.cms.testimonials', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.cms.testimonials');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        $validated['status'] = true;
        Testimonial::create($validated);
        return redirect()->route('admin.cms.testimonials.index')->with('success', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.cms.testimonials', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);
        $testimonial->update($validated);
        return redirect()->route('admin.cms.testimonials.index')->with('success', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.cms.testimonials.index')->with('success', 'Testimonial deleted.');
    }
}
