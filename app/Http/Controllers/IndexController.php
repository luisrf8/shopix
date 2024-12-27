<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\ProductInventory;
use App\Models\Product;
use App\Models\Provider;
use App\Models\PurchaseOrder;
use Carbon\Carbon;
class IndexController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now();
    
        // Definir el rango de fechas (por ejemplo, una semana antes y después)
        $startDate = $currentDate->copy()->subWeek(); // Una semana antes
        $endDate = $currentDate->copy()->addWeek();  // Una semana después
    
        // Filtrar productos en inventario con arrival_date cercana a la fecha actual
        $productInventories = ProductInventory::with(['productVariant.product', 'warehouse'])
            ->whereBetween('arrival_date', [$startDate, $endDate])
            ->get();
    
        // Crear un arreglo con las estadísticas
        $stats = [
            [
                'name' => 'Productos',
                'count' => ProductInventory::count(),
                'link' => '/products' // Enlace al inventario de productos
            ],
            [
                'name' => 'Proveedores',
                'count' => Provider::count(),
                'link' => '/providers' // Enlace a la lista de proveedores
            ],
            [
                'name' => 'Órdenes de Compra',
                'count' => PurchaseOrder::count(),
                'link' => '/purchase-orders' // Enlace a las órdenes de compra
            ],
        ];
    
        // Retornar la vista con los datos
        return view('dashboard', compact('stats', 'productInventories'));
    }
    
    

    public function addToWarehouseindex()
    {
        $warehouses = Warehouse::all();
        $productInventories = ProductInventory::all();
        $productItems = Product::all();
        $providers = Provider::all();
        $warehouses = Warehouse::all();
        return view('productWarehouse', compact('warehouses', 'productInventories', 'productItems', 'providers', 'warehouses')); // Asegúrate de tener una vista para mostrar las almacens.
    }
    public function getWarehouses()
    {
        $warehouses = Warehouse::all();
        return response()->json($warehouses);
    }
    public function create()
    {
        return view('warehouses.create'); // Vista para crear una nueva almacen.
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses',
            'description' => 'nullable|string',
        ]);

        $warehouse = Warehouse::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // return redirect()->route('warehouses.index')->with('success', 'almacen creada con éxito.');
        return response()->json(['message' => 'Warehouse created successfully', 'Warehouse' => $warehouse], 201);

    }
    public function storeInitialInventory(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'itemsSelected' => 'required|array',
            'itemsSelected.*.product_id' => 'required|integer',
            'itemsSelected.*.quantity' => 'required|integer|min:1',
            'itemsSelected.*.variant.id' => 'required|integer',
            'itemsSelected.*.warehouse.id' => 'required|integer',
        ]);

        $itemsSelected = $request->input('itemsSelected');

        // Obtener la fecha actual
        $currentDate = Carbon::now();

        // Preparar los datos para insertar
        $dataToInsert = [];
        foreach ($itemsSelected as $item) {
            $dataToInsert[] = [
                'product_variant_id' => $item['variant']['id'],
                'warehouse_id' => $item['warehouse']['id'],
                'quantity' => $item['quantity'],
                'arrival_date' => $currentDate,
                'expiration_date' => $currentDate,
                'created_at' => $currentDate,
                'updated_at' => $currentDate,
            ];
        }

        // Insertar en la base de datos
        ProductInventory::insert($dataToInsert);

        return response()->json([
            'message' => 'Inventario inicial registrado exitosamente.',
            'data' => $dataToInsert
        ], 201);
    }
    public function updateProductInventory(Request $request, $id)
    {   
        $productInventory = ProductInventory::findOrFail($id);
    
        // Actualizar la información
        $productInventory->warehouse_id = $request->warehouse_id;
        $productInventory->arrival_date = $request->arrival_date;
        $productInventory->expiration_date = $request->expiration_date;
    
        // Guardar los cambios
        $productInventory->save();
    
        return response()->json([
            'status' => 200,
            'message' => 'Producto actualizado con éxito',
            'productInventory' => $productInventory,
        ]);
    }
    public function show(Warehouse $warehouse)
    {
        return view('warehouses.show', compact('warehouse')); // Vista para mostrar una almacen específica.
    }

    public function edit(Warehouse $warehouse)
    {
        return view('warehouses.edit', compact('warehouse')); // Vista para editar una almacen.
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:warehouses,name,' . $warehouse->id,
            'description' => 'nullable|string',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Almacen actualizada con éxito.');
    }

    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Almacen eliminada con éxito.');
    }
}
