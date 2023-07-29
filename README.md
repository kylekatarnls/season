# Season

[![Latest Stable Version](https://poser.pugx.org/cmixin/season/v/stable.png)](https://packagist.org/packages/cmixin/season)
[![GitHub Actions](https://github.com/kylekatarnls/business-time/workflows/Tests/badge.svg)](https://github.com/kylekatarnls/season/actions)
[![Code Climate](https://codeclimate.com/github/kylekatarnls/season/badges/gpa.svg)](https://codeclimate.com/github/kylekatarnls/season)
[![Test Coverage](https://codeclimate.com/github/kylekatarnls/season/badges/coverage.svg)](https://codeclimate.com/github/kylekatarnls/season/coverage)
[![StyleCI](https://styleci.io/repos/655239407/shield?branch=main&style=flat)](https://styleci.io/repos/655239407)

`DateTime` modifiers such as `startOfSeason`, `isInSummer`

- `Season` can be used as a service which can work with any `DateTime` or `DateTimeImmutable` object or date strings
  (which includes any subclass such as `Carbon` or `Chronos`).
- Or it can be used as a mixin to call the methods directly on `Carbon` objects.
- Mixin get automatically enabled on Laravel if auto-discovery is on.

# How to use

## The simple way
```php
(new \Season\Season)->isInSummer('2022-06-25')
(new \Season\Season)->isInSummer(new DateTimeImmutable('2022-06-25'))
(new \Season\Season)->isInSummer(Carbon::now())
```

Methods are available from the class `\Season\Season` which is cheap to create,
so you can just call methods from a new class everytime.

As a good practice, it's recommended you import the class with `use Season\Season;`
at the beginning of the file:
```php
use Season\Season;

(new Season)->isInSummer('2022-06-25');
(new Season)->isInSummer(new DateTimeImmutable('2022-06-25'));
```

And also to keep the same instance to re-use multiple times:
```php
use Season\Season;

$season = new Season();
$season->isInSummer('2022-06-25');
$season->isInSummer(new DateTimeImmutable('2022-06-25'));
```

## As a service

You can use dependency injection with your framework:
```php
use Season\Season;
use Psr\Clock\ClockInterface;

class ProductController
{
    public function new(Season $season, ClockInterface $clock)
    {
        $seasonName = $season->getSeason($clock->now())->getName();
    }
}
```

With Laravel it will be provided by default.

With Symfony, you'll have to register `\Season\Season` as a service and
so edit **config/services.yaml** the following way:
```yaml
services:
    _defaults:
        # ensure you get the tag 'controller.service_arguments'
        # if you need services to be available as controller
        # methods arguments:
        tags: [ 'controller.service_arguments' ]

    # then add the class name as a service
    Season\Season:
```

Learn more from Symfony documentation:
[Configuring Services in the Container](https://symfony.com/doc/current/service_container.html#creating-configuring-services-in-the-container)

## As Carbon methods (mixin)

```php
use Carbon\Carbon;
use Cmixin\SeasonMixin;

// On Laravel, the mixin will be loaded by default.
// On other applications/framework, you can enable it with:
Carbon::mixin(SeasonMixin::class);

Carbon::parse('2022-06-25')->isInSummer();
```
You can use mixin on `CarbonImmutable`, `Carbon` or any of
their subclasses.

# Configuration

## Disable mixin in Laravel

If you use Laravel but don't want to enable `Season` mixin
globally for `Carbon`, you can remove it from auto-discovery using:

```json
"extra": {
    "laravel": {
        "dont-discover": [
            "cmixin/season"
        ]
    }
},
```

## Customize days

By default, `Season` is created with the following config:
```php
[
    3  => 20, // spring
    6  => 21, // summer
    9  => 22, // fall
    12 => 21, // winter
]
```
mapping the month (as key) with the day (as value) for each season
start.

But you can pass a custom config with other days as long as the keys
remain.

```php
use Season\Season;

$season = new Season([
    3  => 21, // spring
    6  => 21, // summer
    9  => 21, // fall
    12 => 21, // winter
]);
```

In Laravel, you can set the config in **config/season.php** which will
apply to both the mixin and the service:

```php
<?php return [
    3  => 21, // spring
    6  => 21, // summer
    9  => 21, // fall
    12 => 21, // winter
];
```

When using `Carbon` mixin alone, you can still call `setSeasonConfig()` to
change the config globally:

```php
Carbon::setSeasonConfig([
    3  => 21, // spring
    6  => 21, // summer
    9  => 21, // fall
    12 => 21, // winter
]);
```

Or apply a config on specific call:
```php
echo Carbon::now()->getSeason([
    3  => 21, // spring
    6  => 21, // summer
    9  => 21, // fall
    12 => 21, // winter
])->getName();
```

With Symfony you can edit **config/services.yaml** to configure the service:
```yaml
services:
    Season\Season:
        arguments:
            $config:
                3: 20
                6: 12
                9: 22
                12: 21
```

# Thanks

<a href="https://tidelift.com/subscription/pkg/packagist-nesbot-carbon?utm_source=packagist-nesbot-carbon&utm_medium=referral&utm_campaign=readme" target="_blank"><img src="https://carbon.nesbot.com/tidelift-brand.png" width="256" height="64"></a>

<a href="https://www.jetbrains.com/phpstorm/" target="_blank"><img src="http://jet-brains.selfbuild.fr/PhpStorm-text.svg" width="256" height="64"></a>