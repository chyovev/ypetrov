<div id="dedication" style="display: none"></div>
<div id="body">
    <div class="book">
        <div class="cover"><img src="{$book['image']}" alt="{escape($book['title'])}" /></div>
        <div class="info">
            <div><strong>Заглавие:</strong> {escape($book['title'])}</div>
            {if $book['publisher']}
                <div><strong>Издателство:</strong> {$book['publisher']}</div>
            {/if}
            <div><strong>Година на издаване:</strong> {$book['published_year']}</div>
            <div><strong>Стихотворения:</strong> {$book['contents']|@count}</div>
        </div>
    </div>
</div>