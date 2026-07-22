<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = CmsPage::latest()->paginate(20);
        return view('admin.cms.pages', compact('pages'));
    }

    public function create()
    {
        return view('admin.cms.page-form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? now() : null;
        CmsPage::create($validated);
        return redirect()->route('admin.cms.pages.index')->with('success', 'Page created.');
    }

    public function edit(CmsPage $page)
    {
        return view('admin.cms.page-form', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        $validated['published_at'] = $validated['is_published'] ? ($page->published_at ?? now()) : null;
        $page->update($validated);
        return redirect()->route('admin.cms.pages.index')->with('success', 'Page updated.');
    }

    public function destroy(CmsPage $page)
    {
        $page->delete();
        return redirect()->route('admin.cms.pages.index')->with('success', 'Page deleted.');
    }
}
