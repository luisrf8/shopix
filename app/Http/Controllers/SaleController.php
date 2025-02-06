<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalesOrder;
use App\Models\SalesOrderDetail;
use App\Models\ProductVariant;
use App\Models\PaymentMethod;
use App\Models\Payment;
use App\Models\Currency;
use App\Models\Category;
use App\Models\Product;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use App\Mail\OrderPdfMail;  // Importa la clase correctamente
use Illuminate\Support\Facades\Mail;

class SaleController extends Controller
{
    public function index()
    {
        // Traer todos los productos con sus variantes
        $productItems = Product::with('variants')->get();
    
        // Traer métodos de pago con sus monedas
        $paymentMethods = PaymentMethod::with('currency')->get();
    
        // Traer todas las categorías
        $categories = Category::all();
    
        return view('sales', compact('categories', 'productItems', 'paymentMethods'));
    }
    
    public function store(Request $request)
    {
        // Validar los datos
        $itemsSelected = $request->itemsSelected;
        $paymentDetails = $request->paymentDetails;
        $totalAmount = $request->totalAmount;
        $customerId = $request->customer_id;
    
        // Validación de productos
        if (empty($itemsSelected) || !is_array($itemsSelected)) {
            return response()->json(['error' => 'No se enviaron productos válidos.'], 400);
        }
    
        // Validación de pagos
        if (empty($paymentDetails) || !is_array($paymentDetails)) {
            return response()->json(['error' => 'No se enviaron detalles de pago válidos.'], 400);
        }
    
        // Agrupar y procesar datos de productos
        $groupedData = [];
        foreach ($itemsSelected as $item) {
            // Comprobar que 'variant' sea un objeto o un array de objetos
                // Si 'variant' no es un array, es un objeto, lo tratamos de otra manera
                $variant = $item['variant'];
                $groupedData[] = [
                    'product_variant_id' => $variant['id'],  // Usamos '->' para acceder a las propiedades del objeto
                    'quantity' => $item['quantity'],
                    'price' => $variant['price'],
                    'amount' => $variant['price'] * $item['quantity'],
                ];
        }
        // Crear orden de venta
        $salesOrder = SalesOrder::create([
            'user_id' => $customerId,
            'date' => now()->toDateString(),
            'status' => 1, // Aprobado
            'address' => 'Tienda',
            'preference' => 'Tienda',
        ]);
    
        // Crear detalles de la venta y actualizar stock
        foreach ($groupedData as $detail) {
            SalesOrderDetail::create([
                'sales_order_id' => $salesOrder->id,
                'product_variant_id' => $detail['product_variant_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'amount' => $detail['amount'],
            ]);
    
            // Actualizar el stock
            $productVariant = ProductVariant::find($detail['product_variant_id']);
            if ($productVariant && $productVariant->stock >= $detail['quantity']) {
                $productVariant->stock -= $detail['quantity'];
                $productVariant->save();
            } else {
                return response()->json(['error' => 'Stock insuficiente para el producto: ' . $productVariant->id], 400);
            }
        }
    
        // Crear pagos
        foreach ($paymentDetails as $paymentDetail) {
            $payment = Payment::create([
                'sales_order_id' => $salesOrder->id,
                'payment_method' => $paymentDetail['id'],
                'amount' => $paymentDetail['amount'],
                'currency' => $paymentDetail['currency'],
            ]);
    
            // Subir imagen (comprobante de pago)
            if (isset($paymentDetail['image']) && $paymentDetail['image']) {
                $imagePath = $paymentDetail['image']->store('payment_images', 'public'); // Suponiendo que la imagen es enviada en la solicitud
    
                // Crear registro de imagen del pago
                PaymentImage::create([
                    'payment_id' => $payment->id,
                    'image_path' => $imagePath,
                ]);
            }
        }
    
        return response()->json(['message' => 'Venta registrada exitosamente.'], 200);
    }
    public function storeEcommerceSale(Request $request)
    {
        // Validar los datos recibidos
        $itemsSelected = json_decode($request->itemsSelected, true); // Convertir JSON a arreglo
        $paymentDetails = $request->paymentDetails;
        $totalAmount = $request->totalAmount;
        $customerId = $request->customer_id;
        $preference = $request->preference;
        $address = $request->direccion;
    
        // Validación de productos
        if (empty($itemsSelected) || !is_array($itemsSelected)) {
            return response()->json(['error' => 'No se enviaron productos válidos.'], 400);
        }
    
        // Validación de pagos
        if (empty($paymentDetails) || !is_array($paymentDetails)) {
            return response()->json(['error' => 'No se enviaron detalles de pago válidos.'], 400);
        }
    
        // Agrupar y procesar datos de productos
        $groupedData = [];
        foreach ($itemsSelected as $item) {
            $variant = $item['item']; // Accedemos a la información del producto
            $groupedData[] = [
                'product_variant_id' => $variant['id'],
                'quantity' => $item['quantity'],
                'price' => $variant['price'],
                'amount' => $variant['price'] * $item['quantity'],
            ];
        }
    
        // Crear orden de venta con status en 0 (pendiente)
        $salesOrder = SalesOrder::create([
            'user_id' => $customerId,
            'date' => now()->toDateString(),
            'status' => 0, // Pendiente por defecto en eCommerce
            'address' => $address ?? 'Tienda',
            'preference' => $preference,
        ]);
    
        // Crear detalles de la venta y actualizar stock
        foreach ($groupedData as $detail) {
            SalesOrderDetail::create([
                'sales_order_id' => $salesOrder->id,
                'product_variant_id' => $detail['product_variant_id'],
                'quantity' => $detail['quantity'],
                'price' => $detail['price'],
                'amount' => $detail['amount'],
            ]);
    
            // Actualizar el stock
            $productVariant = ProductVariant::find($detail['product_variant_id']);
            if ($productVariant && $productVariant->stock >= $detail['quantity']) {
                $productVariant->stock -= $detail['quantity'];
                $productVariant->save();
            } else {
                return response()->json(['error' => 'Stock insuficiente para el producto: ' . $productVariant->id], 400);
            }
        }
    
        // Crear pagos
        foreach ($paymentDetails as $paymentDetail) {
            $paymentDetailMethod = json_decode($paymentDetail['method'], true);// Convertir JSON a arreglo
            $currencyCode = Currency::where('name', $paymentDetail['currency'])->value('code');
            $payment = Payment::create([
                'sales_order_id' => $salesOrder->id,
                'payment_method' => $paymentDetailMethod['id'], // Se usa referencia en eCommerce
                'amount' => $paymentDetail['amount'],
                'reference' => $paymentDetail['reference'],
                'currency' => $currencyCode, // Se usa el código de la moneda
                'payment_date' => $paymentDetail['paymentDate'],
            ]);
    
            // Subir imagen (comprobante de pago) si existe
            if ($request->hasFile('paymentDetails.*.img')) {
                // Iterar sobre el arreglo de detalles de pago
                foreach ($paymentDetails as $key => $paymentDetail) {
                    // Comprobar si existe un archivo 'img'
                    if ($request->hasFile("paymentDetails.$key.img")) {
                        $image = $request->file("paymentDetails.$key.img");
                        $path = $image->store('payment_images', 'public');
                        
                        // Guardar la ruta de la imagen asociada al pago
                        PaymentImage::create([
                            'payment_id' => $payment->id,
                            'image_path' => $path,
                        ]);
                    }
                }
            }
        }
    
        return response()->json(['message' => 'Venta en eCommerce registrada exitosamente.'], 200);
    }
    

    public function viewOrders()
    {
        $salesOrders = SalesOrder::with([
            'user', 
            'details', 
            'details.variant', 
            'payments' // Agregamos la relación de pagos
        ])->orderBy('date', 'desc')->get();
    
        foreach ($salesOrders as $order) {
            $order->total_items = $order->details->sum('quantity');
        }
    
        return view('salesOrders', compact('salesOrders'));
    }

    public function showByOrder($id)
    {
        $order = SalesOrder::with(['user', 'details', 'details.variant','details.variant.product', 'payments', 'payments.payment'])->find($id);
        // Calcular el total de la orden
        $totalOrden = $order->details->sum(function ($detalle) {
            return $detalle->amount;
        });
        // Calcular el total pagado
        $totalPagado = $order->payments->sum(function ($payment) {
            return $payment->amount;
        });

        return view('salesOrderDetail', compact('order', 'totalOrden', 'totalPagado'));
    }

    public function getPaymentMethods()
    {
        $paymentMethods = PaymentMethod::with('currency')->get();
        // Agrupar métodos de pago por moneda
        $groupedPaymentMethods = $paymentMethods->groupBy(function ($paymentMethod) {
            return $paymentMethod->currency->name; // Agrupar por el nombre de la moneda
        });
        
        return response()->json($paymentMethods, 200);
    }
    public function getPaymentMethodsEcomm()
    {
        // Obtener los métodos de pago que están activos (por ejemplo, con status = 1)
        $paymentMethods = PaymentMethod::with('currency')
            ->where('status', 1) // Filtrar por métodos de pago activos
            ->get();
    
        // Filtrar los métodos de pago para excluir "Efectivo" y "Punto de Venta"
        $filteredPaymentMethods = $paymentMethods->filter(function ($paymentMethod) {
            return !in_array($paymentMethod->name, ['Efectivo', 'Punto de Venta']);
        });
    
        // Agrupar los métodos de pago filtrados por la moneda
        $groupedPaymentMethods = $filteredPaymentMethods->groupBy(function ($paymentMethod) {
            return $paymentMethod->currency->name; // Agrupar por el nombre de la moneda
        });
    
        // Convertir la colección agrupada a un array
        $formattedPaymentMethods = $groupedPaymentMethods->mapWithKeys(function ($group, $key) {
            return [$key => $group]; // Asignar la moneda como clave y los métodos de pago como valor
        });
    
        // Devolver la respuesta JSON con los métodos de pago agrupados por moneda
        return response()->json($formattedPaymentMethods, 200);
    }

    public function getVariants(Request $request)
    {
        $itemIds = $request->input('item_ids');
        
        // Validar que se reciban IDs válidos
        if (empty($itemIds) || !is_array($itemIds)) {
            return response()->json(['error' => 'No se enviaron productos válidos.'], 400);
        }
        
        // Obtener variantes y productos, agrupando las variantes por producto
        $variants = ProductVariant::with('product')  // Cargar la relación con el producto
            ->whereIn('product_id', $itemIds)
            ->get();
    
        $groupedVariants = $variants->groupBy('product_id')->map(function ($group, $productId) {
            // Obtener la información del producto
            $product = $group->first()->product;  // Asumiendo que todas las variantes son del mismo producto
    
            return [
                'product_id' => $productId,
                'product_name' => $product->name,  // Obtener el nombre del producto
                'product_description' => $product->description,  // Obtener la descripción del producto (ajustar según tu modelo)
                'variants' => $group->map(function ($variant) {
                    return [
                        'id' => $variant->id,
                        'type' => $variant->type,
                        'size' => $variant->size,
                        'price' => $variant->price,
                        'stock' => $variant->stock,
                    ];
                }),
            ];
        })->values();
        
        // Devolver solo los datos esperados
        return response()->json($groupedVariants, 200);
    }
    public function orderToggleStatus($id, Request $request)
    {
        $order = SalesOrder::with([
            'user', 
            'details', 
            'details.variant.product', 
            'payments.payment'
        ])->findOrFail($id);
        
        // Actualizar el estado de la orden
        $order->status = $request->status;
        $order->save();
    
        // Calcular totales
        $totalOrden = $order->details->sum('amount');
        $totalPagado = $order->payments->sum('amount');
    
        // Generar el HTML para el PDF
        $pdfContent = view('orderPdf', compact('order', 'totalOrden', 'totalPagado'))->render();
    
        // Configuración de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($pdfContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        // Guardar el PDF en storage/app/public/orders/
        $fileName = 'orden-' . $order->id . '.pdf';
        Storage::disk('public')->put('orders/' . $fileName, $dompdf->output());
        $filePath = storage_path('app/public/orders/' . $fileName);

        // URL accesible del PDF
        $pdfUrl = asset('storage/orders/' . $fileName);
        Mail::to($order->user->email)->send(new OrderPdfMail($order, $filePath));

        return response()->json([
            'success' => true,
            'message' => 'Orden actualizada y PDF generado.',
            'pdf_url' => $pdfUrl
        ]);
    }
    
    public function paymentToggleStatus($id, Request $request)
    {
        // Buscar la categoría
        $payment = Payment::findOrFail($id);
    
        // Cambiar el estado de la categoría
        $payment->status = $request->status;
        $payment->save();
    
        return response()->json([
            'status' => 'success',
            'new_status' => $payment->status
        ], 200);
    }
}
