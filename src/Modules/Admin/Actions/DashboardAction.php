<?php
namespace Modules\Admin\Actions;

use Framework\Lib\Router\Router;
use Framework\Lib\Module\CrudAction;
use Framework\Lib\Services\FlashService;
use Framework\Lib\Interfaces\RendererInterface;
use Modules\Admin\AdminWidgetInterface;

class DashboardAction extends CrudAction{
    private $widgets;
    public function __construct(RendererInterface $renderer, FlashService $flash,Router $router, array $widgets)
    {
        parent::__construct($renderer,$flash,$router);
        $this->widgets = $widgets;
    }
    public function index(){
        $widgets = array_reduce($this->widgets,function(string $html, AdminWidgetInterface $widget){
            return $html.$widget->render();
        },'');
        return $this->renderer->render('@Admin/dashboard', compact('widgets'));
    }
}