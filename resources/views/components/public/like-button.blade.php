<div class="social-wrapper">
    <div @class(['social' => true, 'wide' => isset($wide)])>
        <span @class(['like' => true, 'liked' => $object->isLikedByVisitor($visitor)]) data-url="{{ $object->getLikeUrl() }}" data-csrf="{{ csrf_token() }}">
            <span class="title">Харесай</span>
            <span class="heart"></span>
        </span>
    </div>
</div>