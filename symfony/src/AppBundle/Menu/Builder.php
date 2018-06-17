<?php

namespace AppBundle\Menu;

use Knp\Menu\MenuFactory;


class Builder
{
    public function mainMenu(MenuFactory $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class','sidebar-nav');
        $menu->addChild('MenuBarTitle');
        $menu['MenuBarTitle']->setAttribute('class', 'sidebar-brand');
        $menu['MenuBarTitle']->setLabel('Main Menu');
        $menu->addChild('Home', ['route' => 'homepage']);
        $menu['Home']->setAttribute('class', 'sidebar-nav-item');
        $menu->addChild('Get Started',['route' => 'recognizePage']);
        $menu['Get Started']->setAttribute('class', 'sidebar-nav-item');
        $menu->addChild('About',['route' => 'aboutPage']);
        $menu['About']->setAttribute('class', 'sidebar-nav-item');
        return $menu;
    }

}