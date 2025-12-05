<?php

namespace App\View\Components\Layouts;

use Illuminate\View\Component;

class App extends Component
{
    public $title;
    public $description;
    public $ogImage;
    public $ogTitle;
    public $ogDescription;
    public $twitterTitle;
    public $twitterDescription;
    public $twitterImage;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $title = null,
        $description = null,
        $ogImage = null,
        $ogTitle = null,
        $ogDescription = null,
        $twitterTitle = null,
        $twitterDescription = null,
        $twitterImage = null
    ) {
        $this->title = $title;
        $this->description = $description;
        $this->ogImage = $ogImage;
        $this->ogTitle = $ogTitle;
        $this->ogDescription = $ogDescription;
        $this->twitterTitle = $twitterTitle;
        $this->twitterDescription = $twitterDescription;
        $this->twitterImage = $twitterImage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.layouts.app');
    }
}
