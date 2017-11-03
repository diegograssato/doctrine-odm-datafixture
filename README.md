DoctrineMongoODMDatafixture
============================

Module to generate fixture with Doctrine ODM

Installation
------------

To install is quite simple, add the **composer.json:**

```
    "diegograssato/doctrine-odm-datafixture": "1.*"
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
      __NAMESPACE__.'_fixtures' => __DIR__.'/../src/Fixtures',
  ]

```

or group configurator

```
'odm_fixturess' => [
    'groups' => [
        'default' => [
            __NAMESPACE__.'_fixtures' => __DIR__.'/../src/Fixtures/default',
        ],
        'production' => [
            __NAMESPACE__.'_fixtures' => __DIR__.'/../src/Fixtures/prod',
        ]
    ]
]
```

To rotate the fixture use the terminal command:

```
  vendor/bin/doctrine-odm-datafixture odm:fixture:load
```

The odm:fixture:load command loads data fixtures from your bundles:

```
  vendor/bin/doctrine-module odm:fixture:load
```

You can also optionally specify the path to fixtures with the **--fixtures** option:

```
  vendor/bin/doctrine-module odm:fixture:load --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2
```

If you want to append the fixtures instead of flushing the database first you can use the **--append** option:

```
  vendor/bin/doctrine-module odm:fixture:load --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2 --append
```

You can also optionally specify the group configuration:

```
  vendor/bin/doctrine-module odm:fixture:load --group production
```

Finish!
