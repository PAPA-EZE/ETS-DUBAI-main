<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public function __construct(
        public string $status,
        public string $type = 'default'
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
