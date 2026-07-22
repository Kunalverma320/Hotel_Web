<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\CmsPage;
use App\Models\Blog;
use App\Models\Testimonial;
use App\Models\Faq;
use App\Models\GalleryItem;

class CmsController extends Controller
{
    public function pages()
    {
        $pages = CmsPage::latest()->paginate(15);

        return view('admin.cms.pages', compact('pages'));
    }

    public function pageCreate()
    {
        return view('admin.cms.page-form');
    }

    public function pageStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft',
        ]);

        CmsPage::create($request->only('title', 'slug', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'status'));

        return redirect()->route('admin.cms.pages')->with('success', 'Page created successfully.');
    }

    public function pageEdit($id)
    {
        $page = CmsPage::findOrFail($id);

        return view('admin.cms.page-form', compact('page'));
    }

    public function pageUpdate(Request $request, $id)
    {
        $page = CmsPage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug,' . $page->id,
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:published,draft',
        ]);

        $page->update($request->only('title', 'slug', 'content', 'meta_title', 'meta_description', 'meta_keywords', 'status'));

        return redirect()->route('admin.cms.pages')->with('success', 'Page updated successfully.');
    }

    public function pageDestroy($id)
    {
        CmsPage::findOrFail($id)->delete();

        return redirect()->route('admin.cms.pages')->with('success', 'Page deleted successfully.');
    }

    public function pagePreview($slug)
    {
        $page = CmsPage::where('slug', $slug)->findOrFail($slug);

        return view('admin.cms.page-preview', compact('page'));
    }

    public function blogs()
    {
        $blogs = Blog::latest()->paginate(15);

        return view('admin.cms.blogs', compact('blogs'));
    }

    public function blogCreate()
    {
        return view('admin.cms.blog-form');
    }

    public function blogStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft',
        ]);

        $data = $request->only('title', 'slug', 'content', 'excerpt', 'category', 'tags', 'meta_title', 'meta_description', 'status');

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $data['author_id'] = auth()->id();

        Blog::create($data);

        return redirect()->route('admin.cms.blogs')->with('success', 'Blog post created successfully.');
    }

    public function blogEdit($id)
    {
        $blog = Blog::findOrFail($id);

        return view('admin.cms.blog-form', compact('blog'));
    }

    public function blogUpdate(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:1000',
            'featured_image' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:published,draft',
        ]);

        $data = $request->only('title', 'slug', 'content', 'excerpt', 'category', 'tags', 'meta_title', 'meta_description', 'status');

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.cms.blogs')->with('success', 'Blog post updated successfully.');
    }

    public function blogDestroy($id)
    {
        $blog = Blog::findOrFail($id);
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();

        return redirect()->route('admin.cms.blogs')->with('success', 'Blog post deleted successfully.');
    }

    public function testimonials()
    {
        $testimonials = Testimonial::latest()->paginate(15);

        return view('admin.cms.testimonials', compact('testimonials'));
    }

    public function testimonialCreate()
    {
        return view('admin.cms.testimonial-form');
    }

    public function testimonialStore(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:active,inactive',
        ]);

        Testimonial::create($request->only('customer_name', 'customer_email', 'content', 'rating', 'status'));

        return redirect()->route('admin.cms.testimonials')->with('success', 'Testimonial created successfully.');
    }

    public function testimonialUpdate(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'content' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|in:active,inactive',
        ]);

        $testimonial->update($request->only('customer_name', 'customer_email', 'content', 'rating', 'status'));

        return redirect()->route('admin.cms.testimonials')->with('success', 'Testimonial updated successfully.');
    }

    public function testimonialDestroy($id)
    {
        Testimonial::findOrFail($id)->delete();

        return redirect()->route('admin.cms.testimonials')->with('success', 'Testimonial deleted successfully.');
    }

    public function faqs()
    {
        $faqs = Faq::orderBy('sort_order')->paginate(15);

        return view('admin.cms.faqs', compact('faqs'));
    }

    public function faqCreate()
    {
        return view('admin.cms.faq-form');
    }

    public function faqStore(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $maxOrder = Faq::max('sort_order') ?? 0;
        $data = $request->only('question', 'answer', 'status');
        $data['sort_order'] = $request->sort_order ?? ($maxOrder + 1);

        Faq::create($data);

        return redirect()->route('admin.cms.faqs')->with('success', 'FAQ created successfully.');
    }

    public function faqEdit($id)
    {
        $faq = Faq::findOrFail($id);

        return view('admin.cms.faq-form', compact('faq'));
    }

    public function faqUpdate(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $faq->update($request->only('question', 'answer', 'sort_order', 'status'));

        return redirect()->route('admin.cms.faqs')->with('success', 'FAQ updated successfully.');
    }

    public function faqDestroy($id)
    {
        Faq::findOrFail($id)->delete();

        return redirect()->route('admin.cms.faqs')->with('success', 'FAQ deleted successfully.');
    }

    public function gallery()
    {
        $items = GalleryItem::orderBy('sort_order')->paginate(24);

        return view('admin.cms.gallery', compact('items'));
    }

    public function galleryUpload(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:20',
            'images.*' => 'image|max:5120',
        ]);

        foreach ($request->file('images') as $file) {
            $path = $file->store('gallery', 'public');
            $maxOrder = GalleryItem::max('sort_order') ?? 0;

            GalleryItem::create([
                'image' => $path,
                'caption' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'sort_order' => $maxOrder + 1,
            ]);
        }

        return back()->with('success', 'Images uploaded successfully.');
    }

    public function galleryDelete($id)
    {
        $item = GalleryItem::findOrFail($id);
        Storage::disk('public')->delete($item->image);
        $item->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}
