<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Currency;
use App\Models\DollarRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $currencies = Currency::all();
        $paymentMethods = PaymentMethod::with('currency')->get();

        // Obtener el último valor de la tasa del dólar
        $dollarRate = DollarRate::latest('created_at')->first();

        // Agrupar métodos de pago por moneda
        $groupedPaymentMethods = $paymentMethods->groupBy(function ($paymentMethod) {
            return $paymentMethod->currency->name; // Agrupar por el nombre de la moneda
        });

        return view('paymentMethods', compact('currencies', 'groupedPaymentMethods', 'dollarRate'));
    }
    // Crear un nuevo método de pago
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $paymentMethod = PaymentMethod::create([
            'name' => $request->name,
            'currency_id' => $request->currency,
        ]);

        return response()->json(['message' => 'Método de pago creado exitosamente', 'data' => $paymentMethod], 201);
    }

    public function currencyCreate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $currency = Currency::create([
            'name' => $request->name,
            'code' => $request->code,
        ]);

        return response()->json(['message' => 'Método de pago creado exitosamente', 'data' => $currency], 201);
    }
    // Editar un método de pago
    public function edit($id, Request $request)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|boolean',  // Activo o inactivo
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $paymentMethod->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Método de pago actualizado exitosamente', 'data' => $paymentMethod], 200);
    }

    public function toggleStatus($id, Request $request)
    {
        // Validar el parámetro de estado (status)
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',  // true para activar, false para inactivar
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Buscar el método de pago
        $paymentMethod = PaymentMethod::findOrFail($id);
    
        // Actualizar el estado
        $paymentMethod->status = $request->status;
        $paymentMethod->save();
    
        // Responder con un mensaje de éxito
        $message = $request->status ? 'Método de pago activado exitosamente' : 'Método de pago inactivado exitosamente';
        
        return response()->json(['message' => $message], 200);
    }

    public function currencyToggleStatus($id, Request $request)
    {
        // Validar el parámetro de estado (status)
        $validator = Validator::make($request->all(), [
            'status' => 'required|boolean',  // true para activar, false para inactivar
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Buscar el método de pago
        $currency = Currency::findOrFail($id);
    
        // Actualizar el estado
        $currency->status = $request->status;
        $currency->save();
    
        // Responder con un mensaje de éxito
        $message = $request->status ? 'Método de pago activado exitosamente' : 'Método de pago inactivado exitosamente';
        
        return response()->json(['message' => $message], 200);
    }
    // Crear o editar una moneda
    public function updateCurrency(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'currency_code' => 'required|string|max:3',
            'currency_name' => 'required|string|max:255',
            'symbol' => 'required|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $currency = Currency::updateOrCreate(
            ['currency_code' => $request->currency_code],
            [
                'currency_name' => $request->currency_name,
                'symbol' => $request->symbol,
            ]
        );

        return response()->json(['message' => 'Moneda actualizada o creada exitosamente', 'data' => $currency], 200);
    }

    // Actualizar la tasa del dólar
    public function updateDollarRate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // Suponiendo que tienes un modelo 'DollarRate'
        $rate = DollarRate::first(); // Obtener el primer registro
        if (!$rate) {
            $rate = new DollarRate(); // Crear una nueva instancia si no existe
        }
    
        $rate->rate = $request->rate;
        $rate->date = Carbon::now()->format('Y-m-d'); // Asignar la fecha actual
        $rate->save();
    
        return response()->json(['message' => 'Tasa del dólar actualizada exitosamente', 'data' => $rate], 201);
    }
}
