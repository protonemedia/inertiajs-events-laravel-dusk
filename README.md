# Inertia.js Events for Laravel Dusk

[![Latest Version on Packagist](https://img.shields.io/packagist/v/protonemedia/inertiajs-events-laravel-dusk.svg?style=flat-square)](https://packagist.org/packages/protonemedia/inertiajs-events-laravel-dusk)
![run-tests](https://github.com/protonemedia/inertiajs-events-laravel-dusk/workflows/run-tests/badge.svg)
[![Total Downloads](https://img.shields.io/packagist/dt/protonemedia/inertiajs-events-laravel-dusk.svg?style=flat-square)](https://packagist.org/packages/protonemedia/inertiajs-events-laravel-dusk)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/protonemedia/inertiajs-events-laravel-dusk)

## Requirements

* PHP 7.4+
* Vue
* Laravel 8.0 and 9.0

## Support

We proudly support the community by developing Laravel packages and giving them away for free. Keeping track of issues and pull requests takes time, but we're happy to help! If this package saves you time or if you're relying on it professionally, please consider [supporting the maintenance and development](https://github.com/sponsors/pascalbaljet).

## Blogpost

If you want to know more about the background of this package, please read the blogpost: [A package for Laravel Dusk to wait for Inertia.js events](https://protone.media/en/blog/a-package-for-laravel-dusk-to-wait-for-inertiajs-events)

## Installation

You can install the package via composer:

```bash
composer require protonemedia/inertiajs-events-laravel-dusk --dev
```

Add the `inertiaEventsCount` object to your main JavaScript file, somewhere above the creation of the Vue application instance.

```js
window.inertiaEventsCount = {
    navigateCount: 0,
    successCount: 0,
    errorCount: 0,
}
```

In the creation of the Vue application instance, use the `mounted` method to register the [event listeners](https://inertiajs.com/events).

```js
import { Inertia } from '@inertiajs/inertia'

new Vue({
  mounted() {
    Inertia.on('navigate', (event) => {
      window.inertiaEventsCount.navigateCount++;
    })

    Inertia.on('success', (event) => {
      window.inertiaEventsCount.successCount++;
    })

    Inertia.on('error', (event) => {
      window.inertiaEventsCount.errorCount++;
    })
  }
})
```

## Usage

This package provides three helper methods for your Laravel Dusk tests.

### Error
The `waitForInertiaError()` method may be used to wait until the [Error](https://inertiajs.com/events#error) event is fired. You can use it to assert against responses where validation errors are returned.

### Navigate
The `waitForInertiaNavigate()` method may be used to wait until the [Navigate](https://inertiajs.com/events#navigate) event is fired. You can use it to assert a user is redirected, for example, after submitting a form.

### Success
The `waitForInertiaSuccess()` method may be used to wait until the [Success](https://inertiajs.com/events#success) event is fired. This is great for testing forms that don't redirect after successfully submitting a form.

### Example test

```php
<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\DuskTestCase;

class ExampleTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A basic browser test example.
     *
     * @return void
     */
    public function it_can_store_a_user_and_redirect_back_to_the_index_route()
    {
        $this->browse(function ($browser) {
            $browser->loginAs(User::first())
                    ->visit(route('user.create'))
                    ->type('name', 'New User')
                    ->press('Submit')
                    ->waitForInertiaNavigate()
                    ->assertRouteIs('user.index')
                    ->assertSee('User Added!');
        });
    }
}
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other Laravel packages

* [`Laravel Analytics Event Tracking`](https://github.com/protonemedia/laravel-analytics-event-tracking): Laravel package to easily send events to Google Analytics.
* [`Laravel Blade On Demand`](https://github.com/protonemedia/laravel-blade-on-demand): Laravel package to compile Blade templates in memory.
* [`Laravel Cross Eloquent Search`](https://github.com/protonemedia/laravel-cross-eloquent-search): Laravel package to search through multiple Eloquent models.
* [`Laravel Eloquent Scope as Select`](https://github.com/protonemedia/laravel-eloquent-scope-as-select): Stop duplicating your Eloquent query scopes and constraints in PHP. This package lets you re-use your query scopes and constraints by adding them as a subquery.
* [`Laravel Eloquent Where Not`](https://github.com/protonemedia/laravel-eloquent-where-not): This Laravel package allows you to flip/invert an Eloquent scope, or really any query constraint.
* [`Laravel FFMpeg`](https://github.com/protonemedia/laravel-ffmpeg): This package provides an integration with FFmpeg for Laravel. The storage of the files is handled by Laravel's Filesystem.
* [`Laravel Form Components`](https://github.com/protonemedia/laravel-form-components): Blade components to rapidly build forms with Tailwind CSS Custom Forms and Bootstrap 4. Supports validation, model binding, default values, translations, includes default vendor styling and fully customizable!
* [`Laravel Paddle`](https://github.com/protonemedia/laravel-paddle): Paddle.com API integration for Laravel with support for webhooks/events.
* [`Laravel Verify New Email`](https://github.com/protonemedia/laravel-verify-new-email): This package adds support for verifying new email addresses: when a user updates its email address, it won't replace the old one until the new one is verified.
* [`Laravel WebDAV`](https://github.com/protonemedia/laravel-webdav): WebDAV driver for Laravel's Filesystem.

### Security

If you discover any security related issues, please email pascal@protone.media instead of using the issue tracker.

## Credits

- [Pascal Baljet](https://github.com/protonemedia)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/pascalbaljetmedia/inertiajs-events-laravel-dusk) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
