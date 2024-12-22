<?php

namespace App\Http\Controllers;

use App\DataTables\InventoriesDataTable;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(InventoriesDataTable $dataTable)
    {
        //
        return $dataTable->render('inventory.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|digits_between:1,10',
            'stock' => 'required|numeric|digits_between:1,10',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $inventory = Inventory::create([
            "code" => Str::random(12),
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
        ]);

        return response()->json(['message' => 'Create inventory successfully!']);
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
        //
        $inventory = Inventory::findOrFail($id);
        return view('inventory.edit', ['inventory' => $inventory]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|digits_between:1,10',
            'stock' => 'required|numeric|digits_between:1,10',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(["errors" => 'Inventory not found'], 404);
        }

        $inventory = $inventory->update([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
        ]);

        return response()->json(['message' => 'Update inventory successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
        $inventory = Inventory::find($id);

        if (!$inventory) {
            return response()->json(["errors" => 'Inventory not found'], 404);
        }

        $inventory->delete();

        return response()->json(['message' => 'Delete inventory successfully!']);
    }
}
