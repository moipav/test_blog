<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TemplateEngine;

class ArticleController
{
    private templateEngine $template;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
    }

/*
 * ● Страница статьи:

    ○ Вывести всю информацию о статье
        - ○ Изображение
        - ○ Название
        - ○ Описание
        - ○ Текст
        - ○ Категория (одна или несколько)
        - ○ Кол-во просмотров
        - ○ Вывести блок из 3 похожих статей
*/
    public function index($id): void
    {
        $article = [
            'id' => $id,
            'image' => '',
            'title' => 'заголовок',
            'description' => 'описание статьи',
            'text' => 'текст статьи',
            'categories' => ['новости','погода','спорт'],
            'views' => 0,
            'related_articles' => [],
        ];

        $this->template->render('pages/article.tpl', ['article' => $article]);
    }
}