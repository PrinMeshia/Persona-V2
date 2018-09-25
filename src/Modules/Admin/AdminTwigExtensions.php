<?php

namespace Modules\Admin;




class AdminTwigExtensions extends \Twig_Extension
{
    private $widgets ;
    public function __construct(array $widgets){
        $this->widgets = $widgets;
    }
    public function getFunctions(){
        return [
            new \Twig_SimpleFunction('adminMenu',[$this,'renderMenu'],['is_safe'=>["html"]])
        ];
    }
    public function renderMenu():string{
        return array_reduce($this->widgets,function(string $html, AdminWidgetInterface $widget){
            return $html.$widget->renderMenu();
        },'');
        return $this->renderer->render('@Admin/dashboard', compact('widgets'));
    }


}