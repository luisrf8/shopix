<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase; // Permite resetear la base de datos para cada prueba

    /** @test */
    public function test_index_returns_categories()
    {
        // Crear algunas categorías manualmente
        Category::create(['name' => 'Category 1', 'description' => 'Description 1']);
        Category::create(['name' => 'Category 2', 'description' => 'Description 2']);
        Category::create(['name' => 'Category 3', 'description' => 'Description 3']);

        // Hacer la solicitud GET a la ruta que lista las categorías
        $response = $this->get(route('categories.index'));

        // Verificar que la respuesta tiene un estado 200 y que contiene las categorías
        $response->assertStatus(200);
        $response->assertViewHas('categories'); // Verifica que la vista tenga las categorías
    }

    /** @test */
    public function test_get_categories_returns_json()
    {
        // Crear algunas categorías manualmente
        $category1 = Category::create(['name' => 'Category 1', 'description' => 'Description 1']);
        $category2 = Category::create(['name' => 'Category 2', 'description' => 'Description 2']);
        $category3 = Category::create(['name' => 'Category 3', 'description' => 'Description 3']);

        // Hacer la solicitud GET para obtener categorías en formato JSON
        $response = $this->getJson(route('categories.getCategories'));

        // Verificar que la respuesta es un JSON y contiene las categorías
        $response->assertStatus(200);
        $response->assertJsonCount(3); // Verifica que haya 3 categorías
    }

    /** @test */
    public function test_store_creates_a_category()
    {
        // Datos para crear una categoría
        $categoryData = [
            'name' => 'New Category',
            'description' => 'A new category description.',
        ];

        // Hacer la solicitud POST para crear una categoría
        $response = $this->postJson(route('categories.store'), $categoryData);

        // Verificar que la categoría se haya creado correctamente
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Category created successfully',
            'category' => $categoryData,
        ]);

        // Verificar que la categoría esté en la base de datos
        $this->assertDatabaseHas('categories', $categoryData);
    }

    /** @test */
    public function test_update_category()
    {
        // Crear una categoría manualmente
        $category = Category::create(['name' => 'Old Category', 'description' => 'Old description.']);

        // Datos para actualizar la categoría
        $updatedData = [
            'name' => 'Updated Category',
            'description' => 'Updated description.',
        ];

        // Hacer la solicitud PUT para actualizar la categoría
        $response = $this->postJson(route('categories.update', $category), $updatedData);

        // Verificar que la respuesta sea exitosa y contiene los datos actualizados
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Categoría actualizada con éxito.',
            'category' => $updatedData,
        ]);

        // Verificar que la categoría esté actualizada en la base de datos
        $this->assertDatabaseHas('categories', $updatedData);
    }

    /** @test */
    public function test_toggle_status_changes_category_status()
    {
        // Crear una categoría manualmente
        $category = Category::create(['name' => 'Category to toggle', 'description' => 'A category to toggle', 'is_active' => 1]);

        // Hacer la solicitud PUT para cambiar el estado de la categoría
        $response = $this->postJson(route('categories.toggleStatus', $category));

        // Verificar que el estado de la categoría se haya cambiado
        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'is_active' => 0, // Verifica que el estado se haya cambiado
        ]);
    }

    /** @test */
}
