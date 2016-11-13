<?php

namespace DoctrineMongoODMDatafixture;

class Module
{
    const VERSION = '1.0';

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
