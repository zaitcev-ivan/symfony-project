<?php

namespace AppBundle\Component;

use SbS\AdminLTEBundle\Model\MenuItemModel;
use SbS\AdminLTEBundle\Model\MenuItemInterface;

class MenuBuilder {

    public function getMenu() {

        // Menu Label
        $label_main = new MenuItemModel('Панель управления');

        // One Level Menu
        $item_info = (new MenuItemModel('Information'))
            ->setRoute('admin_homepage')
            ->setIcon('fa fa-circle-o text-blue')
            ->addBadge('17', MenuItemInterface::COLOR_RED)
            ->addBadge('new');

        $user_info = (new MenuItemModel('Пользователи'))
            ->setRoute('admin_user_list')
            ->setIcon('fa fa-circle-o text-blue');

        $contact_info = (new MenuItemModel('Обратная связь'))
            ->setRoute('admin_contact_list')
            ->setIcon('fa fa-circle-o text-blue');

        $brand_info = (new MenuItemModel('Бренды'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Список брендов'))->setRoute('admin_brand_list')
            )
            ->addChild(
                (new MenuItemModel('Создать бренд'))->setRoute('admin_brand_create')
            )
        ;

        $category_info = (new MenuItemModel('Категории'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Список категорий'))->setRoute('admin_category_list')
            )
            ->addChild(
                (new MenuItemModel('Создать категорию'))->setRoute('admin_category_create')
            )
            ;

        $characteristic_info = (new MenuItemModel('Характеристики'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Список характеристик'))->setRoute('admin_characteristic_list')
            )
            ->addChild(
                (new MenuItemModel('Создать характеристику'))->setRoute('admin_characteristic_create')
            )
            ;

        $product_info = (new MenuItemModel('Продукты'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Список продуктов'))->setRoute('admin_product_list')
            )
            ->addChild(
                (new MenuItemModel('Создать продукт'))->setRoute('admin_product_create')
            )
        ;

        $delivery_info = (new MenuItemModel('Методы доставки'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Список метдов'))->setRoute('admin_delivery_list')
            )
            ->addChild(
                (new MenuItemModel('Создать метод'))->setRoute('admin_delivery_create')
            )
        ;

        // Multi Level Menu
        $item_multilevel = (new MenuItemModel('Multilevel'))
            ->setIcon('fa fa-share')
            ->addChild(
                (new MenuItemModel('Level One'))
                    ->addChild(
                        (new MenuItemModel('Level Two'))
                            ->setChildren([
                                (new MenuItemModel('Level Three'))->setRoute('admin_homepage'),
                                (new MenuItemModel('Level Three'))->setRoute('admin_homepage')
                            ])
                    )
                    ->addChild((new MenuItemModel('Level Two'))->setRoute('admin_homepage'))
            )
            ->addChild((new MenuItemModel('Level One'))->setRoute('admin_homepage')->addBadge('new'));
        // ...

        return [
            $label_main,
            $user_info,
            $contact_info,
            $brand_info,
            $category_info,
            $characteristic_info,
            $product_info,
            $delivery_info,
            //$item_multilevel,
            //$item_info
        ];
    }
}