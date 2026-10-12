<?php

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Faker\Factory as FakerFactory;

// Загружаем переменные окружения
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

echo "=== Starting Database Seeding with Faker ===\n\n";

// Создаём Faker с русской локалью
$faker = FakerFactory::create('ru_RU');

try {
    // Подключение к БД
    $dsn = "mysql:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4";
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    echo "✓ Connected to database successfully.\n";

    // Очищаем таблицы (в правильном порядке из-за внешних ключей)
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE category_article");
    $pdo->exec("TRUNCATE TABLE articles");
    $pdo->exec("TRUNCATE TABLE categories");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✓ Tables truncated.\n\n";

    // ============================================
    // 1. Сидинг категорий
    // ============================================
    $categoriesData = [
        ['name' => 'Технологии', 'description' => 'Новости из мира IT, программирования и новых технологий'],
        ['name' => 'Путешествия', 'description' => 'Истории из поездок, обзоры стран и городов'],
        ['name' => 'Кулинария', 'description' => 'Вкусные рецепты, обзоры ресторанов и кулинарные советы'],
        ['name' => 'Спорт', 'description' => 'Спортивные новости, тренировки и здоровый образ жизни'],
        ['name' => 'Наука', 'description' => 'Открытия, исследования и научные достижения'],
        ['name' => 'Культура', 'description' => 'Искусство, кино, музыка и литературные обзоры'],
        ['name' => 'Бизнес', 'description' => 'Стартапы, инвестиции и бизнес-стратегии'],
    ];

    $stmtCat = $pdo->prepare("INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)");
    $categoryIds = [];

    foreach ($categoriesData as $cat) {
        $slug = transliterate($cat['name']);
        $stmtCat->execute([
            'name' => $cat['name'],
            'slug' => $slug,
            'description' => $cat['description'],
        ]);
        $categoryIds[$slug] = $pdo->lastInsertId();
        echo "  ✓ Category created: {$cat['name']} ({$slug})\n";
    }
    echo "\n✓ Seeded " . count($categoriesData) . " categories.\n\n";

    // ============================================
    // 2. Сидинг статей
    // ============================================
    $stmtArt = $pdo->prepare("
        INSERT INTO articles (title, slug, description, content, image, views, published_at) 
        VALUES (:title, :slug, :description, :content, :image, :views, :published_at)
    ");

    $stmtLink = $pdo->prepare("INSERT INTO category_article (category_id, article_id) VALUES (:cat_id, :art_id)");

    $articleCount = 30;
    $articleIds = [];

    echo "Creating {$articleCount} articles...\n";

    for ($i = 1; $i <= $articleCount; $i++) {
        // Генерируем данные статьи
        $title = $faker->sentence(rand(4, 8));
        $title = rtrim($title, '.'); // Убираем точку в конце
        $slug = transliterate($title) . '-' . $i;

        // Генерируем HTML-контент
        $paragraphs = $faker->paragraphs(rand(3, 6));
        $content = implode("\n", array_map(fn($p) => "<p>{$p}</p>", $paragraphs));

        // Добавляем заголовки в контент
        if (rand(0, 1)) {
            $content = "<h2>{$faker->sentence(4)}</h2>\n" . $content;
        }

        $articleData = [
            'title' => $title,
            'slug' => $slug,
            'description' => $faker->paragraph(2),
            'content' => $content,
            'image' => '/uploads/image.jpg',
            'views' => $faker->numberBetween(10, 5000),
            'published_at' => $faker->dateTimeBetween('-60 days', 'now')->format('Y-m-d H:i:s'),
        ];

        $stmtArt->execute($articleData);
        $articleId = $pdo->lastInsertId();
        $articleIds[] = $articleId;

        // Привязываем к 1-3 случайным категориям
        $categorySlugs = array_keys($categoryIds);
        $randomCategories = $faker->randomElements($categorySlugs, rand(1, 3));

        foreach ($randomCategories as $catSlug) {
            $stmtLink->execute([
                'cat_id' => $categoryIds[$catSlug],
                'art_id' => $articleId,
            ]);
        }

        echo "  ✓ Article #{$i}: {$title} (categories: " . implode(', ', $randomCategories) . ")\n";
    }

    echo "\n✓ Seeded {$articleCount} articles with categories.\n";

    // ============================================
    // 3. Статистика
    // ============================================
    echo "\n=== Seeding Statistics ===\n";

    $stats = $pdo->query("
        SELECT 
            (SELECT COUNT(*) FROM categories) as categories,
            (SELECT COUNT(*) FROM articles) as articles,
            (SELECT COUNT(*) FROM category_article) as links
    ")->fetch();

    echo "Categories: {$stats['categories']}\n";
    echo "Articles: {$stats['articles']}\n";
    echo "Category-Article links: {$stats['links']}\n";

    echo "\n=== Seeding completed successfully! ===\n";

} catch (PDOException $e) {
    echo "\n✗ Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// ============================================
// Вспомогательная функция транслитерации
// ============================================
function transliterate(string $text): string
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd',
        'е' => 'e', 'ё' => 'e', 'ж' => 'zh', 'з' => 'z', 'и' => 'i',
        'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
        'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'sch', 'ъ' => '', 'ы' => 'y', 'ь' => '',
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ' ' => '-', '.' => '', ',' => '', '!' => '', '?' => '',
        ':' => '', ';' => '', '(' => '', ')' => '',
    ];

    $text = mb_strtolower($text);
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9\-]/', '', $text);
    $text = preg_replace('/-+/', '-', $text);
    $text = trim($text, '-');

    return $text;
}
