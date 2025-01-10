<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\DollarRate;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Comparte la última tasa del dólar con todas las vistas que incluyen el layout 'layouts.head'
        View::composer('layouts.head', function ($view) {
            $dollarRate = DollarRate::latest('created_at')->first();
            $view->with('dollarRate', $dollarRate);
        });
    }
}
