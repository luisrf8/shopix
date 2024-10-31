<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories')); // Asegúrate de tener una vista para mostrar las categorías.
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

        return redirect()->route('categories.index')->with('success', 'Categoría actualizada con éxito.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoría eliminada con éxito.');
    }
}
