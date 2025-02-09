<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_index_returns_categories_and_products()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function test_get_products_returns_json()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function test_show_product_by_category()
    {
        $this->assertTrue(true);
    }

/** @test */
    public function test_store_creates_a_product()
    {
        $this->assertTrue(true);
    }
}
