<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */
namespace wcs\menu\item;

use core\helpers\ArrayHelper;

abstract class Item extends \yii\base\Object {


    /**
     * @var \Item 父菜单
     */
    protected $parent = null;

    /**
     * @var array 子菜单项
     */
    protected $children = array();

    /**
     * 菜单项名字
     * @var string
     */
    protected $name = "";

    /**
     * class 名称
     * @var string
     */
    protected $class = "";

    /**
     * 样式
     * @var string
     */
    protected $style = "";

    /**
     * 菜单项显示内容
     * @var string
     */
    protected $title = "";

    /**
     * 菜单项链接地址
     * @var string
     */
    protected $url = "";

    /**
     * 链接参数
     * @var string
     */
    protected $urlParam = "";


    /**
     * 链接参数
     * @var string
     */
    protected $parsedUrl = "";

    /**
     * 菜单项链接地址
     * @var string
     */
    protected $target = "";


    /**
     * target默认值
     * @var int
     */
    protected $targetArray = array("_blank","_self","_parent","_top");


    /**
     * 是不是根菜单
     * @var bool
     */
    protected $isRoot = false;


    /**
     * 菜单排序权重
     * @var bool
     */
    public $weight = 0;


    /**
     * 菜单权重步长
     * @var int
     */
    protected $weightStep = 5;


    /**
     * 菜单权重初始值
     * @var int
     */
    protected $weightDefault = 200;



    /**
     * 是否禁用
     * @var bool
     */
    protected $disabled = false;

    /**
     * 显示与否
     * @var bool
     */
    protected $display = true;


    /**
     * 额外的属性
     * @var array
     */
    protected $attribute = array();

    /**
     * 属性数组
     * @var array
     */
    protected $attributes = array();


    protected $child = [];

    /**
     * 累加序号
     * @var array
     */
    protected static $count = array();



    /**
     * @param Item $parent
     * @param array $options
     */
    public function __construct(array $options, Item $parent = null){
        //初始化属性参数
        parent::__construct($options);
        $count_key = ( $parent === null )?"root":$parent->name;
        $count = empty(self::$count[$count_key])?(self::$count[$count_key] = $this->weightDefault ):self::$count[$count_key] -= $this->weightStep;
        $this->weight = isset($options['weight'])?$options['weight']:$count;
        $this->setParent($parent);
        return;
    }


    protected function add(Item $item){
        if($item instanceof self){
            if(!array_key_exists($item->name,$this->children)){
                $this->children[$item->name] = $item;
            }
        }
        return;
    }



    /**
     * @return string
     */
    //todo:待加强
    protected function parseUrl(){

        if(empty($this->url) || preg_match('#^[a-zA-Z]*://.*$#i',$this->url) ) {
            $this->parsedUrl = $this->url;
            return;
        }else if(strpos($this->url,"/") === 0 ){
            $this->parsedUrl = $this->url;
            return;
        }else{
            $param = array();
            if(!empty($this->urlParam)){
                foreach($this->urlParam as $field => $urlField){
                    if(!empty($field) && !is_numeric($field))
                        $param[$field] = $urlField;
                }
            }
            $this->parsedUrl = U($this->url,$param);
            return;
        }
    }


    protected function updateDisplayStyle(){

        $style = $this->style;
        if(!$this->display){
            $this->addAttribute(array("style"=>"display:none;"));
        }
        $this->style = $style;
    }

    /**
     * @param array $attribute
     */
    protected function addAttribute(array $attribute)
    {
        if(!empty($this->attribute)){
            $this->attribute = array_merge($this->attribute,$attribute);
        }else{
            $this->attribute = $attribute;
        }

    }

    protected function updateAttributes(){

        $this->parseUrl();
        $this->setDebugTitle();
        $this->updateDisplayStyle();

        $data = array();
        $data['id']          = $this->name;
        $data['name']        = $this->name;
        $data['class']       = $this->class;
        $data['href']        = $this->parsedUrl;
        $data['target']      = $this->target;
        $data['style']       = $this->style;

        if(!empty($this->attribute)){
            $data = ArrayHelper::arrayMerge($data,$this->attribute,array("style","class"));
        }

        $this->attributes = $data;
    }


    public function getAttribute($name = null){

        if(empty($name)){
            return $this->attributes;
        }else{
            return $this->attributes[$name];
        }
    }

    /**
     * @return array
     */
    protected function getAttributesString()
    {
        if(!empty($this->attributes)){
            $str = ArrayHelper::toString($this->attributes);
        }

        return $str;
    }


    protected function addClass($className){
        if(!empty($this->class)){
            $classArr = explode(" ",$this->class);
            array_push($classArr,$className);
            $className = implode(" ",$classArr);
        }
        $this->class = $className;
    }


    protected function setDebugTitle(){
        if(YII_DEBUG){
            $title = "name: ".$this->name." 标题: ".$this->title." 权重: ".$this->weight;
            $extra = array(
                "title" => $title
            );
            $this->addAttribute($extra);
        }
    }




    //外部接口

    /**
     * @param boolean $disabled
     */
    public function setDisabled($disabled)
    {
        $this->disabled = $disabled;
    }

    /**
     * @return boolean
     */
    public function getDisabled()
    {
        return $this->disabled;
    }

    /**
     * @param boolean $display
     */
    public function setDisplay($display)
    {
        $this->display = $display;
    }

    /**
     * @return boolean
     */
    public function getDisplay()
    {
        return $this->display;
    }


    /**
     * @return bool
     */
    public function isParent(){

        return  !empty($this->children)?true:false;
    }

    public function getChildren(){

        return $this->children;

    }

    public function isRoot(){

        return $this->isRoot;

    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }



    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @return boolean
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @param array $attribute
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
    }

    /**
     * @param string $style
     */
    public function setStyle($style)
    {
        $this->style = $style;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @param string $urlParam
     */
    public function setUrlParam($urlParam)
    {
        $this->urlParam = $urlParam;
    }

    /**
     * @param boolean $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @param Item $parent
     */
    public function setParent( $parent )
    {
        if($parent === $this){
            E("请不要将父亲指向自身！");
            return;
        }
        if($parent && $parent instanceof self ){
            $this->parent = $parent;
            $this->isRoot = false;
            $parent->add($this);
        }else{
            $this->parent = null;
            $this->isRoot = true;
        }
        return;
    }

    /**
     * @param array $child
     */
    public function setChild($child)
    {
        $this->child = $child;
    }

    /**
     * @return array
     */
    public function getChild()
    {
        return $this->child;
    }



    /**
     * 渲染输出
     */
    abstract protected function render();

}