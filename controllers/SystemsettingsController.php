<?php

namespace PHPMaker2026\Project1;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\EventStreamResponse;
use Symfony\Component\HttpFoundation\StreamedJsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use PHPMaker2026\Project1\Db\Entity;

/**
 * Systemsettings controller
 */
class SystemsettingsController extends BaseController
{
    // list
    #[Route('/SystemsettingsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.systemsettings')]
    public function list(Request $request, SystemsettingsList $page): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Perform inline/grid actions
        if ($response = $page->action()) {
            return $response;
        }
        $page->TotalRecords = $page->listRecordCount();
        if (!$page->Records) {
            $page->Records = $page->loadRecords($page->StartRecord - 1, $page->DisplayRecords);
        }

        // Run page
        return $this->runPage($page);
    }

    // add
    #[Route('/SystemsettingsAdd/{id:systemsetting?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.systemsettings')]
    public function add(Request $request, SystemsettingsAdd $page, ?Entity\Systemsetting $systemsetting = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($systemsetting) {
            $page->CurrentRecord = $systemsetting;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/SystemsettingsView/{id:systemsetting?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.systemsettings')]
    public function view(Request $request, SystemsettingsView $page, ?Entity\Systemsetting $systemsetting = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($systemsetting) {
            $page->CurrentRecord = $systemsetting;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/SystemsettingsEdit/{id:systemsetting?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.systemsettings')]
    public function edit(Request $request, SystemsettingsEdit $page, ?Entity\Systemsetting $systemsetting = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($systemsetting) {
            $page->CurrentRecord = $systemsetting;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/SystemsettingsDelete/{id:systemsetting?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.systemsettings')]
    public function delete(Request $request, SystemsettingsDelete $page, ?Entity\Systemsetting $systemsetting = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($systemsetting) {
            $page->CurrentRecord = $systemsetting;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
