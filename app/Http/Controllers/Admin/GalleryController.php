<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(20);
        return view('admin.cms.gallery', compact('galleries'));
    }

    public function create()
    {
        return view('admin.cms.gallery');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'category' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('gallery', 'public');
        }
        $validated['status'] = true;
        Gallery::create($validated);
        return redirect()->route('admin.cms.gallery.index')->with('success', 'Image uploaded.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && \Storage::disk('public')->exists($gallery->image)) {
            \Storage::disk('public')->delete($gallery->image);
        }
        $gallery->delete();
        return redirect()->route('admin.cms.gallery.index')->with('success', 'Image deleted.');
    }
}
