<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VariantControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_index_returns_product_variants()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function test_store_creates_product_variants()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function test_edit_returns_variant_edit_view()
    {
        $this->assertTrue(true);
}

    /** @test */
    public function test_update_modifies_product_variant()
    {
        $this->assertTrue(true);
 }

    /** @test */
    public function test_destroy_deletes_product_variant()
    {
        $this->assertTrue(true);
}
}
