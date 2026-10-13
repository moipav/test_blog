<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{block name="title"}Блог{/block}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
<header>
    <nav>
        <a href="/" class="logo">
            <span>✨</span>
            <span>Мой Блог</span>
        </a>
        <div>
            <a href="/">Главная</a>
        </div>
    </nav>
</header>

<main>
    {block name="content"}{/block}
</main>

<footer>
    <p>&copy; {$smarty.now|date_format:"%Y"} Мой Блог. Все права защищены.</p>
</footer>
</body>
</html>