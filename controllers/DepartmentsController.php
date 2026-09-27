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
 * Departments controller
 */
class DepartmentsController extends BaseController
{
    // list
    #[Route('/DepartmentsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.departments')]
    public function list(Request $request, DepartmentsList $page): Response
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
    #[Route('/DepartmentsAdd/{id:department?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.departments')]
    public function add(Request $request, DepartmentsAdd $page, ?Entity\Department $department = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($department) {
            $page->CurrentRecord = $department;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/DepartmentsView/{id:department?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.departments')]
    public function view(Request $request, DepartmentsView $page, ?Entity\Department $department = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($department) {
            $page->CurrentRecord = $department;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/DepartmentsEdit/{id:department?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.departments')]
    public function edit(Request $request, DepartmentsEdit $page, ?Entity\Department $department = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($department) {
            $page->CurrentRecord = $department;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/DepartmentsDelete/{id:department?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.departments')]
    public function delete(Request $request, DepartmentsDelete $page, ?Entity\Department $department = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($department) {
            $page->CurrentRecord = $department;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
