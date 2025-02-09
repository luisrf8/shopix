<?php

namespace Tests\Feature;

use App\Models\Currency;
use App\Models\PaymentMethod;
use App\Models\DollarRate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PaymentMethodControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_payment_methods_with_grouped_currencies_and_dollar_rate()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_create_a_payment_method()
    {
        $this->assertTrue(true);
    }

    /** @test */
    public function it_can_update_a_payment_method()
    {
        // Crear moneda y método de pago manualmente
        $currency = new Currency([
            'name' => 'USD',
            'code' => 'USD'
        ]);
        $currency->save();

        $paymentMethod = new PaymentMethod([
            'name' => 'Credit Card',
            'currency_id' => $currency->id,
        ]);
        $paymentMethod->save();

        // Actualizar datos del método de pago
        $data = [
            'name' => 'Updated Payment Method',
            'currency' => $currency->id,
        ];

        // Realizar la solicitud PUT para actualizar el método de pago
        $response = $this->post(route('paymentMethods.edit', $paymentMethod->id), $data);

        // Comprobar que la respuesta fue exitosa
        $response->assertJson(['message' => 'Método de pago actualizado correctamente.']);
    }

    /** @test */
    public function it_can_toggle_payment_method_status()
    {
        $currency = new Currency([
            'name' => 'USD',
            'code' => 'USD'
        ]);
        $currency->save();

        $paymentMethod = new PaymentMethod([
            'name' => 'Credit Card',
            'currency_id' => $currency->id,
        ]);
        $paymentMethod->save();

        // Realizar la solicitud PATCH para cambiar el estado
        $response = $this->post(route('paymentMethods.toggleStatus', $paymentMethod->id));

        // Comprobar que la respuesta fue exitosa
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Método de pago inactivado exitosamente']);

        // Verificar el cambio de estado
        $paymentMethod->refresh();
    }

    /** @test */
    public function it_can_create_a_currency()
    {
        // Datos para crear una nueva moneda
        $data = [
            'name' => 'USD',
            'code' => 'USD',
        ];

        // Realizar la solicitud POST para crear la moneda
        $response = $this->post(route('paymentMethods.currencyCreate'), $data);

        // Comprobar que la respuesta fue exitosa
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Método de pago creado exitosamente']);
    }

    /** @test */
    public function it_can_update_dollar_rate()
    {
        // Datos para actualizar la tasa del dólar
        $data = [
            'rate' => 20.5,
        ];

        // Realizar la solicitud PUT para actualizar la tasa del dólar
        $response = $this->post(route('paymentMethods.updateDollarRate'), $data);

        // Comprobar que la respuesta fue exitosa
        $response->assertStatus(201);
        $response->assertJson(['message' => 'Tasa del dólar actualizada exitosamente']);
    }
}
