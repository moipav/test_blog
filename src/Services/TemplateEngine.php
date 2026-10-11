<?php
declare(strict_types=1);

namespace App\Services;

use Smarty;

class TemplateEngine
{
    private  Smarty $smarty;
    public function __construct()
    {
        $this->smarty = new Smarty();

        // Настраиваем пути
        $this->smarty->setTemplateDir(__DIR__ . '/../../templates/');
        $this->smarty->setCompileDir(__DIR__ . '/../../cache/smarty/compile/');
        $this->smarty->setCacheDir(__DIR__ . '/../../cache/smarty/cache/');
        $this->smarty->setConfigDir(__DIR__ . '/../../cache/smarty/configs/');

        // Настройки кэширования
        $this->smarty->caching = false; // Пока отключим для разработки
        $this->smarty->compile_check = true;

        // Создаём директории, если их нет
        $this->createDirectories();
    }

    /**
     * Рендер шаблона
     */
    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->display($template);
    }

    private function createDirectories(): void
    {
        $dirs = [
            __DIR__ . '/../../cache/smarty/compile',
            __DIR__ . '/../../cache/smarty/cache',
            __DIR__ . '/../../cache/smarty/configs',
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
    }
}