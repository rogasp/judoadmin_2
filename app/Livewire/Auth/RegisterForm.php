<?php

namespace App\Livewire\Auth;

use App\Models\Tenant;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class RegisterForm extends Component
{
    public $centralDomains = [];
    public $selectedDomain;

    public $subDomain;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $plan;

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'subDomain' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('tenants', 'id')->where(function ($query) {
                    return $query->where('id', $this->subDomain . '.' . $this->selectedDomain);
                }),
            ],
            'selectedDomain' => 'required|string|in:' . implode(',', Config::get('tenancy.central_domains', [])),
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function mount($plan = 'free'): void
    {
        $this->plan = $plan;
        $this->centralDomains = Config::get('tenancy.central_domains', []);
        $this->selectedDomain = $this->centralDomains[0] ?? null;
    }

    public function submit(): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Foundation\Application
    {
        $this->validate();

        $subDomain = $this->subDomain;
        $domain = $subDomain . '.' . $this->selectedDomain;

        $tenant = Tenant::create([
            'id' => $subDomain,
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'ready' => false,
            'plan' => $this->plan,
        ]);

        $tenant->domains()->create([
            'domain' => $domain,
        ]);

        // Bygg fullständig URL för omdirigering
        $protocol = request()->getScheme(); // http eller https
        $host = $domain; // Subdomänen vi har skapat
        $port = request()->getPort(); // Portnummer om den inte är standard

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

    public function render(Factory $factory): View
    {
        return $factory->make('livewire.auth.register-form');
    }
}
