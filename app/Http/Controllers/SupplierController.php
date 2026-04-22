<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('suppliers.index', ['suppliers' => Supplier::paginate(5)]);
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Supplier::create($request->validate(['name' => 'required', 'address' => 'nullable', 'phone' => 'nullable']));
        return redirect('/suppliers')->with('msg', 'Supplier added!');
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', ['supplier' => $supplier]);
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $supplier->update($request->validate(['name' => 'required', 'address' => 'nullable', 'phone' => 'nullable']));
        return redirect('/suppliers')->with('msg', 'Supplier updated!');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        $supplier->delete();
        return redirect('/suppliers')->with('msg', 'Supplier deleted!');
    }
}
