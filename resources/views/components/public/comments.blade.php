<div class="comments-wrapper">
    <div @class(['comments' => true, 'none' => $object->comments->count() === 0])>
        <div class="section-title">Коментари</div>
        @foreach ($object->comments as $comment)
            <x-public.single-comment :$comment :counter="$loop->iteration" />
        @endforeach
    </div>

    <div class="comment-form">
        <div class="section-title">Добавяне на коментар</div>
        <form method="POST" id="comment-form" action="{{ $object->getCommentsUrl() }}">
            {{ csrf_field() }}
            <input type="text" id="name" name="name" placeholder="*Име" />
            <textarea name="message" id="message" placeholder="*Коментар"></textarea>
            <div class="error-message none"></div>
            <div class="success-message center green none"></div>
            <input type="submit" value="Изпрати" />
        </form>
    </div>
</div>