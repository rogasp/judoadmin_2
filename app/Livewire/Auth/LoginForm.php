<?php

namespace App\Livewire\Auth;

use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Component;

class LoginForm extends Component
{
    #[validate(['required', 'email', 'max:255'])]
    public string $email = '';

    #[validate(['required', 'string', 'max:255'])]
    public string $password = '';

    public function submit(AuthManager $auth): void
    {
        $this->validate();

        if(!$auth->attempt(['email' => $this->email, 'password' => $this->password])) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email or password'
            ]);
        }

        //dd($auth);

        $this->redirect(
            url: route('pages:tenants:home')
        );
    }
    public function render(Factory $factory): view
    {
        return $factory->make(
            view: 'livewire.auth.login-form');
    }
}
