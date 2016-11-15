DoctrineMongoODMDatafixture
============================

Module to generate fixture with Doctrine ODM

Installation
------------

To install is quite simple, add the **composer.json:**

```
    "zdeveloper/doctrine-odm-datafixture": "dev-master"
```

Next step is to update the composer

```
    php composer.phar self-update
```

```
    php composer.phar install
```

In module.config.php you should inform the folder where your fixtures, for example:

```
    'odm-data-fixtures' => array(
        __NAMESPACE__.'_fixtures' => __DIR__.'/../src/Fixtures',
    ),

```

To rotate the fixture use the terminal command:

```
    vendor/bin/odm-data-fixture odm:fixture:load
```
