<?php

// Home
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push(__('global.home'), route('admin.home'));
});

// Home > Dashboard
Breadcrumbs::for('admin.dashboard', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.dashboard'));
});

// Home > [Static Page]
Breadcrumbs::for('admin.static_pages.edit', function ($trail, $staticPage) {
    $trail->parent('admin.home');
    $trail->push($staticPage->title);
});

// Home > Users
Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.users'), route('admin.users.index'));
});

// Home > Users > Create
Breadcrumbs::for('admin.users.create', function ($trail, $user) {
    $trail->parent('admin.users.index');
    $trail->push(__('global.create'), route('admin.users.create'));
});

// Home > Users > [User]
Breadcrumbs::for('admin.users.edit', function ($trail, $user) {
    $trail->parent('admin.users.index');
    $trail->push($user->name, route('admin.users.edit', $user->id));
});

// Home > Contact Messages
Breadcrumbs::for('admin.contact_messages.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.contact_messages'), route('admin.contact_messages.index'));
});

// Home > Contact Messages > [Message]
Breadcrumbs::for('admin.contact_messages.show', function ($trail, $message) {
    $trail->parent('admin.contact_messages.index');
    $trail->push("{$message->name} (#{$message->id})", route('admin.contact_messages.show', $message->id));
});

// Home > Works
Breadcrumbs::for('admin.works', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.works'), null);
});

// Home > Works > Poems
Breadcrumbs::for('admin.poems.index', function ($trail) {
    $trail->parent('admin.works');
    $trail->push(__('global.poems'), route('admin.poems.index'));
});

// Home > Works > Poems > Create
Breadcrumbs::for('admin.poems.create', function ($trail) {
    $trail->parent('admin.poems.index');
    $trail->push(__('global.create'), route('admin.poems.create'));
});

// Home > Works > Poems > [Poem]
Breadcrumbs::for('admin.poems.edit', function ($trail, $poem) {
    $trail->parent('admin.poems.index');
    $trail->push(strip_tags($poem->title), route('admin.poems.edit', $poem->id));
});

// Home > Works > Books
Breadcrumbs::for('admin.books.index', function ($trail) {
    $trail->parent('admin.works');
    $trail->push(__('global.books'), route('admin.books.index'));
});

// Home > Works > Books > Create
Breadcrumbs::for('admin.books.create', function ($trail) {
    $trail->parent('admin.books.index');
    $trail->push(__('global.create'), route('admin.books.create'));
});

// Home > Works > Books > [Book]
Breadcrumbs::for('admin.books.edit', function ($trail, $book) {
    $trail->parent('admin.books.index');
    $trail->push($book->title, route('admin.books.edit', $book->id));
});

// Home > Essays
Breadcrumbs::for('admin.essays.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.essays'), route('admin.essays.index'));
});

// Home > Essays > Create
Breadcrumbs::for('admin.essays.create', function ($trail) {
    $trail->parent('admin.essays.index');
    $trail->push(__('global.create'), route('admin.essays.create'));
});

// Home > Essays > [Essay]
Breadcrumbs::for('admin.essays.edit', function ($trail, $essay) {
    $trail->parent('admin.essays.index');
    $trail->push($essay->title, route('admin.essays.edit', $essay->id));
});

// Home > Press Articles
Breadcrumbs::for('admin.press_articles.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.press_articles'), route('admin.press_articles.index'));
});

// Home > Press Articles > Create
Breadcrumbs::for('admin.press_articles.create', function ($trail) {
    $trail->parent('admin.press_articles.index');
    $trail->push(__('global.create'), route('admin.press_articles.create'));
});

// Home > Press Articles > [Press Article]
Breadcrumbs::for('admin.press_articles.edit', function ($trail, $pressArticle) {
    $trail->parent('admin.press_articles.index');
    $trail->push($pressArticle->title, route('admin.press_articles.edit', $pressArticle->id));
});

// Home > Videos
Breadcrumbs::for('admin.videos.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.videos'), route('admin.videos.index'));
});

// Home > Videos > Create
Breadcrumbs::for('admin.videos.create', function ($trail) {
    $trail->parent('admin.videos.index');
    $trail->push(__('global.create'), route('admin.videos.create'));
});

// Home > Videos > [Video]
Breadcrumbs::for('admin.videos.edit', function ($trail, $video) {
    $trail->parent('admin.videos.index');
    $trail->push($video->title, route('admin.videos.edit', $video->id));
});

// Home > Gallery
Breadcrumbs::for('admin.gallery_images.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push(__('global.gallery'), route('admin.gallery_images.index'));
});

// Home > Gallery > Create
Breadcrumbs::for('admin.gallery_images.create', function ($trail) {
    $trail->parent('admin.gallery_images.index');
    $trail->push(__('global.create'), route('admin.gallery_images.create'));
});

// Home > Gallery > [Image]
Breadcrumbs::for('admin.gallery_images.edit', function ($trail, $image) {
    $trail->parent('admin.gallery_images.index');
    $trail->push($image->title ?? "#{$image->id}", route('admin.gallery_images.edit', $image->id));
});