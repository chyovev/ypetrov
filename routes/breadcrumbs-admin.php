<?php

// Home
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->push('Home', route('admin.home'));
});

// Home > Users
Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.users.index'));
});

// Home > Users > Create
Breadcrumbs::for('admin.users.create', function ($trail, $user) {
    $trail->parent('admin.users.index');
    $trail->push('Create', route('admin.users.create'));
});

// Home > Users > [User]
Breadcrumbs::for('admin.users.edit', function ($trail, $user) {
    $trail->parent('admin.users.index');
    $trail->push($user->name, route('admin.users.edit', $user->id));
});

// Home > Contact Messages
Breadcrumbs::for('admin.contact_messages.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Contact Messages', route('admin.contact_messages.index'));
});

// Home > Contact Messages > [Message]
Breadcrumbs::for('admin.contact_messages.show', function ($trail, $message) {
    $trail->parent('admin.contact_messages.index');
    $trail->push("{$message->name} (#{$message->id})", route('admin.contact_messages.show', $message->id));
});

// Home > Works
Breadcrumbs::for('admin.works', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Works', null);
});

// Home > Works > Poems
Breadcrumbs::for('admin.poems.index', function ($trail) {
    $trail->parent('admin.works');
    $trail->push('Poems', route('admin.poems.index'));
});

// Home > Works > Poems > Create
Breadcrumbs::for('admin.poems.create', function ($trail) {
    $trail->parent('admin.poems.index');
    $trail->push('Create', route('admin.poems.create'));
});

// Home > Works > Poems > [Poem]
Breadcrumbs::for('admin.poems.edit', function ($trail, $poem) {
    $trail->parent('admin.poems.index');
    $trail->push($poem->title, route('admin.poems.edit', $poem->id));
});

// Home > Works > Books
Breadcrumbs::for('admin.books.index', function ($trail) {
    $trail->parent('admin.works');
    $trail->push('Books', route('admin.books.index'));
});

// Home > Works > Books > Create
Breadcrumbs::for('admin.books.create', function ($trail) {
    $trail->parent('admin.books.index');
    $trail->push('Create', route('admin.books.create'));
});

// Home > Works > Books > [Book]
Breadcrumbs::for('admin.books.edit', function ($trail, $book) {
    $trail->parent('admin.books.index');
    $trail->push($book->title, route('admin.books.edit', $book->id));
});

// Home > Essays
Breadcrumbs::for('admin.essays.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Essays', route('admin.essays.index'));
});

// Home > Essays > Create
Breadcrumbs::for('admin.essays.create', function ($trail) {
    $trail->parent('admin.essays.index');
    $trail->push('Create', route('admin.essays.create'));
});

// Home > Essays > [Essay]
Breadcrumbs::for('admin.essays.edit', function ($trail, $essay) {
    $trail->parent('admin.essays.index');
    $trail->push($essay->title, route('admin.essays.edit', $essay->id));
});

// Home > Press Articles
Breadcrumbs::for('admin.press_articles.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Press Articles', route('admin.press_articles.index'));
});

// Home > Press Articles > Create
Breadcrumbs::for('admin.press_articles.create', function ($trail) {
    $trail->parent('admin.press_articles.index');
    $trail->push('Create', route('admin.press_articles.create'));
});

// Home > Press Articles > [Press Article]
Breadcrumbs::for('admin.press_articles.edit', function ($trail, $pressArticle) {
    $trail->parent('admin.press_articles.index');
    $trail->push($pressArticle->title, route('admin.press_articles.edit', $pressArticle->id));
});

// Home > Videos
Breadcrumbs::for('admin.videos.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Videos', route('admin.videos.index'));
});

// Home > Videos > Create
Breadcrumbs::for('admin.videos.create', function ($trail) {
    $trail->parent('admin.videos.index');
    $trail->push('Create', route('admin.videos.create'));
});

// Home > Videos > [Video]
Breadcrumbs::for('admin.videos.edit', function ($trail, $video) {
    $trail->parent('admin.videos.index');
    $trail->push($video->title, route('admin.videos.edit', $video->id));
});