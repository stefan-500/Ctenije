<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Tabela extends Component
{

    /**
     * Odlucuje da li prikazati dugmad za akcije.
     *
     * @var bool
     */
    public $showActions;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Support\Collection $korisnici
     * @param bool $showActions
     * @return void
     */
    public function __construct($showActions = true)
    {
        $this->showActions = $showActions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.tabela');
    }
}
