<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\H;
use App\Services\TemplateEngine;

class HomeController
{
    private templateEngine $template;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
    }

    /*
     * ● Главная страница:

            ○ Вывести каждую категорию, в которой есть статьи и отобразить 3 последних поста (по дате публикации).
             ○ Вывести кнопку “Все статьи” для каждой категории.
    */
    public function index(): void
    {
        $articles = [
            ['title' => ' article1', 'description' => ' description for article1'],
            ['title' => ' article2', 'description' => ' description for article2'],
        ];

        $this->template->render('pages/home.tpl', [
            'articles' => $articles
        ]);
    }
}