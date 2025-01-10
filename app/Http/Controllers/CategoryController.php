<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('products.index', compact('categories')); // Asegúrate de tener una vista para mostrar las categorías.
    }
    public function getCategories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
    public function create()
    {
        return view('categories.create'); // Vista para crear una nueva categoría.
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // return redirect()->route('categories.index')->with('success', 'Categoría creada con éxito.');
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);

    }

    public function show(Category $category)
    {
        return view('categories.show', compact('category')); // Vista para mostrar una categoría específica.
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category')); // Vista para editar una categoría.
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);
    
        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
    
        // Recargar la categoría con los datos actualizados
        $category->refresh();
        return response()->json([
            'message' => 'Categoría actualizada con éxito.',
            'category' => $category
        ], 200); // Cambié el código de estado a 200 para indicar éxito en una actualización
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active; // Cambia el estado
        $category->save();

        return response()->json(['status' => 'success', 'new_status' => $category->is_active], 200);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito.');
    }
}
