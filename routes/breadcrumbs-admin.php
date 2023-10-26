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
