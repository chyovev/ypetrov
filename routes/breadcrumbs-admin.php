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