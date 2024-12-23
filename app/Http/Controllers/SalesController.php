<?php

namespace App\Http\Controllers;

use App\DataTables\SalesDataTable;
use App\Models\Inventory;
use App\Models\Sales;
use App\Models\SalesDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SalesDataTable $dataTable)
    {
        //
        return $dataTable->render('sales.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $inventories = Inventory::all();

        return view('sales.create', ['inventories' => $inventories]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|digits_between:1,10',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $inventory = Inventory::find($request->inventory_id);

        if (!$inventory) {
            return response()->json(["errors" => "Inventory not found"], 404);
        }

        $sales = new Sales();
        $sales->date = $request->date;
        $sales->number = $sales->newUniqueId();
        // $sales->user_id = Auth::user()->id;
        $sales->user()->associate(Auth::user());
        $sales->save();

        $sales_detail = new SalesDetail;
        $sales_detail->qty = $request->quantity;

        $sales_detail->inventory()->associate($inventory);
        $sales_detail->sales()->associate($sales);
        $sales_detail->save();

        return response()->json(['message' => 'Create Sales successfully!']);
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
    public function edit($id)
    {
        //
        $sales = Sales::with('details.inventory')->where('id', '=', $id)->firstOrFail();
        $inventories = Inventory::all();

        return view('sales.edit', ['sales' => $sales, 'inventories' => $inventories]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|digits_between:1,10',
            'date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors" => $validator->errors()], 422);
        }

        $inventory = Inventory::find($request->inventory_id);

        if (!$inventory) {
            return response()->json(["errors" => "Inventory not found"], 404);
        }

        $sales = Sales::find($id);
        $sales_detail = SalesDetail::find($sales->details->id);
        if (!$sales) {
            return response()->json(["errors" => "Sales not found"], 404);
        }

        if (!$sales_detail) {
            return response()->json(["errors" => "Sales Detail not found"], 404);
        }
        
        $sales->date = $request->date;
        $sales->save();

        $sales_detail->qty = $request->quantity;

        $sales_detail->inventory()->associate($inventory);
        $sales_detail->save();

        return response()->json(['message' => 'Create Sales successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
        $sales = Sales::find($id);

        if (!$sales) {
            return response()->json(["errors" => 'Sales not found'], 404);
        }

        $sales->delete();

        return response()->json(['message' => 'Delete sales successfully!']);
    }
}
