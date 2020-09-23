<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;

class UserArea extends Component
{
    public $username = 'User';
    public $userIP;

    public function render(Request $request)
    {
        $this->userIP = $request->ip();
        return view('livewire.user-area');
    }
}
