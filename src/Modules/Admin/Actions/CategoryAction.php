<?php

namespace Modules\Admin\Actions;

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
    public function __construct( CategoriesModel $CategoriesModel, Router $router, RendererInterface $renderer, FlashService $flash)
    {
        parent::__construct($renderer,$flash,$router);
        $this->CategoriesModel = $CategoriesModel;
    }
    public function categories(Request $request) : string
    {
        $items = $this->CategoriesModel->FindPaginated(10);
        $flash = $this->flash;
        return $this->renderer->render('@Admin/categories/list', compact('items', 'flash'));
    }
    public function categoryCreate(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $params = $this->getCategoryParams($request);
            $validator = $this->validate($request);
            if($validator->isValid()){
                $result = $this->CategoriesModel->insert($params);
                $this->flash->success('The article has been successfully created');
                return $this->redirect('admin.categories');
            }
            $errors = $validator->getErrors();
            
        }
        return $this->renderer->render('@Admin/categories/create',compact('errors'));
    }
    public function categoryEdit(Request $request)
    {
        $item = $this->CategoriesModel->find("id", $request->getAttribute('id'));
        if ($request->getMethod() === 'POST') {
            $params = $this->getCategoryParams($request);
            $validator = $this->validate($request);
            if($validator->isValid()){ 
                $result = $this->PostsModel->update($item->id, $params);
                $this->flash->success('The article has been successfully edited');
                return $this->redirect('admin.posts');
            }
            $errors = $validator->getErrors();
        }

        return $this->renderer->render('@Admin/categories/edit', compact('item','errors'));
    }
    public function categoryDelete(Request $request)
    {
        $result = $this->CategoriesModel->delete($request->getAttribute('id'));
        if ($result)
                $this->flash->success('The article has been successfully removed');
            else
                $this->flash->error('An error occured');
        return $this->redirect('admin.posts');
    }

    
    private function getCategoryParams(Request $request)
    {
        return array_filter($request->getRequest(), function ($key) {
            return in_array($key, ['title', 'slug']);
        }, ARRAY_FILTER_USE_KEY);
    }
   
    private function validate(Request $request){
        return $this->getValidator($request)
            ->require('title','slug')
            ->length('title',5,255)
            ->length('slug',5,255)
            ->unique('slug',$this->CategoriesModel,$request->getAttribute('id'))
            ->slug('slug');
    }
}
