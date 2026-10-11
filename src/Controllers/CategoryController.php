<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TemplateEngine;

class CategoryController
{
    private templateEngine $template;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
    }

    public function index(): void
    {
        $categories = [
            ['name' => 'Новости', 'pages' => ' статьи из этой категории'],
            ['name' => 'кино', 'pages' => 'статьи из этой категории'],
            ['name' => 'знамениотости', 'pages' => 'статьи из этой категории']
        ];

        $this->template->render('pages/category.tpl', ['categories' => $categories]);
    }
}