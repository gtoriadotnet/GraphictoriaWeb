<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SocialCard extends Component
{
	public $title;
	public $description;
	public $link;
	
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($title, $description, $link)
    {
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.social-card');
    }
}
