<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\TemplateEngine;

class NotFoundController
{
    private TemplateEngine $template;

    public function __construct(TemplateEngine $template)
    {
        $this->template = $template;
    }

    public function index(): void
    {
        $this->template->render('pages/404.tpl');
    }
}