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



class CategoryAction extends CrudAction
{
    private $PostsModel;
    private $CategoriesModel;

    public function __construct(PostsModel $PostsModel, CategoriesModel $CategoriesModel, Router $router, RendererInterface $renderer, FlashService $flash)
    {
        parent::__construct($renderer,$flash,$router);
        $this->PostsModel = $PostsModel;
        $this->CategoriesModel = $CategoriesModel;
    }
    public function show(Request $request)
    {
        $slug = $request->getAttribute('slug');
        $category = $this->CategoriesModel->find("slug", $slug);
        if (!$category)
            return $this->redirect('main.index');

        $items = $this->PostsModel->FindPaginatedPublicForCategory(10,$category->id);
        $categories = $this->CategoriesModel->getAll();
        return $this->renderer->render('@Main/index', compact('category','items','categories'));
    }
}
