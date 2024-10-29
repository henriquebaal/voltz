<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventory = Inventory::all();
        return response()->json($inventory);
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string',
            'quantity' => 'required|integer',
            'minimum_quantity' => 'nullable|integer',
        ]);

        $item = Inventory::create($request->all());
        return response()->json($item, 201);
    }
}
