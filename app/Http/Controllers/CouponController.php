<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function create()
    {
        return view('coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:coupons|max:10',
            'discount' => 'required|numeric|min:1|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'required|date|after:valid_from',
        ]);

        Coupon::create($request->all());

        return redirect()->route('coupons.create')->with('success', 'Cupom criado com sucesso!');
    }

    // Listar cupons ativos
    public function index()
    {
        $coupons = Coupon::where('valid_until', '>=', now())->get(); // Apenas cupons ativos
        return view('coupons.index', compact('coupons'));
    }

    // Excluir cupom
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Cupom removido com sucesso!');
    }
}
