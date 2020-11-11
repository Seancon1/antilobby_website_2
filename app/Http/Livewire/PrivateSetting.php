<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Session;

class PrivateSetting extends Component
{
    public Session $session;

    public $result = "";

    protected $rules = [
        'session.private' => 'boolean',
    ];


    public function mount($sessionValue) {
        $this->session = Session::find($sessionValue);
    }

    public function save() {
        $this->session->save();
        $this->result = "Saved! (" . gmdate("H:i:s", time()) . ")";
    }

    public function render()
    {
        return view('livewire.private-setting');
    }

}
