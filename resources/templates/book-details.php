<div id="dedication" style="display: none"></div>
<div id="body">
    <div class="book">
        <div class="cover"><img src="<?= $book['image'] ?>" alt=" <?= escape($book['title']) ?>" /></div>
        <div class="info">
            <div><strong>Година на издаване:</strong><?= $book['published_year'] ?></div>
            <div><strong>Стихотворения:</strong><?= count($book['contents']) ?></div>
        </div>
    </div>
</div>