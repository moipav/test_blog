{extends file="layouts/main.tpl"}

{block name="title"}{$article.title} - Блог{/block}

{block name="content"}
    <article class="article-page">
        <header class="article-header">
            <h1>{$article.title}</h1>

            <div class="article-meta">
                <span class="date">📅 {$article.published_at|date_format:"%d.%m.%Y"}</span>
                <span class="views">👁 {$article.views} просмотров</span>

                {if $categories}
                    <span class="categories">
                        🏷️ {foreach $categories as $cat}
                            <a href="/category/{$cat.id}" class="category-tag">{$cat.name}</a>{if not $cat@last}, {/if}
                        {/foreach}
                    </span>
                {/if}
            </div>
        </header>

        {if $article.image}
            <div class="article-image-wrapper">
                <img src="{$article.image}" alt="{$article.title}" width="100" class="article-main-image">
            </div>
        {/if}

        <div class="article-content">
            <p class="article-lead">{$article.description}</p>
            <div class="article-body">
                {$article.content}
            </div>
        </div>
    </article>

    {* Блок похожих статей *}
    {if $relatedArticles}
        <section class="related-articles">
            <h2>Похожие статьи</h2>
            <div class="articles-grid">
                {foreach $relatedArticles as $related}
                    <article class="article-card">
                        {if $related.image}
                            <img src="{$related.image}" alt="{$related.title}" width="50" class="article-image">
                        {/if}
                        <div class="article-content">
                            <h3>
                                <a href="/article/{$related.id}">{$related.title}</a>
                            </h3>
                            <p class="article-description">{$related.description}</p>
                            <div class="article-meta">
                                <span class="views">👁 {$related.views}</span>
                                <span class="date">{$related.published_at|date_format:"%d.%m.%Y"}</span>
                            </div>
                        </div>
                    </article>
                    <hr>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}