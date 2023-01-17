<?php /** @noinspection PhpUndefinedVariableInspection */
/* * *************************************************************
 * Extension Manager/Repository config file for ext: "eh_powermail_tools"
 *
 *
 * Manual updates:
 * Only the data in the array - anything else is removed by next write.
 * "version" and "dependencies" must not be touched!
 * ************************************************************* */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Additional tools for Powermail',
    'description' => 'Adds some tools e.g. to export data from Powermail backend module with one click.',
    'category' => 'plugin',
    'author' => 'Ephraim Härer',
    'author_email' => 'mail@ephra.im',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-10.4.99',
            'powermail' => '8.0.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
