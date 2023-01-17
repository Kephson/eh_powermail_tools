<?php

namespace EHAERER\EhPowermailTools\ViewHelpers\Ext;

use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Site\Entity\Site;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * View helper to get a key from extension configuration
 *
 * @package TYPO3
 * @subpackage Fluid
 * @version
 */
class ExtConfigViewHelper extends AbstractViewHelper
{

    /**
     * register additional arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();

        $this->registerArgument('extKey', 'string', 'The extension key', false);
        $this->registerArgument('configKey', 'string', 'The extension configuration key', false);
    }

    /**
     * Returns replaced string
     *
     * @return string
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    public function render()
    {
        $extKey = $this->arguments['extKey'];
        $extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get($extKey);
        $configKey = $this->arguments['configKey'];
        if ($configKey === 'full_site_url') {
            /** @var Site $site */
            $site = $GLOBALS['TYPO3_REQUEST']->getAttribute('site');
            $baseUri = $site->getBase();
            if (empty($baseUri) && isset($extConf[$configKey])) {
                return $extConf[$configKey];
            } else {
                return $baseUri;
            }
        } elseif (isset($extConf[$configKey])) {
            return $extConf[$configKey];
        }
        return '';
    }
}
