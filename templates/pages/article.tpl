{extends file="layouts/main.tpl"}


{block name="title"}Статья: {$articleSlug}{/block}
{block name="content"}
    <h1>Статья: {$article.title}</h1>
    <p>{$article.text}</p>
{*    <p>{$article.categories}</p>
проверим как как красивее
*}
{/block}


{*'id' => $id,
'image' => '',
'title' => 'заголовок',
'description' => 'описание статьи',
'text' => 'текст статьи',
'categories' => ['новости','погода','спорт'],
'views' => 0,
'related_articles' => [],*}