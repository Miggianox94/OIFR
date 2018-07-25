<?php

namespace AppBundle\Menu;

use Knp\Menu\MenuFactory;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;


class Builder
{
    protected $securityContext;
    protected $isLoggedIn;
    protected $factory;

    /**
     * Builder constructor.
     * @param AuthorizationChecker $securityContext
     */
    public function __construct(MenuFactory $factory, AuthorizationChecker $securityContext) {
        $this->securityContext = $securityContext;
        $this->factory = $factory;
        $this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function mainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');
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
        if($this->isLoggedIn){
            $menu->addChild('Logout',['route' => 'logout']);
            $menu['Logout']->setAttribute('class', 'sidebar-nav-item');
        }
        return $menu;
    }

}