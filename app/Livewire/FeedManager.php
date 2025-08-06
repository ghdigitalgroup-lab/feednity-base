<?php

namespace App\Livewire;

use Livewire\Component;

class FeedManager extends Component
{
    public function render()
    {
        return view('livewire.feed-manager')
            ->layout('layouts.dashboard', ['title' => 'Feed Manager']);
    }
}
