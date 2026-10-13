<?php
declare(strict_types=1);

use App\Controllers\CategoryController;
use App\Controllers\HomeController;
use App\Controllers\ArticleController;
use App\Controllers\NotFoundController;
use App\Services\Database;
use App\Services\TemplateEngine;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$db = new Database();
$templateEngine = new TemplateEngine();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/') ?: '/';
$httpMethod = $_SERVER['REQUEST_METHOD'];

$routeCollector = function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/', 'HomePage');
    $r->addRoute('GET', '/categories/{id}', 'showOneCategory');
    $r->addRoute('GET', '/categories', 'CategoryPage');
    $r->addRoute('GET', '/article/{id}', 'ArticlePage');
};

$routeInfo = FastRoute\simpleDispatcher($routeCollector)->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        (new NotFoundController($templateEngine))->index();
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);
        echo "Метод не разрешён";
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        match ($handler) {
            'HomePage' => (new HomeController($templateEngine, $db))->index(),
            'CategoryPage' => (new CategoryController($templateEngine, $db))->index(),
            'showOneCategory' => (new CategoryController($templateEngine, $db))->show((int)$vars['id']),
            'ArticlePage' => (new ArticleController($templateEngine, $db))->index((int)$vars['id']),
            default => (new NotFoundController($templateEngine))->index()
        };
        break;
}
