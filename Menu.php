<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */

namespace wcs\menu;

use core\helpers\ArrayHelper;

abstract class Menu {


    public $title;
    /**
     * 菜单配置项
     * @var array
     */
    protected $menuConfig;

    /**
     * 合并后的菜单项
     * @var array
     */
    protected $menuRevised;

    /**
     * 菜单项集合
     * @var array Item
     */
    protected $menuItems;


    /**
     * 菜单项默认类
     * @var
     */
    protected $itemClass;



    /**
     * @param mixed $menuRevised
     */
    public function setMenuRevised($menuRevised)
    {
        $this->menuRevised = $menuRevised;
    }

    /**
     * @return mixed
     */
    public function getMenuRevised()
    {
        return $this->menuRevised;
    }

    /**
     * @param mixed $menuConfig
     */
    public function setMenuConfig($menuConfig)
    {
        $this->menuConfig = $menuConfig;
    }

    /**
     * @return mixed
     */
    public function getMenuConfig()
    {
        return $this->menuConfig;
    }

    /**
     * @return array
     */
    public function getMenuItems()
    {
        return $this->menuItems;
    }

    public function getMenuItem($name){

        if(!empty($name) && !empty($this->menuItems[$name]) ){
            return $this->menuItems[$name];
        }else{
            return null;
        }

    }


    protected function addDelayItems(){

        if(!empty($this->delayItems)){
            $this->addItems($this->delayItems);
            unset($this->delayItems);
        }
    }

    /**
     * 根据菜单数组添加菜单项目
     */
    protected function addItems(array $menuArr, $parent = null){

        if(empty($menuArr)) return;

        foreach($menuArr as $item){

            $oldParent = $parent;

            if(array_key_exists("parentName",$item) && !empty($item['parentName']) ){
                if( !empty($this->menuItems[$item['parentName']]) ){
                    $parent = $this->menuItems[$item['parentName']];
                }else{
                    $this->delayItems[$item['name']] = $item;
                    continue;
                }
            }

            $obj = $this->addItem($item,$parent);
            $parent = $oldParent;
            if(!empty($item['child'])){
                $this->addItems($item['child'],$obj);
            }
        }


    }


    /**
     * 添加菜单项目
     */
    public function addItem(array $item, $parent = null){

        //检查 “name” 的名称是否合法
        if (!preg_match('/^[a-z0-9_]*$/i', $item['name'] ) || empty($item['name']) ) {
            throw new \ErrorException("菜单 name 必须指定！确保其合法性和唯一性。 ".$item['name']."不合法！");
        }

        $itemClass = $this->itemClass;
        if(!class_exists($itemClass)){
            $classFile = __DIR__."/1Item/".$itemClass.".class.php";
            if(!file_exists($classFile)){
                throw new \ErrorException("加载 ".$classFile." 文件失败！");
                return;
            }else{

                require_once $classFile;

            }
        }

        $itemObj = new $itemClass($item, $parent);
        if(!array_key_exists($item['name'],(array)$this->menuItems)){
            $this->menuItems[$item['name']] = $itemObj;
        }
        return $itemObj;
    }

    /**
     * @param mixed $itemClass
     */
    public function setItemClass($itemClass)
    {
        $this->itemClass = $itemClass;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }




    /**
     *对菜单对象进行排序操作
     */
    public function sortItems(){
        usort($this->menuItems,array($this, 'sortRootItems'));
        foreach($this->menuItems as $item){
            if($item->isParent()) {
                $itemChild = $item->getChildren();
                ArrayHelper::sortObjects($itemChild,"weight");
            }
        }
    }

    protected function sortRootItems($a,$b){

        if ( $a->weight == $b->weight ) return 0;
        return ($a->weight > $b->weight ) ? -1 : 1;
    }

    abstract public function render();


} 