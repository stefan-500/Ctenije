<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteConfirmationModal extends Component
{
    /**
     * URL prefix za route.
     *
     * @var string
     */
    public $urlPrefix;

    /**
     * Create a new component instance.
     *
     * @param string $urlPrefix
     * @return void
     */
    public function __construct($urlPrefix)
    {
        $this->urlPrefix = $urlPrefix;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.delete-confirmation-modal');
    }
}
