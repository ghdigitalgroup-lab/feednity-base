<?php

namespace App\Livewire;

use Livewire\Component;

class StoreConnections extends Component
{
    public function render()
    {
        return view('livewire.store-connections')
            ->layout('layouts.dashboard', ['title' => 'Store Connections']);
    }
}
