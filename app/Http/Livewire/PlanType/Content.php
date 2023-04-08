<?php

namespace App\Http\Livewire\PlanType;

use App\Http\Livewire\Layouts\DynamicContent;
use Livewire\Component;

class Content extends DynamicContent
{
    public function render()
    {
        return view('livewire.plan-type.content');
    }
}
