<?php

namespace Modules\Admin\Actions;

use Models\PostsModel;
use Models\CategoriesModel;
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
    
    public function __construct(PostsModel $PostsModel, CategoriesModel $CategoriesModel, Router $router, RendererInterface $renderer, FlashService $flash)
    {
        parent::__construct($renderer,$flash,$router);
        $this->PostsModel = $PostsModel;
        $this->CategoriesModel = $CategoriesModel;
    }
   
    public function posts(Request $request) : string
    {
        $items = $this->PostsModel->FindPaginated(10);
        $flash = $this->flash;
        return $this->renderer->render('@Admin/posts/list', compact('items', 'flash'));
    }
    public function postCreate(Request $request)
    {

        if ($request->getMethod() === 'POST') {
            $params = $this->getParams($request);
            $params = array_merge($params, [
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $validator = $this->validate($request);
            if ($validator->isValid()) {
                $result = $this->PostsModel->insert($params);
                $this->flash->success('The article has been successfully created');
                return $this->redirect('admin.posts');
            }
            $errors = $validator->getErrors();

        }
        $categoriesList = $this->CategoriesModel->findList(['id', 'title']);
        return $this->renderer->render('@Admin/posts/create', compact('errors', 'categoriesList'));
    }
    public function postEdit(Request $request)
    {
        $item = $this->PostsModel->find("id", $request->getAttribute('id'));
        if ($request->getMethod() === 'POST') {
            $validator = $this->validate($request);
            if ($validator->isValid()) {
                $result = $this->PostsModel->update($item->id, $this->getParams($request));
                $this->flash->success('The article has been successfully edited');
                return $this->redirect('admin.posts');
            }
            $errors = $validator->getErrors();
        }
        $categoriesList = $this->CategoriesModel->findList(['id', 'title']);
        return $this->renderer->render('@Admin/posts/edit', compact('item', 'errors', 'categoriesList'));
    }
    public function postDelete(Request $request)
    {
        $result = $this->PostsModel->delete($request->getAttribute('id'));
        if ($result)
            $this->flash->success('The article has been successfully removed');
        else
            $this->flash->error('An error occured');
        return $this->redirect('admin.posts');
    }
    private function getParams(Request $request)
    {
        $params =  array_filter($request->getRequest(), function ($key) {
            return in_array($key, ['title', 'slug', 'content', 'category_id']);
        }, ARRAY_FILTER_USE_KEY);
        return array_merge($params,[
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
    private function validate(Request $request)
    {
        return $this->getValidator($request)
            ->require('title', 'slug', 'content', 'category_id')
            ->length('content', 25)
            ->length('title', 5, 255)
            ->length('slug', 5, 255)
            ->unique('slug',$this->CategoriesModel,$request->getAttribute('id'))
            ->exists('category_id', $this->CategoriesModel)
            ->slug('slug');
    }
}
