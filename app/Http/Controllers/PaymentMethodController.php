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
            'currency' => 'required|exists:currencies,id',
            'admin_name' => 'nullable|string',
            'dni' => 'nullable|string',
            'bank' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $paymentMethod = PaymentMethod::create([
            'name' => $request->name,
            'currency_id' => $request->currency,
            'admin_name' => $request->admin_name,
            'dni' => $request->dni,
            'bank' => $request->bank,
            'image' => $request->image,
        ]);
        if ($request->hasFile('image')) {
            // Guardar la imagen en la carpeta `qr_images/` en el almacenamiento público
            $path = $request->file('image')->store('qr_images', 'public');

            // Convertir la ruta al formato requerido
            $formattedPath = json_encode([$path]);

            // Guardar la ruta en el campo correspondiente
            $paymentMethod->qr_image = $formattedPath;
            $paymentMethod->save();
        }

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
    public function edit(Request $request, $id)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'name' => 'required|string',
            'currency' => 'required|exists:currencies,id',
            'admin_name' => 'nullable|string',
            'dni' => 'nullable|string',
            'bank' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Buscar el método de pago
        $paymentMethod = PaymentMethod::findOrFail($id);
        $paymentMethod->update($validated);

        // Procesar la imagen QR si se envía
        if ($request->hasFile('image')) {
            // Guardar la imagen en la carpeta `qr_images/` en el almacenamiento público
            $path = $request->file('image')->store('qr_images', 'public');

            // Convertir la ruta al formato requerido
            $formattedPath = json_encode([$path]);

            // Guardar la ruta en el campo correspondiente
            $paymentMethod->qr_image = $formattedPath;
            $paymentMethod->save();
        }

        // Respuesta con la ruta del QR
        return response()->json([
            'success' => true,
            'message' => 'Método de pago actualizado correctamente.',
            'qr_image' => $paymentMethod->qr_image, // Aquí se devuelve en el formato correcto
        ]);
    }

    

    public function toggleStatus($id, Request $request)
    {
        // Validar el parámetro de estado (is_active)
        // $validator = Validator::make($request->all(), [
        //     'is_active' => 'required|boolean',  // true para activar, false para inactivar
        // ]);
    
        // if ($validator->fails()) {
        //     return response()->json(['errors' => $validator->errors()], 400);
        // }
    
        // Buscar el método de pago
        $paymentMethod = PaymentMethod::findOrFail($id);
    
        // Actualizar el estado
        $paymentMethod->status = !$paymentMethod->status;
        $paymentMethod->save();
    
        // Responder con un mensaje de éxito
        $message = $request->is_active ? 'Método de pago activado exitosamente' : 'Método de pago inactivado exitosamente';
    
        return response()->json(['message' => $message], 200);
    }

    public function currencyToggleStatus($id, Request $request)
    {
        // Buscar la moneda
        $currency = Currency::findOrFail($id);
    
        // Cambiar el estado de la moneda
        $currency->status = !$currency->status;
        $currency->save();
    
        // Si la moneda se desactiva, desactivar también sus métodos de pago
        if ($currency->status == 0) {
            PaymentMethod::where('currency_id', $currency->id)->update(['status' => 0]);
        }
    
        // Responder con un mensaje de éxito
        $message = $currency->status ? 'Moneda activada exitosamente' : 'Moneda inactivada exitosamente, junto con sus métodos de pago.';
        
        return response()->json(['message' => $message], 200);
    }
    
    // Crear o editar una moneda
    public function updateCurrency(Currency $id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $id->id,
            'code' => 'required|string',
        ]);

        $id->update([
            'name' => $request->name,
            'code' => $request->code,
        ]);
        $id->refresh();

        return response()->json(['message' => 'Moneda actualizada o creada exitosamente', 'data' => $id], 200);
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
    public function getDollarRate()
    {
        $dollarRate = DollarRate::latest('created_at')->first();
        return response()->json(['message' => 'Tasa del dólar obtenida exitosamente', 'data' => $dollarRate], 201);
    }
    // Función para eliminar una imagen
    public function removeQrImage($methodId)
    {
        $paymentMethod = PaymentMethod::findOrFail($methodId);
    
        // Verificar si existe una imagen QR en el almacenamiento y eliminarla
        if ($paymentMethod->qr_image && Storage::disk('public')->exists($paymentMethod->qr_image)) {
            Storage::disk('public')->delete($paymentMethod->qr_image);
        }
    
        // Actualizar el campo `qr_image` a null
        $paymentMethod->qr_image = null;
        $paymentMethod->save();
    
        return response()->json(['success' => true, 'message' => 'QR eliminado correctamente.']);
    }

}
