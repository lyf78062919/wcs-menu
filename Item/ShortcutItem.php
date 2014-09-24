<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */
namespace wcs\menu\item;

class ShortcutItem extends Item {


    /**
     * 菜单权重步长
     * @var int
     */
    protected $weightStep = 3;

    /**
     * 菜单权重初始值
     * @var int
     */
    protected $weightDefault = 100;

    /**
     * 图标 class
     * @var string
     */
    protected $iconClass = '';

    /**
     * @param string $iconClass
     */
    public function setIconClass($iconClass)
    {
        $this->iconClass = $iconClass;
    }

    /**
     * @return string
     */
    public function getIconClass()
    {
        return $this->iconClass;
    }





    /**
     * 渲染输出
     */
    public function render(){
        $this->addAttribute(array("_pname"=>!empty($this->parent)?$this->parent->name:null));
        $this->updateAttributes();
        if($this->disabled) return;
        $outHtml = "";
        $outHtml .='<li class="">
            <a  ' . $this->getAttributesString() . '  >
                <span class="shortcuts-icon '.$this->iconClass.'"></span>
                <span class="shortcuts-label">'.$this->title.'</span>
            </a>
        </li>';

        return $outHtml;
    }

}