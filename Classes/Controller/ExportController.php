<?php

namespace EHAERER\EhPowermailTools\Controller;

/* * *************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2020-2023 Ephraim Härer <mail@ephra.im>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

use In2code\Powermail\Domain\Model\Mail;
use In2code\Powermail\Domain\Repository\MailRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationExtensionNotConfiguredException;
use TYPO3\CMS\Core\Configuration\Exception\ExtensionConfigurationPathDoesNotExistException;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Context\Exception\AspectNotFoundException;
use TYPO3\CMS\Core\Http\HtmlResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Core\Context\Context;
use TYPO3\CMS\Fluid\View\StandaloneView;
use UnexpectedValueException;

/**
 * ExportController
 */
class ExportController extends ActionController
{

    /**
     * @var string $extKey Extension key
     */
    protected $extKey = 'eh_powermail_tools';

    /**
     * @var int $mailUid Mail uid
     */
    protected $mailUid;

    /**
     * @var array
     */
    protected $extConf = [];

    /**
     * @var array
     */
    protected $paramData = [];

    /**
     * persistenceManager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * @var \In2code\Powermail\Domain\Repository\MailRepository
     * @inject
     */
    protected $mailRepository;

    /**
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     */
    protected function initializeData()
    {
        $this->persistenceManager = GeneralUtility::makeInstance(PersistenceManager::class);
        $this->mailRepository = GeneralUtility::makeInstance(MailRepository::class);
        $this->extConf = GeneralUtility::makeInstance(ExtensionConfiguration::class)
            ->get($this->extKey);
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws ExtensionConfigurationExtensionNotConfiguredException
     * @throws ExtensionConfigurationPathDoesNotExistException
     * @throws AspectNotFoundException
     */
    public function processAjaxRequest(ServerRequestInterface $request): ResponseInterface
    {
        $this->initializeData();
        $this->paramData = $request->getQueryParams();
        $htmlOutput = '<p>' . 'You are not allowed to access this data.' . '</p>';

        if ((int)$this->paramData['expMailuid'] > 0) {
            $this->mailUid = (int)$this->paramData['expMailuid'];
            /** @var $context Context */
            $context = GeneralUtility::makeInstance(Context::class);
            /* Checking if a user is logged in */
            $beUserIsLoggedIn = $context->getPropertyFromAspect('backend.user', 'isLoggedIn');
            /* show it only for logged in backend users */
            if ($beUserIsLoggedIn) {
                $mail = $this->mailRepository->findByUid($this->mailUid);
                $htmlOutput = $this->renderStandaloneView($mail);
            }
        } else {
            throw new UnexpectedValueException('Not all needed parameters found!', 15434184824312);
        }

        return new HtmlResponse($htmlOutput);
    }

    /**
     * @param Mail $mail
     * @param string $template
     * @return string
     */
    public function renderStandaloneView($mail = null, $template = 'Render'): string
    {
        /* load partial paths info from TypoScript */
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setFormat('html');
        $view->setTemplateRootPaths(
            [0 => 'EXT:eh_powermail_tools/Resources/Private/Templates/']
        );
        $view->setPartialRootPaths(
            [0 => 'EXT:eh_powermail_tools/Resources/Private/Partials/']
        );
        $view->setLayoutRootPaths(
            [0 => 'EXT:eh_powermail_tools/Resources/Private/Layouts/']
        );
        $resourcesPath = 'EXT:eh_powermail_tools/Resources/';
        $view->setTemplatePathAndFilename($resourcesPath . 'Private/Templates/Export/' . $template . '.html');
        $view->assignMultiple([
            'mail' => $mail,
            'extConf' => $this->extConf,
        ]);
        return $view->render();
    }

    /**
     * action render
     *
     * @return string The rendered view.
     * @throws
     */
    public function renderAction()
    {
        /** @var @var $context \TYPO3\CMS\Core\Context\Context */
        $context = GeneralUtility::makeInstance(Context::class);
        /* Checking if a user is logged in */
        $beUserIsLoggedIn = $context->getPropertyFromAspect('backend.user', 'isLoggedIn');
        /* show it only for logged in backend users */
        if ($beUserIsLoggedIn) {
            if ($this->request->hasArgument('mailuid')) {
                $mailUid = (int)$this->request->getArgument('mailuid');
                $mail = $this->mailRepository->findByUid($mailUid);
                $this->view->assign("mail", $mail);
            }
        }
    }
}
