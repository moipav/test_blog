{extends file="layouts/main.tpl"}

{*{block name="title"}Категория: {$categorySlug}{/block}*}

{block name="content"}
    {foreach $categories as $category}
        <h1>Категория: {$category.name}</h1>
        <p>Здесь будут статьи из этой категории.</p>
        <p>Здесь будут связанные статьи со списком и пагинацией</p>
    {/foreach}
{/block}