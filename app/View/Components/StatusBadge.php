<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    /**
     * @param string|null $status Le statut (ex: 'active', 'pending', 'cancelled')
     * @param string|null $type   Le type visuel (ex: 'success', 'danger', 'warning')
     */
    public function __construct(
        public ?string $status = null,
        public ?string $type = null
    ) {
        // Valeurs par défaut lorsque la valeur fournie est null ou vide
        $this->status = $this->status ?? 'unknown';
        $this->type = $this->type ?? 'default';
    }

    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
