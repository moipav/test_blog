{extends file="layouts/main.tpl"}

{block name="title"}Главная - Блог{/block}

{block name="content"}
    <h1>Привет !</h1>
    <p>Это главная страница нашего блога.</p>
    <section>
        <h2>Все категории</h2>
        {if $categories}
            {foreach $categories as $category}
                <ul>
                    <h3>{$category.name}</h3>
                    <a class="btn btn-primary" href="/categories/{$category.id}">Все статьи</a>
                    <p class="category-description">{$category.description}</p>
                    {if $category.articles}
                    <div class="articles-grid>">
                        {foreach $category.articles as $article}
                            <ul>
                                <li>
                                    {if $article.image}
                                        <img src="{$article.image}" alt="{$article.title}" width="100">
                                    {/if}
                                    <h3>
                                        <a href="/article/{$article.id}">{$article.title}</a>
                                    </h3>
                                    <p class="article-description">{$article.description}</p>
                                    <div class="article-meta">
                                        <span class="views">👁 {$article.views}</span>
                                        <span class="date">{$article.published_at|date_format:"%d.%m.%Y"}</span>
                                    </div>
                                </li>
                            </ul>
                        {/foreach}
                        {/if}
                </ul>
            {/foreach}
        {else}
            <p>Пока нет статей</p>
        {/if}
    </section>
{/block}