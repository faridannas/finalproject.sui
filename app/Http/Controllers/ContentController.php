<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contents = Content::paginate(10);
        return view('admin.contents.index', compact('contents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.contents.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('contents', 'public');
        }

        Content::create($data);

        return redirect()->route('admin.contents.index')->with('success', 'Content created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $content = Content::findOrFail($id);
        return view('contents.show', compact('content'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $content = Content::findOrFail($id);
        return view('admin.contents.edit', compact('content'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $content = Content::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($content->image) {
                Storage::disk('public')->delete($content->image);
            }
            $data['image'] = $request->file('image')->store('contents', 'public');
        }

        $content->update($data);

        return redirect()->route('admin.contents.index')->with('success', 'Content updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $content = Content::findOrFail($id);

        if ($content->image) {
            Storage::disk('public')->delete($content->image);
        }

        $content->delete();

        return redirect()->route('admin.contents.index')->with('success', 'Content deleted successfully.');
    }
}
