<?php

namespace App\Livewire\Component\Tenant;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProfileDropdown extends Component
{
    public $user;
    public $dropdownOpen = false; // Tillstånd för att hantera dropdown

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function toggleDropdown()
    {
        $this->dropdownOpen = !$this->dropdownOpen; // Växla tillståndet för dropdown
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('pages:tenants:auth:login');
    }

    public function render()
    {
        return view('livewire.component.tenant.profile-dropdown');
    }
}
