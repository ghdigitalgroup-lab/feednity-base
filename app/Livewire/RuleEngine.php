<?php

namespace App\Livewire;

use Livewire\Component;

class RuleEngine extends Component
{
    public function render()
    {
        return view('livewire.rule-engine')
            ->layout('layouts.dashboard', ['title' => 'Rule Engine']);
    }
}
