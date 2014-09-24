<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */
namespace wcs\menu\item;

class NotifyItem extends Item {


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
        static $odd = 0;
        $this->addAttribute(array("_pname"=>!empty($this->parent)?$this->parent->name:null));
        $this->updateAttributes();
        if($this->disabled) return;
        $outHtml = "";
        if($this->isParent()){
            $outHtml .= '<li class="' . ($odd%2==0?'odd':'') .  '">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="" >
                        <span class="count"> '. count( $this->child ) .' </span>
                        <span class="head-icon ' . $this->iconClass . '"></span>
                        <span class="headmenu-label">'. $this->title .'</span>
                    </a>
                    <ul class="dropdown-menu">
                    <li class="nav-header">'. $this->title .'</li>';
            $childOutHtml = "";
            foreach($this->children as $child){
                $childOutHtml .= $child->render();
            }
            $outHtml .= $childOutHtml;
            $outHtml .= '</ul></li>';
            $odd++;
        }else{

            $outHtml .= '<li><a  ' . $this->getAttributesString() . '  ><span class="' . $this->iconClass .'"></span>' . $this->title .' <small class="muted"></small></a></li>';


        }

        return $outHtml;
    }

}