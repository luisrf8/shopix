<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Plan;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\TenantPlanPayment;

class TenantController extends Controller
{
public function index()
{
    // Trae todos los tenants con todos sus planes asociados
    $tenants = Tenant::with(['tenantPlanPayments.plan'])->get();

    // O solo el plan activo de cada tenant
    // $tenants = Tenant::with(['activePlanPayment.plan'])->get();

    $plans = Plan::all();

    return view('tenant', compact('tenants', 'plans'));
}


    public function createIndex()
    {
        $tenants = Tenant::all();
        $plans = Plan::all();
        return view('createTenant', compact('tenants', 'plans'));

    }

    public function store(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:255',
            'slug'            => 'required|string|max:255|unique:tenants,slug',
            'email'           => 'required|email|unique:tenants,email',
            'logo'            => 'nullable|image|mimes:png,svg|max:2048',
            'color_primary'   => 'required|string|max:7',
            'color_secondary' => 'required|string|max:7',
            'color_accent'    => 'required|string|max:7',
            'users'           => 'array',
            'plan_id'         => 'required|exists:plans,id', 
        ]);

        // 📂 Subir logo si existe
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('tenants/logos', 'public');
        }

        // 🏢 Crear Tenant
        $tenant = Tenant::create([
            'name'            => $request->name,
            'slug'            => '/' . ltrim(Str::slug($request->slug), '/'),
            'email'           => $request->email,
            'logo'            => $logoPath,
            'color_primary'   => $request->color_primary,
            'color_secondary' => $request->color_secondary,
            'color_accent'    => $request->color_accent,
        ]);

        // 💳 Crear relación TenantPayment
        $plan = Plan::findOrFail($request->plan_id);

        TenantPlanPayment::create([
            'tenant_id' => $tenant->id,
            'plan_id'   => $plan->id,
            'amount'    => $plan->price,
            'status'    => 'paid', // o pending si quieres validar pago
            'paid_at'   => now(),
        ]);

        // 🎭 Obtener roles existentes
        $roles = Role::whereIn('name', ['owner', 'admin', 'vendor'])->get()->keyBy('name');

        // 👥 Crear usuarios enviados en el formulario
        if ($request->has('users')) {
            foreach ($request->users as $roleName => $userData) {
                if (!empty($userData['email'])) {
                    User::create([
                        'name'      => $userData['name'] ?? ucfirst($roleName),
                        'email'     => $userData['email'],
                        'password'  => Hash::make($userData['password'] ?? 'password123'),
                        'role_id'   => $roles[$roleName]->id ?? null,
                        'tenant_id' => $tenant->id,
                        'is_active' => 1,
                    ]);
                }
            }
        }

        return redirect()
            ->route('tenants.index')
            ->with('success', 'Tenant creado correctamente con su plan y usuarios.');
    }

    public function show(Tenant $tenant)
    {
        return $tenant;
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'slug'  => 'sometimes|string|max:255|unique:tenants,slug,' . $tenant->id,
            'email' => 'nullable|email',
            'logo'  => 'nullable|string',
        ]);

        $tenant->update($validated);

        return $tenant;
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return response()->json(['message' => 'Tenant eliminado correctamente']);
    }
}
