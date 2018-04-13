DoctrineMongoODMDatafixture
============================

Module to generate fixture with Doctrine ODM

Instalation
------------

To install is quite simple, add the **composer.json:**

```
    "diegograssato/doctrine-odm-datafixture": "2.0"
```

Next step is to update the composer

```
  php composer.phar self-update
```

```
  php composer.phar install
```

Then add **DoctrineMongoODMDatafixture** to your **config/application.config.php**.

In **module.config.php** you should inform the folder where your fixtures, for example:

```
  'odm_fixtures' => [
     __DIR__.'/../src/Fixtures',
  ]

```

or group configurator

```
    'odm_fixtures' => [
        'groups' => [
            'default' => [
                __DIR__.'/../MyModule/src/MyModule/Fixtures/default',
            ],
            'production' => [
                 __DIR__.'/../MyModule/src/MyModule/Fixtures/production',
            ]
        ]
    ]
```

To rotate the fixture use the terminal command:

```
  vendor/bin/doctrine-odm-datafixture odm:fixtures:load
```

The odm:fixture:load command loads data fixtures from your bundles:

```
  vendor/bin/doctrine-module odm:fixtures:load
```

You can also optionally specify the path to fixtures with the **--fixtures** option:

```
  vendor/bin/doctrine-module odm:fixtures:load --fixture=/path/to/fixtures1 --fixture=/path/to/fixtures2
```

If you want to append the fixtures instead of flushing the database first you can use the **--append** option:

```
  vendor/bin/doctrine-module odm:fixture:load --fixture=/path/to/fixtures1 --fixture=/path/to/fixtures2 --append
```

You can also optionally specify the group configuration:

```
  vendor/bin/doctrine-module odm:fixtures:load --group production
```

You can also optionally list the fixtures:
```
  vendor/bin/doctrine-module odm:fixtures:list --group production
```

Finish!
