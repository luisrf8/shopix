<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
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
    public function create(Request $request)
    {
        // Validar los datos del producto y las imágenes
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Crear el producto
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        // Guardar cada imagen en el almacenamiento local y en la base de datos
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Guarda la imagen en el almacenamiento local (storage/app/public/products)
                $path = $image->store('products', 'public');

                // Crear un registro en la tabla de imágenes del producto con la ruta de la imagen
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path, // Ruta de la imagen en el sistema de archivos
                ]);
            }
        }

        // Responder con éxito
        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
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

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        if ($request->hasFile('images')) {
            // Eliminar imágenes existentes
            foreach ($product->images as $image) {
                // Asumiendo que la ruta de la imagen es la URL completa de Google Drive
                // Extraer el ID del archivo de la URL
                $fileId = basename(parse_url($image->path, PHP_URL_PATH));
                $service->files->delete($fileId);
                $image->delete();
            }

            // Guardar nuevas imágenes
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

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
    }
}
