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
Breadcrumbs::for('admin.poems.create', function ($trail, $user) {
    $trail->parent('admin.poems.index');
    $trail->push('Create', route('admin.poems.create'));
});

// Home > Works > Poems > [Poem]
Breadcrumbs::for('admin.poems.edit', function ($trail, $poem) {
    $trail->parent('admin.poems.index');
    $trail->push($poem->title, route('admin.poems.edit', $poem->id));
});