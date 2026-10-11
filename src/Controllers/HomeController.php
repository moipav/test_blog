<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\H;
use App\Models\Article;
use App\Models\Category;
use App\Services\TemplateEngine;

class HomeController
{
    private templateEngine $template;
    private Category $category;
    private Article $article;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
        /*
         * TODO переделать на DI
         * */
        $this->category = new Category();
        $this->article = new Article();
    }

    /*
     * ● Главная страница:

            ○ Вывести каждую категорию, в которой есть статьи и отобразить 3 последних поста (по дате публикации).
            ○ Вывести кнопку “Все статьи” для каждой категории.
    */
    public function index(): void
    {
        $categories = $this->category->getAllWithArticles();

        //для каждой категории получаем 3 последние статьи
        $categoriesWithArticles = [];
        foreach ($categories as $category) {
            $category['articles'] = $this->article->getLatestByCategory((int) $category['id'], 3);
            $categoriesWithArticles[] = $category;
        }

        $this->template->render('pages/home.tpl', [
            'categories' => $categoriesWithArticles,
        ]);
    }
}