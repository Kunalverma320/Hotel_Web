<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Document;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::latest();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $documents = $query->paginate(24);
        $types = ['image', 'document', 'video', 'audio', 'archive', 'other'];

        return view('admin.documents.index', compact('documents', 'types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'type' => 'nullable|string|max:50',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());

        $typeMap = [
            'jpg' => 'image', 'jpeg' => 'image', 'png' => 'image', 'gif' => 'image', 'svg' => 'image', 'webp' => 'image',
            'pdf' => 'document', 'doc' => 'document', 'docx' => 'document', 'txt' => 'document', 'xls' => 'document', 'xlsx' => 'document', 'ppt' => 'document', 'pptx' => 'document', 'csv' => 'document',
            'mp4' => 'video', 'avi' => 'video', 'mov' => 'video', 'wmv' => 'video', 'mkv' => 'video',
            'mp3' => 'audio', 'wav' => 'audio', 'ogg' => 'audio', 'flac' => 'audio',
            'zip' => 'archive', 'rar' => 'archive', '7z' => 'archive', 'tar' => 'archive', 'gz' => 'archive',
        ];

        $type = $request->type ?? ($typeMap[$extension] ?? 'other');
        $path = $file->store('documents/' . $type, 'public');

        Document::create([
            'name' => $file->getClientOriginalName(),
            'slug' => Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => $type,
            'uploaded_by' => auth()->id(),
        ]);

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded successfully.');
    }

    public function destroy($id)
    {
        $document = Document::findOrFail($id);
        Storage::disk($document->disk)->delete($document->path);
        $document->delete();

        return redirect()->route('admin.documents.index')->with('success', 'Document deleted successfully.');
    }

    public function download($id)
    {
        $document = Document::findOrFail($id);

        return Storage::disk($document->disk)->download($document->path, $document->name);
    }

    public function preview($id)
    {
        $document = Document::findOrFail($id);

        return Storage::disk($document->disk)->response($document->path, $document->name, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => 'inline; filename="' . $document->name . '"',
        ]);
    }
}
