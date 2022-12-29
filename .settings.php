<?php

use Baarlord\OfficeMap\Entity\Office;
use Baarlord\OfficeMap\Service\OfficeService;
use Bitrix\Main\DI\ServiceLocator;

return [
    'services' => [
        'value' => [
            'baarlord.officemap.service.OfficeService' => [
                'className' => OfficeService::class,
                'constructorParams' => [
                    ServiceLocator::getInstance()
                        ->get('baarlord.officemap.doctrineEntityManager'),
                    ServiceLocator::getInstance()
                        ->get('baarlord.officemap.doctrineEntityManager')
                        ->getRepository(Office::class)
                ],
            ],
        ],
        'readonly' => true,
    ],
];
