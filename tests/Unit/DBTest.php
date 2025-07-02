<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DBTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_db_conection()
    {
        $this->assertTrue(true);
    }
}
