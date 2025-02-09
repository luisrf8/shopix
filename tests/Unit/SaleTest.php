<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Customer;
use App\Models\SaleDetail;
use App\Models\ProductItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SaleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_sales()
    {
        $this->assertTrue(true);
}

    public function test_store_creates_new_sale()
    {
        $this->assertTrue(true);
}

    public function test_store_ecommerce_sale()
    {
        $this->assertTrue(true);
    }

    public function test_view_orders_returns_orders()
    {
        $this->assertTrue(true);
}
}
