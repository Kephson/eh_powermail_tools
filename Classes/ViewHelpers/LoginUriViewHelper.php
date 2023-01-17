<?php

namespace EHAERER\EhPowermailTools\ViewHelpers;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper generate URL with username and password
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class LoginUriViewHelper extends AbstractViewHelper
{

    /**
     * register additional arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('extKey', 'string', 'The extension key', false);
        $this->registerArgument('path', 'string', 'The path to the file', false);
    }

    /**
     * Returns replaced string
     *
     * @return string
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function render(): string
    {
        $uri = '';
        $extKey = $this->arguments['extKey'];
        $path = $this->arguments['path'];
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get($extKey);
        $uri .= parse_url(GeneralUtility::getIndpEnv('TYPO3_REQUEST_URL'), PHP_URL_SCHEME);
        $uri .= '://';
        if (isset($extConf['htaccess_username'], $extConf['htaccess_password'])) {
            $uri .= $extConf['htaccess_username'] . ':' . $extConf['htaccess_password'] . '@';
        }
        $uri .= GeneralUtility::getIndpEnv('HTTP_HOST') . '/' . rtrim(GeneralUtility::getIndpEnv('TYPO3_SITE_PATH'),
                '/') . $path;
        return $uri;
    }
}
