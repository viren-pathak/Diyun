<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ProgressBar extends Component
{
    public $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function render()
    {
        return view('components.progress-bar');
    }
}