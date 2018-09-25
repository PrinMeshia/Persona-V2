<?php

namespace Modules\Main\Actions;

use Models\PostsModel;
use Framework\Lib\Router\Router;
use Framework\Lib\Module\CrudAction;
use Framework\Lib\Services\FlashService;
use Framework\Lib\Interfaces\RendererInterface;
use Framework\Lib\Interfaces\RequestInterface as Request;
use Framework\Lib\Interfaces\ResponseInterface as Response;

class PostAction extends CrudAction
{
    private $PostsModel;
    private $CategoriesModel;
    
    public function __construct(PostsModel $PostsModel, Router $router, RendererInterface $renderer, FlashService $flash)
    {
        parent::__construct($renderer,$flash,$router);
        $this->PostsModel = $PostsModel;
    }
    public function show(Request $request)
    {
        $id = $request->getAttribute('id');
        $slug = $request->getAttribute('slug');
        $item = $this->PostsModel->find("id", $id);

        if ($slug !== $item->slug . '-')
            return $this->redirect('main.show', ['slug' => $item->slug, 'id' => $item->id]);
        return $this->renderer->render('@Main/show', compact('item'));
    }
}
