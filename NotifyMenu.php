<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */

namespace wcs\menu;


class NotifyMenu extends Menu {


    protected $itemClass = 'wcs\menu\item\notifyItem';



    public function __construct($menuArr = null){

        if(empty($menuArr)) return;
        $this->setMenuConfig($menuArr);
        $menuRevised = $menuArr;
        //tag("menu_alter",$menuRevised);
        $this->setMenuRevised($menuRevised);
        $this->addItems($this->menuRevised);
        $this->addDelayItems();
        //tag("menu_items_alter",$this->menuItems);
        //print_r($this->menuItems);
        return;
    }


    public function render(){

        if( empty( $this->menuItems ) ) return;

        $this->sortItems();
        $outHtml = '';

        foreach($this->menuItems as $item){
            if($item->isRoot()) {
                $outHtml .= $item->render();
            }
        }

        return $outHtml;
    }


} 