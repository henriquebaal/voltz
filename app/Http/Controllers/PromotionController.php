<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::all();
        return response()->json($promotions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string|unique:promotions',
            'discount_value' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
        ]);

        $promotion = Promotion::create($request->all());
        return response()->json($promotion, 201);
    }
}
