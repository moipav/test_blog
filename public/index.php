<?php
declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ArticleController;
use App\Controllers\NotFoundController;
use App\Services\TemplateEngine;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$templateEngine = new TemplateEngine();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';
$httpMethod = $_SERVER['REQUEST_METHOD'];

$routeCollector = function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'HomePage');
    $r->addRoute('GET', '/categories', 'CategoryPage');
    $r->addRoute('GET', '/article/{id}', 'ArticlePage');
};

$routeInfo = FastRoute\simpleDispatcher($routeCollector)->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        handleNotFound($templateEngine);
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "Метод не разрешён";
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        match ($handler) {
            'HomePage' => (new HomeController($templateEngine))->index(),
            'CategoryPage' => (new CategoryController($templateEngine))->index(),
            'ArticlePage' => (new ArticleController($templateEngine))->index($vars['id']),
            default => (new NotFoundController($templateEngine))->index()
        };
        break;
}

function handleNotFound(TemplateEngine $templateEngine): void
{
    http_response_code(404);
    $templateEngine->render('pages/404.tpl');
}
