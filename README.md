Soflomo\Cache
===

Soflomo\Cache is a command line utility for cache management in Zend Framework
2. It enables you to manage your Zend\Cache instances from the commandline,
including:

 1. Printing a list of all available cache services
 2. Get the status (total space, available space) of all services
 3. Flush the complete cache instance
 4. Clear the cache by expired items
 5. Clear the cache by namespace
 6. Clear the cache by prefix
 7. Removing ZF2 application caches: the merged config and module map files


Installation
------------

Soflomo\Cache works with [Composer](https://getcomposer.org). Make sure you have
the composer.phar downloaded and you have a `composer.json` file at the root of
your project. To install it, add the following line into your `composer.json`
file:

```
"require": {
    "soflomo/cache": "~0.2"
}
```

After installation of the package, you need to enable the module by adding
`Soflomo\Cache` to your `application.config.php` file.

Requirements
------------

 1. Zend Framework 2: the `Zend\Cache` component
 2. Zend Framework 2: the `Zend\Mvc` component

Configuration
-------------

Soflomo\Cache scans automatically the list of available caches from the `caches`
key in the configuration. This key serves to [define your caches as service](http://framework.zend.com/manual/2.3/en/modules/zend.mvc.services.html#zend-cache-service-storagecacheabstractservicefactory).
If you want to use this key, you must register the cache as abstract factory:

```php
'service_manager' => array(
    'abstract_factories' => array(
        'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
    ),
),
```
With Soflomo\Cache you are not forced to use the cache abstract factory. The only
prerequisite is the cache must be available as a service. You can register your
own cache service using e.g. the service manager. For these self-defined caches
you cannot use the `--list` and `--status --all` commands and you are required
to supply the service name of the cache.

Usage
-----

Typing `php public/index.php` will show you a list of all available commands in
your application, including all Soflomo\Cache commands.

### List all available caches

```bash
php public/index.php cache cache --list
```

NB. You have to define your caches using the [abstract factory](#configuration).

### Get the cache status

```bash
php public/index.php cache --status
```

If you have only one cache defined, this cache is picked. For more than one
cache, you will get to choose which cache's status must be printed.

When your cache is defined without using the [abstract factory](#configuration),
you must supply the cache name:

```bash
php public/index.php cache --status MyCacheServiceName
```

All cache statuses are listed with `--all`:

```bash
php public/index.php cache --status --all
```

Note with the `--all` only caches from the abstract factory are listed.

### Flush the cache

```bash
php public/index.php cache --flush
```

If you have only one cache defined, this cache is picked. For more than one
cache, you will get to choose which cache's status must be printed.

When your cache is defined without using the [abstract factory](#configuration),
you must supply the cache name:

```bash
php public/index.php cache --flush MyCacheServiceName
```

All clearing operations, including `--flush` will require confirmation via the
prompt. Using the command non-interactively, you can use the `--force` or `-f`
flag:

```bash
php public/index.php cache --flush --force
```

### Clear all expired items

```bash
php public/index.php cache --clear --expired
```

If you have only one cache defined, this cache is picked. For more than one
cache, you will get to choose which cache's status must be printed.

When your cache is defined without using the [abstract factory](#configuration),
you must supply the cache name:

```bash
php public/index.php cache --clear MyCacheServiceName --expired
```

All clearing operations, including `--clear --expired` will require confirmation
via the prompt. Using the command non-interactively, you can use the `--force`
or `-f` flag:

```bash
php public/index.php cache --clear --expired --force
```

### Clear items by namespace

```bash
php public/index.php cache --clear --by-namespace=MyNamespace
```

If you have only one cache defined, this cache is picked. For more than one
cache, you will get to choose which cache's status must be printed.

When your cache is defined without using the [abstract factory](#configuration),
you must supply the cache name:

```bash
php public/index.php cache --clear MyCacheServiceName --by-namespace=MyNamespace
```

All clearing operations, including `--clear --by-namespace=MyNamespace` will
require confirmation via the prompt. Using the command non-interactively, you
can use the `--force` or `-f` flag:

```bash
php public/index.php cache --clear --force --by-namespace=MyNamespace
```

### Clear items by prefix

```bash
php public/index.php cache --clear --by-prefix=MyPrefix
```

If you have only one cache defined, this cache is picked. For more than one
cache, you will get to choose which cache's status must be printed.

When your cache is defined without using the [abstract factory](#configuration),
you must supply the cache name:

```bash
php public/index.php cache --clear MyCacheServiceName --by-prefix=MyPrefix
```

All clearing operations, including `--clear --by-prefix=MyPrefix` will
require confirmation via the prompt. Using the command non-interactively, you
can use the `--force` or `-f` flag:

```bash
php public/index.php cache --clear --force --by-prefix=MyPrefix
```

### Clear the ZF2 application's config cache

```bash
php public/index.php cache --clear-config
```

### Clear the ZF2 application's module map

```bash
php public/index.php cache --module-map
```

Clearing opcode cache
---------------------

At this moment, it is not possible to clear the opcode cache. The opcode caches
of CLI and PHP-FPM are not shared and as such, you cannot control the cache from
PHP-FPM with the CLI. 

Like the Symfony [ApcBundle](https://github.com/ornicar/ApcBundle) it is required
to create a file in the web directory and call that file via HTTP. Then the file
itself can clear the PHP-FPM opcode cache. You can track the progress of this 
feature in the issue #2.
