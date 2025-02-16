<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // private $client;

    // public function __construct()
    // {
    //     $this->client = new Client();
    //     // $this->client->setAuthConfig(storage_path('app/credentials.json'));
    //     $this->client->addScope(Drive::DRIVE_FILE);
    //     $this->client->setAccessType('offline');
    //     $this->client->setPrompt('select_account consent');
    // }
    public function index()
    {
        $categories = Category::all();
        $productItems = Product::with(['category', 'images', 'variants'])->get();
        return view('products', compact('categories', 'productItems')); // Asegúrate de tener una vista para mostrar las categorías.
    }
    public function getProducts()
    {
        $productItems = Product::with(['category', 'images', 'variants'])->get();
        return response()->json($productItems);
    }
    public function categoriesIndex()
    {
        $categories = Category::all();
        return view('categories', compact('categories')); // Asegúrate de tener una vista para mostrar las categorías.
    }
    public function showByCategory($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $categories = Category::all();
        $productItems = Product::where('category_id', $category->id)->get();
    
        return view('products', compact('productItems', 'category', 'categories'));
    }
    public function showByCategoryEcomm($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        $categories = Category::all();
        $productItems = Product::where('category_id', $category->id)
        ->with(['images', 'variants'])
        ->get();
        return response()->json($productItems);
    }
    public function showByCategoryEcommAll()
    {
        try {
            // Obtener todos los productos con sus imágenes y variantes
            $productItems = Product::with(['images', 'variants'])->get();
    
            // Verificar si hay productos, de lo contrario devolver un mensaje adecuado
            if ($productItems->isEmpty()) {
                return response()->json(['message' => 'No products found'], 404);
            }
    
            return response()->json($productItems);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }
    
    public function showByProduct($id)
    {
        $product = Product::with(['variants', 'images', 'category'])->findOrFail($id);
        $categories = Category::all();
        return view('productItem', compact('product', 'categories'));
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required',
        ]);

        // Crear el producto
        Product::create($validatedData);
        return response()->json(['success' => true, 'message' => 'Product created successfully'], 200);

    }
    public function create(Request $request)
    {
        // dd($request);
        // Validar los datos del producto y las variantes
        $request->validate([
            // 'category_id' => 'required|numeric',
            // 'productName' => 'required|string|max:255',
            // 'productDescription' => 'required|string',
            // 'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // 'variants' => 'nullable|array',
        ],
         [
            'productName.unique' => 'El nombre del producto ya está registrado. Por favor, elige otro.',
        ]);
    
        // Crear el producto
        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->productName,
            'description' => $request->productDescription,
        ]);
        // Guardar cada imagen
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }
        $variants = $request->variants;
        if (is_string($variants)) {
            $variants = json_decode($variants, true);
        }
        // Crear las variantes con stock
        if (!empty($variants) && is_array($variants)) {
            foreach ($variants as $variant) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $variant['name'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'], // Agregar el stock aquí
                ]);
            }
        }
    
        return response()->json(['success' => true, 'message' => 'Product created successfully']);
        // return response()->json(['message' => 'Category created successfully', 'product' => $product], 201);

    }
        // Función para agregar una imagen
        public function addImage(Request $request, $productId)
        {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // Guardar la imagen en el almacenamiento
            $path = $request->file('image')->store('products', 'public');
    
            // Asociar la imagen al producto
            ProductImage::create([
                'product_id' => $productId,
                'path' => $path,
            ]);
    
            return redirect()->back()->with('success', 'Imagen agregada correctamente.');
        }
    
        // Función para eliminar una imagen
        public function removeImage($imageId)
        {
            $image = ProductImage::findOrFail($imageId);
    
            // Eliminar la imagen del almacenamiento
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
    
            // Eliminar el registro de la base de datos
            $image->delete();
    
            return response()->json(['success' => true, 'message' => 'Imagen eliminada correctamente.']);
        }
    
    public function storeGoogle(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filePath = $image->getPathName();
                $fileName = $image->getClientOriginalName();
                $fileMetadata = new Drive\DriveFile([
                    'name' => $fileName,
                    'parents' => ['your-folder-id']
                ]);

                $content = file_get_contents($filePath);
                $file = $service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => $image->getMimeType(),
                    'uploadType' => 'multipart',
                    'fields' => 'id'
                ]);

                $fileId = $file->id;
                $fileUrl = "https://drive.google.com/uc?export=view&id={$fileId}";

                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $fileUrl,
                ]);
            }
        }

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function show($id) {
        $product = Product::with(['images', 'variants', 'category'])->findOrFail($id);
        // dd($product); // Esto te mostrará los datos completos del producto
        return response()->json($product);
    }
    
    public function update(Request $request, $id) {
        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category_id = $request->category;
        
        $product->save();
        return response()->json(['message' => 'Producto actualizado con éxito.', 'Producto' => $product], 201);


    }
    
}
