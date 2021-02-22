{if !isset($comments)}{$comments = []}{/if}
<div class="comments-wrapper">
    <div class="comments"{if $comments|@count == 0} style="display: none"{/if}>
        <div class="section-title">Коментари</div>
        {foreach $comments as $item}
            {$i=$item@iteration}
            {$comment = $item->getDetails()}
            {include './single-comment.tpl'}
        {/foreach}
    </div>

    <div class="comment-form">
        <div class="section-title">Добавяне на коментар</div>
        <form method="POST" id="comment-form" class="ajax-form" action="{$commentUrl}">
            <input type="text" id="username" name="username" placeholder="*Име" />
            <textarea name="comment" id="comment" placeholder="*Коментар"></textarea>
            <div class="error-message none"></div>
            <div class="success-message center green none"></div>
            <input type="submit" value="Изпрати" />
        </form>
    </div>
</div>