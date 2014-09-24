<?php
/**
 * @version    WCS 6.0
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 * @Author:    lyf78062919@gmail.com
 */

namespace wcs\menu;

use yii\base\ErrorException;
use yii\base\Widget;


class Menus extends Widget
{


    /**
     * @var string simple|notify|shortcut
     */
    public $type = '';

    /**
     * @var boolean whether to render the block content in place. Defaults to false,
     * meaning the captured block content will not be displayed.
     */
    public $renderInPlace = false;


    /**
     * Starts recording a block.
     */
    public function init()
    {
        ob_start();
        ob_implicit_flush(false);
    }

    /**
     * Ends recording a block.
     * This method stops output buffering and saves the rendering result as a named block in the view.
     */
    public function run()
    {
        $block = ob_get_clean();
        if ($this->renderInPlace) {
            echo $block;
        }
        echo $this->renderMenu();
        $this->view->blocks[$this->getId()] = $block;
    }

    protected function renderMenu(){

        //$user = \Yii::$app->user->identity;
        $output = '';
        $menuArr = [];

        $menuArr['simple'] = [
            [
                'name' => 'head',
                'title' => '栏目管理',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconfa-laptop',
                'child' => [
                    [
                        'name' =>  'arctype_add',
                        'title' => '添加栏目'
                    ],
                    [
                        'name' =>  'arctype_edit',
                        'title' => '编辑栏目'
                    ],
                    [
                        'name' =>  'arctype_del',
                        'title' => '删除栏目'
                    ]
                ]
            ],
            [   'name' => 'dev',
                'title' => '开发工具'
            ]
        ];


        $menuArr['shortcut'] = [
            [
                'name' => 'event',
                'title' => '待办事务',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconsi-event'
            ],
            [
                'name' => 'cart',
                'title' => '订单管理',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconsi-cart'
            ],
            [
                'name' => 'archive',
                'title' => '文章管理',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconsi-archive'
            ],
            [
                'name' => 'help',
                'title' => '系统帮助',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconsi-help'
            ],
            [
                'name' => 'images',
                'title' => '相册管理',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'iconsi-images'
            ]
        ];



        $menuArr['notify'] = [
            [
                'name' => 'msg',
                'title' => '消息',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'head-message',
                'child' => [
                    [
                        'name' =>  'm1',
                        'title' => '小明请你过去玩',
                        'iconClass'=>'icon-envelope'
                    ],
                    [
                        'name' =>  'm2',
                        'title' => '黎明的妈妈过来了'
                    ],
                    [
                        'name' =>  'm3',
                        'title' => '小黄吸毒被抓了'
                    ]
                ]
            ],
            [
                'name' => 'user',
                'title' => '用户',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'head-users',
                'child' => [
                    [
                        'name' =>  'm1',
                        'title' => '小明请你过去玩'
                    ],
                    [
                        'name' =>  'm2',
                        'title' => '黎明的妈妈过来了'
                    ],
                    [
                        'name' =>  'm3',
                        'title' => '小黄吸毒被抓了'
                    ]
                ]
            ],
            [
                'name' => 'tj',
                'title' => '统计',
                'url'   => 'http://www.baidu.com',
                'iconClass' => 'head-bar',
                'child' => [
                    [
                        'name' =>  'm1',
                        'title' => '小明请你过去玩'
                    ],
                    [
                        'name' =>  'm2',
                        'title' => '黎明的妈妈过来了'
                    ],
                    [
                        'name' =>  'm3',
                        'title' => '小黄吸毒被抓了'
                    ]
                ]
            ]
        ];





        $className = 'wcs\\menu\\'.ucfirst( $this->type ) . 'Menu';

        if( class_exists( $className ) ){

            $menu = new $className($menuArr[$this->type]);

        }else{

            throw new ErrorException('未知的 '.$className.' 类型');
            return;

        }


        $menu->title = '功能菜单';
        $output = $menu->render();


        return $output;

    }



}
