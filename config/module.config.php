<?php

namespace Laminas\ApiTools\Doctrine\QueryProvider;

return [
    'api-tools-doctrine-query-provider' => [
        'abstract_factories' => [
            Service\QueryProvider\ORM\AbstractFactory::class,
            Service\QueryProvider\ODM\AbstractFactory::class,
        ],
    ],
    'api-tools-doctrine-query-create-filter' => [
        'abstract_factories' => [
            Service\QueryCreateFilter\ORM\AbstractFactory::class,
            Service\QueryCreateFilter\ODM\AbstractFactory::class,
        ],
    ],
];
