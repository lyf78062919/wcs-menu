<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */
namespace wcs\menu\item;

class SimpleItem extends Item {


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
        if($this->isParent()){
            $outHtml .= '<li  class="dropdown" ><a  ' . $this->getAttributesString() . ' ><span class="'.$this->iconClass.'"></span>' . $this->title .'</a><ul>';
            $childOutHtml = "";
            foreach($this->children as $child){
                $childOutHtml .= $child->render();
            }
            $outHtml .= $childOutHtml;
            $outHtml .= '</ul></li>';
        }else{

            $outHtml .= '<li><a  ' . $this->getAttributesString() . '  ><span class="'.$this->iconClass.'"></span>'. $this->title .'</a></li>';

        }

        return $outHtml;
    }

}