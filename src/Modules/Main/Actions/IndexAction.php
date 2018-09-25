<?php

namespace Modules\Main\Actions;

use Models\PostsModel;
use Models\CategoriesModel;
use Framework\Lib\Router\Router;
use Framework\Lib\Module\CrudAction;
use Framework\Lib\Services\FlashService;
use Framework\Lib\Interfaces\RendererInterface;
use Framework\Lib\Interfaces\RequestInterface as Request;
use Framework\Lib\Interfaces\ResponseInterface as Response;

class IndexAction extends CrudAction
{
    private $PostsModel;
    private $CategoriesModel;
    
    public function __construct(PostsModel $PostsModel, CategoriesModel $CategoriesModel, Router $router, RendererInterface $renderer, FlashService $flash)
    {
        parent::__construct($renderer,$flash,$router);
        $this->PostsModel = $PostsModel;
        $this->CategoriesModel = $CategoriesModel;
    }
    public function index(Request $request) : string
    {
        $items = $this->PostsModel->FindPaginatedPublic(10);
        $categories = $this->CategoriesModel->getAll();
        return $this->renderer->render('@Main/index', compact('items','categories'));
    }

}
