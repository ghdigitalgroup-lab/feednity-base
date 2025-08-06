<?php

namespace App\Livewire;

use Livewire\Component;

class BillingOverview extends Component
{
    public function render()
    {
        return view('livewire.billing-overview')
            ->layout('layouts.dashboard', ['title' => 'Billing Overview']);
    }
}
