<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\H;
use App\Models\Category;
use App\Services\TemplateEngine;

class CategoryController
{
    private TemplateEngine $template;
    private Category $category;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
        $this->category = new Category();
    }

    public function index(): void
    {
        $categories = $this->category->findAll();
        $this->template->render('pages/category.tpl', ['categories' => $categories]);
    }
}