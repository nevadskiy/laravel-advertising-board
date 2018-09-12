<?php

use App\Entity\Adverts\Attribute;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;

/**
 * Home
 */
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});

/**
 * Register
 */
Breadcrumbs::for('login', function ($trail) {
    $trail->parent('home');
    $trail->push('Login', route('login'));
});

Breadcrumbs::for('register', function ($trail) {
    $trail->parent('home');
    $trail->push('Register', route('register'));
});

Breadcrumbs::for('password.request', function ($trail) {
    $trail->parent('login');
    $trail->push('Forgot password', route('password.request'));
});

Breadcrumbs::for('password.reset', function ($trail) {
    $trail->parent('login');
    $trail->push('Reset password', route('password.reset'));
});

Breadcrumbs::for('cabinet.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Cabinet', route('cabinet.home'));
});

Breadcrumbs::for('cabinet.profile.home', function ($trail) {
    $trail->parent('cabinet.home');
    $trail->push('Profile', route('cabinet.profile.home'));
});

Breadcrumbs::for('cabinet.profile.edit', function ($trail) {
    $trail->parent('cabinet.profile.home');
    $trail->push('Edit', route('cabinet.profile.edit'));
});

Breadcrumbs::for('cabinet.profile.phone', function ($trail) {
    $trail->parent('cabinet.profile.home');
    $trail->push('Phone', route('cabinet.profile.phone'));
});

/**
 * Cabinet Adverts
 */
Breadcrumbs::for('cabinet.adverts.index', function ($trail) {
    $trail->parent('cabinet.home');
    $trail->push('Adverts', route('cabinet.adverts.index'));
});

/**
 * Admin
 */
Breadcrumbs::for('admin.home', function ($trail) {
    $trail->parent('home');
    $trail->push('Admin', route('admin.home'));
});

/**
 * Admin Users
 */
Breadcrumbs::for('admin.users.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Users', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.create', function ($trail) {
    $trail->parent('admin.users.index');
    $trail->push('Create', route('admin.users.create'));
});

Breadcrumbs::for('admin.users.show', function ($trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push($user->name, route('admin.users.show', $user));
});

Breadcrumbs::for('admin.users.edit', function ($trail, User $user) {
    $trail->parent('admin.users.show', $user);
    $trail->push('Edit', route('admin.users.edit', $user));
});

/**
 * Admin Regions
 */
Breadcrumbs::for('admin.regions.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Regions', route('admin.regions.index'));
});

Breadcrumbs::for('admin.regions.create', function ($trail) {
    $trail->parent('admin.regions.index');
    $trail->push('Create', route('admin.regions.create'));
});

Breadcrumbs::for('admin.regions.show', function ($trail, Region $region) {
    if ($parent = $region->parent) {
        $trail->parent('admin.regions.show', $parent);
    } else {
        $trail->parent('admin.regions.index');
    }
    $trail->push($region->name, route('admin.regions.show', $region));
});

Breadcrumbs::for('admin.regions.edit', function ($trail, Region $region) {
    $trail->parent('admin.regions.show', $region);
    $trail->push('Edit', route('admin.regions.edit', $region));
});

/**
 * Admin Categories
 */
Breadcrumbs::for('admin.adverts.categories.index', function ($trail) {
    $trail->parent('admin.home');
    $trail->push('Categories', route('admin.adverts.categories.index'));
});

Breadcrumbs::for('admin.adverts.categories.create', function ($trail) {
    $trail->parent('admin.adverts.categories.index');
    $trail->push('Create', route('admin.adverts.categories.create'));
});

Breadcrumbs::for('admin.adverts.categories.show', function ($trail, Category $category) {
    if ($parent = $category->parent) {
        $trail->parent('admin.adverts.categories.show', $parent);
    } else {
        $trail->parent('admin.adverts.categories.index');
    }
    $trail->push($category->name, route('admin.adverts.categories.show', $category));
});

Breadcrumbs::for('admin.adverts.categories.edit', function ($trail, Category $category) {
    $trail->parent('admin.adverts.categories.show', $category);
    $trail->push('Edit', route('admin.adverts.categories.edit', $category));
});

/**
 * Admin Categories Attributes
 */
Breadcrumbs::for('admin.adverts.categories.attributes.create', function ($trail, Category $category) {
    $trail->parent('admin.adverts.categories.show', $category);
    $trail->push('Create', route('admin.adverts.categories.attributes.create', $category));
});

Breadcrumbs::for('admin.adverts.categories.attributes.show', function ($trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.adverts.categories.show', $category);
    $trail->push($attribute->name, route('admin.adverts.categories.attributes.show', [$category, $attribute]));
});

Breadcrumbs::for('admin.adverts.categories.attributes.edit', function ($trail, Category $category, Attribute $attribute) {
    $trail->parent('admin.adverts.categories.attributes.show', $category, $attribute);
    $trail->push('Edit', route('admin.adverts.categories.attributes.edit', [$category, $attribute]));
});
