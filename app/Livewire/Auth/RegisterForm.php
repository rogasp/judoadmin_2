<?php

namespace App\Livewire\Auth;

use App\Models\Tenant;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Stancl\Tenancy\Features\UserImpersonation;

class RegisterForm extends Component
{

    public $centralDomains = [];
    public $selectedDomain;

    public $subDomain;

    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'name' => 'required|string|max:255',
        'subDomain' => [
            'required',
            'string',
            'max:255',
            'alpha_dash',
        ],
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    private function addUniqueSubdomainRule()
    {
        $this->rules['subDomain'][] = Rule::unique('tenants', 'id')->where(function ($query) {
            return $query->where('id', $this->subDomain . '.' . $this->selectedDomain);
        });
    }

    public function mount()
    {
        $this->centralDomains = Config::get('tenancy.central_domains', []);
        $this->selectedDomain = $this->centralDomains[0] ?? null;
    }

    public function submit(AuthManager $auth): \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Foundation\Application
    {
        //$this->addUniqueSubdomainRule();
        //dd($this->subDomain, $this->selectedDomain, $this->name, $this->email, $this->password, $this->password_confirmation);
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'subDomain' => $this->subDomain,
        ];
        //$data = $this->validate();
        $data['password'] = bcrypt($data['password']);

        $subDomain = $data['subDomain'];
        unset($data['subDomain']);
        $domain = $subDomain . '.' . $this->selectedDomain;

        $tenant = new Tenant(
            attributes: [
                'id' => $subDomain,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'ready' => false,
            ]
        );

        $tenant->save();

        $tenant->domains()->create(
            attributes: [
                'domain' => $domain,
            ]
        );


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
    public function render(Factory $factory): view
    {
        return $factory->make(
            view: 'livewire.auth.register-form'
        );
    }
}
