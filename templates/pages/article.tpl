{extends file="layouts/main.tpl"}

{block name="title"}Статья: {$articleSlug}{/block}

{block name="content"}
    <h1>Статья: {$articleSlug}</h1>
    <p>Здесь будет полный текст статьи.</p>
{/block}