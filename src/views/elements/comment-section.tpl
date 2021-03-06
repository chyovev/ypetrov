<div class="comments-wrapper">
    <div class="comments"{if $comments|@count == 0} style="display: none"{/if}>
        <div class="section-title">Коментари</div>
        {foreach $comments as $item}
            {include './single-comment.tpl' comment=$item->getDetails() i=$item@iteration}
        {/foreach}
    </div>

    <div class="comment-form">
        <div class="section-title">Добавяне на коментар</div>
        <form method="POST" id="comment-form" class="ajax-form" action="{$_url}">
            <input type="text" id="username" name="username" placeholder="*Име" />
            <textarea name="comment" id="comment" placeholder="*Коментар"></textarea>
            {include file='./captcha.tpl'}
            <div class="error-message none"></div>
            <div class="success-message center green none"></div>
            <input type="submit" value="Изпрати" />
        </form>
    </div>
</div>