<?php

namespace App\Livewire\Auth;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\AuthManager;
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

    public function submit(AuthManager $auth): void
    {
        $this->addUniqueSubdomainRule();

        //dd('HERE2');
        //$this->validate();

        //dd('HERE3');
        $tenant = Tenant::create([
            'name' => $this->subDomain,
        ]);

        // Create a new domain with subdomain and attach
        $domain = $this->subDomain . '.' . $this->selectedDomain;
        $tenant->domains()->create(['domain' => $domain]);

        $userData = [
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
        ];

        $tenant->run(function () use ($userData){
            $user = User::create($userData);
            auth()->login($user);
        });

        // Omdirigera till tenantens dashboard eller annan lämplig sida
        $this->redirect(
            url: route('pages:tenants:home')
        );
    }
    public function render(Factory $factory): view
    {
        return $factory->make(
            view: 'livewire.auth.register-form'
        );
    }
}
