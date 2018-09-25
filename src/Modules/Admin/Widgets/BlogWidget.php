<?php

namespace Modules\Admin\Widgets;

use Framework\Lib\Interfaces\RendererInterface;
use Modules\Admin\AdminWidgetInterface;
use Models\PostsModel;


class BlogWidget  implements AdminWidgetInterface
{

    private $renderer;
    private $PostsModel;
    public function __construct(RendererInterface $renderer,PostsModel $PostsModel)
    {
        $this->renderer = $renderer;
        $this->PostsModel = $PostsModel;
    }
    public function render():string{
        $count = $this->PostsModel->count();
        return $this->renderer->render('@Admin/widgets/widget',compact("count"));
    }
    public function renderMenu():string{
        $count = $this->PostsModel->count();
        return $this->renderer->render('@Admin/widgets/menu');
    }
}
