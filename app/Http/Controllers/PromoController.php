<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promos = Promo::paginate(10);
        return view('admin.promos.index', compact('promos'));
    }

    /**
     * Display active promos for public users
     */
    public function publicIndex()
    {
        $promos = Promo::where('valid_until', '>=', now())
                       ->orderBy('created_at', 'desc')
                       ->get();
        return view('promos.index', compact('promos'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:promos',
            'discount' => 'required|integer|min:0|max:100',
            'valid_until' => 'required|date|after:today',
        ]);

        Promo::create($request->all());

        return redirect()->route('admin.promos.index')->with('success', 'Promo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $promo = Promo::findOrFail($id);
        return view('admin.promos.edit', compact('promo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $promo = Promo::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:promos,code,' . $id,
            'discount' => 'required|integer|min:0|max:100',
            'valid_until' => 'required|date',
        ]);

        $promo->update($request->all());

        return redirect()->route('admin.promos.index')->with('success', 'Promo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $promo = Promo::findOrFail($id);
        $promo->delete();

        return redirect()->route('admin.promos.index')->with('success', 'Promo deleted successfully.');
    }
}
