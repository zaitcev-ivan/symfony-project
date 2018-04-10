<?php
namespace AppBundle\EventListener;

use AppBundle\Component\MenuBuilder;
use SbS\AdminLTEBundle\Event\SidebarMenuEvent;
use SbS\AdminLTEBundle\Model\MenuItemModel;

class SidebarMenuEventListener {

    private $builder;

    public function __construct(MenuBuilder $builder)
    {
        $this->builder = $builder;
    }

    public function onShowMenu(SidebarMenuEvent $event)
    {
        foreach ($this->builder->getMenu() as $item) {
            $event->addItem($item);
        }
    }
}