{extends file="layouts/main.tpl"}

{block name="title"}{$category.name} - Блог{/block}

{block name="content"}
    <div class="category-page">
        <header class="category-header">
            <h1>{$category.name}</h1>
            <p class="category-description">{$category.description}</p>
            <p class="articles-count">Всего статей: {$totalArticles}</p>
        </header>

        {if $articles}
            {* Панель сортировки *}
            <div class="sort-panel">
                <span class="sort-label">Сортировать:</span>
                <a href="?sort=published_at&order={if $sortBy == 'published_at' && $sortOrder == 'DESC'}ASC{else}DESC{/if}"
                   class="sort-link {if $sortBy == 'published_at'}active{/if}">
                    По дате
                    {if $sortBy == 'published_at'}
                        {if $sortOrder == 'DESC'}↓{else}↑{/if}
                    {/if}
                </a>
                <a href="?sort=views&order={if $sortBy == 'views' && $sortOrder == 'DESC'}ASC{else}DESC{/if}"
                   class="sort-link {if $sortBy == 'views'}active{/if}">
                    По просмотрам
                    {if $sortBy == 'views'}
                        {if $sortOrder == 'DESC'}↓{else}↑{/if}
                    {/if}
                </a>
            </div>

            {* Список статей *}
            <div class="articles-grid">
                {foreach $articles as $article}
                    <article class="article-card">
                        {if $article.image}
                            <img src="{$article.image}" alt="{$article.title}" class="article-image" width="100">
                        {/if}
                        <div class="article-content">
                            <h3>
                                <a href="/article/{$article.slug}">{$article.title}</a>
                            </h3>
                            <p class="article-description">{$article.description}</p>
                            <div class="article-meta">
                                <span class="views">👁 {$article.views}</span>
                                <span class="date">{$article.published_at|date_format:"%d.%m.%Y"}</span>
                            </div>
                        </div>
                    </article>
                {/foreach}
            </div>

            {* Пагинация *}
            {if $totalPages > 1}
                <nav class="pagination">
                    {* Кнопка "Назад" *}
                    {if $currentPage > 1}
                        <a href="?sort={$sortBy}&order={$sortOrder}&page={$currentPage - 1}" class="pagination-link">← Назад</a>
                    {else}
                        <span class="pagination-link disabled">← Назад</span>
                    {/if}

                    {* Номера страниц *}
                    {for $i=1 to $totalPages}
                        {if $i == $currentPage}
                            <span class="pagination-link active">{$i}</span>
                        {else}
                            <a href="?sort={$sortBy}&order={$sortOrder}&page={$i}" class="pagination-link">{$i}</a>
                        {/if}
                    {/for}

                    {* Кнопка "Вперёд" *}
                    {if $currentPage < $totalPages}
                        <a href="?sort={$sortBy}&order={$sortOrder}&page={$currentPage + 1}" class="pagination-link">Вперёд →</a>
                    {else}
                        <span class="pagination-link disabled">Вперёд →</span>
                    {/if}
                </nav>
            {/if}
        {else}
            <div class="empty-state">
                <p>В этой категории пока нет статей</p>
            </div>
        {/if}
    </div>
{/block}