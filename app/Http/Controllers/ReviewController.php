<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review = Review::create([
            'order_id' => $request->order_id,
            'user_id' => $request->user()->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return response()->json($review, 201);
    }
}
