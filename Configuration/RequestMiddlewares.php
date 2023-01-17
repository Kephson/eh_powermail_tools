<?php

use EHAERER\EhPowermailTools\Middleware\ExportMiddleware;

return [
    'frontend' => [
        'ehaerer/eh-powermail-tools/ajax-middleware' => [
            'target' => ExportMiddleware::class,
            'before' => [
                'typo3/cms-adminpanel/renderer'
            ]
        ]
    ]
];
