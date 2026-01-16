<?php

namespace App\Livewire;

use Livewire\Component;

class Breadcrumb extends Component
{
    public $li_1;
    public $li_2;
    public $li_3;
    public $title;
    public $pageTitle;
    public $breadcrumbItems = [];

    public function mount($li_1 = null, $li_2 = null, $li_3 = null, $title = null)
    {
        // Extract content from slot objects if needed
        $this->li_1 = $this->extractSlotContent($li_1);
        $this->li_2 = $this->extractSlotContent($li_2);
        $this->li_3 = $this->extractSlotContent($li_3);
        $this->title = $this->extractSlotContent($title);
        $this->pageTitle = $this->title ?? '';

        // Build breadcrumb items array
        $this->buildBreadcrumbItems();
    }

    /**
     * Build breadcrumb items array
     */
    private function buildBreadcrumbItems()
    {
        $this->breadcrumbItems = [];

        // Add li_1 (first breadcrumb item) if provided
        if (!empty($this->li_1)) {
            $this->breadcrumbItems[] = $this->li_1;
        }

        // Add li_2 (second breadcrumb item) if provided
        if (!empty($this->li_2)) {
            $this->breadcrumbItems[] = $this->li_2;
        }

        // Add li_3 (third breadcrumb item) if provided
        if (!empty($this->li_3)) {
            $this->breadcrumbItems[] = $this->li_3;
        }

        // Add the title as the last item
        if (!empty($this->title)) {
            $this->breadcrumbItems[] = $this->title;
        }
    }

    /**
     * Extract content from slot object or return the value as-is
     */
    private function extractSlotContent($slot)
    {
        if (is_null($slot)) {
            return null;
        }

        // If it's a slot object, convert to string
        if (is_object($slot) && method_exists($slot, 'toHtml')) {
            return $slot->toHtml();
        }

        if (is_object($slot) && method_exists($slot, '__toString')) {
            return (string) $slot;
        }

        // If it's already a string, return as-is
        return $slot;
    }

    public function render()
    {
        return view('livewire.breadcrumb');
    }
}
