<?php

namespace App\Livewire\Layout;

use Livewire\Component;
use Livewire\Attributes\Locked;

class Sidebar extends Component
{

     // desktop collapse state
    public bool $collapsed = false;

    // mobile open state
    public bool $open = false;

    // optional: mount with defaults
    public function mount(bool $collapsed = false)
    {
        $this->collapsed = $collapsed;
    }

    public function toggleCollapse()
    {
        $this->collapsed = ! $this->collapsed;
    }

    public function openMobile()
    {
        $this->open = true;
    }

    public function closeMobile()
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.layout.sidebar');
    }
}
