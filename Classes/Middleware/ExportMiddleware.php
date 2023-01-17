<?php

namespace EHAERER\EhPowermailTools\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\NullResponse;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use EHAERER\EhPowermailTools\Controller\ExportController;

class ExportMiddleware implements MiddlewareInterface
{

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $expMailUid = $request->getParsedBody()['expMailuid'] ?? $request->getQueryParams()['expMailuid'] ?? null;

        if ($expMailUid === null) {
            return $handler->handle($request);
        }

        /* Remove any output produced until now */
        ob_clean();

        /** @var ExportController $exportController */
        $exportController = GeneralUtility::makeInstance(ExportController::class);
        return $exportController->processAjaxRequest($request) ?? new NullResponse();
    }
}
