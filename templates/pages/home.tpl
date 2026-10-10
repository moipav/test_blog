{extends file="layouts/main.tpl"}

{block name="title"}Главная - Блог{/block}

{block name="content"}
    <h1>Привет !</h1>
    <p>Это главная страница нашего блога.</p>

    <section>
        <h2>Последние статьи</h2>
        {if isset($articles) && $articles}
            <ul>
                {foreach $articles as $article}
                    <li>
                        <h3>{$article.title}</h3>
                        <p>{$article.description}</p>
                    </li>
                {/foreach}
            </ul>
        {else}
            <p>Пока нет статей</p>
        {/if}
    </section>
{/block}