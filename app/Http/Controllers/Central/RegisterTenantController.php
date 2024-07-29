<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Livewire\Auth\RegisterForm;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegisterTenantController extends Controller
{
    public function showRegistrationForm($plan = null)
    {
        return view('pages.auth.register', [
            'livewire' => new RegisterForm($plan),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'subDomain' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('tenants', 'id')->where(function ($query) use ($request) {
                    return $query->where('id', $request->subDomain . '.' . $request->selectedDomain);
                }),
            ],
            'selectedDomain' => 'required|string|in:' . implode(',', config('tenancy.central_domains')),
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];

        $data = $request->validate($rules);

        $data['password'] = Hash::make($data['password']);
        $subDomain = $data['subDomain'];
        unset($data['subDomain']);
        $domain = $subDomain . '.' . $request->selectedDomain;

        $tenant = Tenant::create([
            'id' => $subDomain,
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'ready' => false,
        ]);

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        // Bygg fullständig URL för omdirigering
        $protocol = $request->getScheme(); // http eller https
        $host = $domain; // Subdomänen vi har skapat
        $port = $request->getPort(); // Portnummer om den inte är standard

        $redirectUrl = $protocol . '://' . $host;
        if ($port && $port != 80 && $port != 443) {
            $redirectUrl .= ':' . $port;
        }

        $redirectUrl .= route('pages:tenants:home', [], false);

        // Generera en impersonation token för att autentisera användaren i tenant-konteksten
        $token = tenancy()->impersonate($tenant, 1, $redirectUrl, 'web');

        $url = tenant_route($domain, 'pages:tenants:auth:impersonate', ['token' => $token->token]);
        // Omdirigera till tenantens domän med impersonation token
        return redirect($url);
    }
}
