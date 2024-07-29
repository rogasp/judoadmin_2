<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class LoginForm extends Component
{
    public string $email = '';
    public string $password = '';

    protected $rules = [
        'email' => 'required|email|max:255',
        'password' => 'required|string|max:255',
    ];

    public function submit(): void
    {
        $data = $this->validate();

        if(!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password'
            ]);
        }

        $this->redirect(route('pages:tenants:home'));
    }
    public function render(Factory $factory): view
    {
        return $factory->make(
            view: 'livewire.auth.login-form');
    }
}
