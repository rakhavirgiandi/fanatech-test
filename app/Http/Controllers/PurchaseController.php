<?php

namespace App\Http\Controllers;

use App\DataTables\PurchaseDataTable;
use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PurchaseDataTable $purchaseDataTable)
    {
        //
        return $purchaseDataTable->render('purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $inventories = Inventory::all();

        return view('purchase.create', ['inventories' => $inventories]);
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

        $purchase = new Purchase();
        $purchase->date = $request->date;
        $purchase->number = $purchase->newUniqueId();
        $purchase->user()->associate(Auth::user());
        $purchase->save();

        $purchase_detail = new PurchaseDetail;
        $purchase_detail->qty = $request->quantity;
        $purchase_detail->price = $inventory->price * $request->quantity;

        $purchase_detail->inventory()->associate($inventory);
        $purchase_detail->purchase()->associate($purchase);
        $purchase_detail->save();

        return response()->json(['message' => 'Create purchase successfully!']);
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
        $purchase = Purchase::with('details', 'details.inventory')->firstOrFail();
        $inventories = Inventory::all();

        return view('purchase.edit', ['purchase' => $purchase, 'inventories' => $inventories]);
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

        $sales = Purchase::find($id);
        $sales_detail = PurchaseDetail::find($sales->details->id);
        if (!$sales) {
            return response()->json(["errors" => "Sales not found"], 404);
        }

        if (!$sales_detail) {
            return response()->json(["errors" => "Sales Detail not found"], 404);
        }
        
        $sales->date = $request->date;
        $sales->save();

        $sales_detail->qty = $request->quantity;
        $sales_detail->price = $inventory->price * $request->quantity;

        $sales_detail->inventory()->associate($inventory);
        $sales_detail->save();

        return response()->json(['message' => 'Create Sales successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $purchase = Purchase::find($id);

        if (!$purchase) {
            return response()->json(["errors" => 'Purchase not found'], 404);
        }

        $purchase->delete();

        return response()->json(['message' => 'Delete purchase successfully!']);
    }
}
