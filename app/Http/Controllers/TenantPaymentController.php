<?php

namespace App\Http\Controllers;

use App\Models\TenantPayment;
use Illuminate\Http\Request;

class TenantPaymentController extends Controller
{
    public function index()
    {
        return TenantPayment::with(['tenant', 'plan'])->get();
    }

    public function store(Request $request)
    {
        $payment = TenantPayment::create($request->all());
        return response()->json($payment, 201);
    }

    public function show(TenantPayment $payment)
    {
        return $payment->load(['tenant', 'plan']);
    }

    public function update(Request $request, TenantPayment $payment)
    {
        $payment->update($request->all());
        return response()->json($payment);
    }

    public function destroy(TenantPayment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }
}
