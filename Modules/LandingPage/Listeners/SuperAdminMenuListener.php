<?php

namespace Modules\LandingPage\Listeners;

use App\Events\SuperAdminMenuEvent;

class SuperAdminMenuListener
{
    /**
     * Handle the event.
     */
    public function handle(SuperAdminMenuEvent $event): void
    {
        $module = 'LandingPage';
        $menu = $event->menu;
       
        $menu->add([
            'title' => __('CMS'),
            'icon' => 'package',
            'name' => 'landing-page',
            'parent' => null,
            'order' => 220,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => '',
            'module' => 'LandingPage',
            'permission' => 'Manage LandingPage'
        ]);
        $menu->add([
            'title' => __('Landing Page'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 1,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'landingpage.index',
            'module' => 'LandingPage',
            'permission' => 'Manage LandingPage'
        ]);
        $menu->add([
            'title' => __('Marketplace'),
            'icon' => 'settings',
            'name' => '',
            'parent' => 'landing-page',
            'order' => 2,
            'ignore_if' => [],
            'depend_on' => [],
            'route' => 'marketplace.index',
            'module' => 'LandingPage',
            'permission' => 'Manage LandingPage'
        ]);
    }
}
