ZFLabsODMFixture
================

Module to generate fixture with Doctrine ODM

Installation
------------

To install is quite simple, add the **composer.json:**

```
    "zflabs/odm-fixture", "dev-master"
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
    'zflabs-odm-fixture' => array(
        __NAMESPACE__.'_fixture' => __DIR__ . '/../src/'.__NAMESPACE__.'/Fixture',
    ),
```

To rotate the fixture use the terminal command:

```
    vendor/bin/ZFLabs odm-fixture load
```
