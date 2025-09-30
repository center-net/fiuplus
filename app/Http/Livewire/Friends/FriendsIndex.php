<?php

namespace App\Http\Livewire\Friends;

use Livewire\Component;

class FriendsIndex extends Component
{
    public function render()
    {
        return view('livewire.friends.friends-index')
            ->layout('layouts.app', [
                'title' => 'الأصدقاء'
            ]);
    }
}