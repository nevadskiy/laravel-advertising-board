<?php

namespace App\Http\ViewComposers;

use App\Entity\Page;
use Illuminate\Contracts\View\View;

class MenuPagesComposer
{
    /**
     * @var Page
     */
    private $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }

    public function compose(View $view)
    {
        $view->with('menuPages', $this->page->whereIsRoot()->defaultOrder()->getModels());
    }
}
