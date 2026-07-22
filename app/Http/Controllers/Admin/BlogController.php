<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('author')->latest()->paginate(20);
        return view('admin.cms.blogs', compact('blogs'));
    }

    public function create()
    {
        return view('admin.cms.blog-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:255',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;
        Blog::create($validated);
        return redirect()->route('admin.cms.blogs.index')->with('success', 'Blog post created.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.cms.blog-form', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:255',
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? ($blog->published_at ?? now()) : null;
        $blog->update($validated);
        return redirect()->route('admin.cms.blogs.index')->with('success', 'Blog updated.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('admin.cms.blogs.index')->with('success', 'Blog deleted.');
    }
}
