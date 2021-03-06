<?php

use Modules\Main\Actions \{
    PostAction, IndexAction, CategoryAction
};
use Modules\Admin\Actions \{
    PostAction as AdminPost, CategoryAction as AdminActegory
};
use Framework\Lib\Router\Router;
use Modules\Admin\Actions\DashboardAction;
$router = \DI\get(Router::class);
$blogPrefix =  \DI\get("blog.prefix");
$AdminPrefix =  \DI\get("admin.prefix");
$router->get('/persona-v2/blog', [IndexAction::class, 'index'], 'main.index');
$router->get('/persona-v2/blog/{slug:[a-z\-0-9]+}-{id:[\d]+}', [PostAction::class, 'show'], 'post.show');
$router->get('/persona-v2/category/{slug:[a-z\-0-9]+}', [CategoryAction::class, 'show'], 'category.show');

$router->get('/persona-v2/admin/', [DashboardAction::class, 'index'], 'admin.index');
$router->get('/persona-v2/admin/posts', [AdminPost::class, 'posts'], 'admin.posts');
$router->add('/persona-v2/admin/posts/{id:[\d]+}', [AdminPost::class, 'postEdit'], 'admin.post.edit', ['GET', 'POST']);
$router->add('/persona-v2/admin/posts/create', [AdminPost::class, 'postCreate'], 'admin.post.create', ['GET', 'POST']);
$router->delete('/persona-v2/admin/posts/{id:[\d]+}', [AdminPost::class, 'postDelete'], 'admin.post.delete');
$router->get('/persona-v2/admin/categories', [AdminActegory::class, 'categories'], 'admin.categories');
$router->add('/persona-v2/admin/categories/{id:[\d]+}', [AdminActegory::class, 'categoryEdit'], 'admin.category.edit', ['GET', 'POST']);
$router->add('/persona-v2/admin/categories/create', [AdminActegory::class, 'categoryCreate'], 'admin.category.create', ['GET', 'POST']);
$router->delete('/persona-v2/admin/categories/{id:[\d]+}', [AdminActegory::class, 'categoryDelete'], 'admin.category.delete');  