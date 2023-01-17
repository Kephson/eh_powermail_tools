<?php
/** @noinspection PhpFullyQualifiedNameUsageInspection */
defined('TYPO3_MODE') || die();

(static function ($extKey = 'eh_powermail_tools') {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'EHAERER.' . $extKey, 'Rpmex',
        [
            'Export' => 'render',
        ],
        [
            'Export' => 'render',
        ]
    );
})();
