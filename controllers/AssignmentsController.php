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
 * Assignments controller
 */
class AssignmentsController extends BaseController
{
    // list
    #[Route('/AssignmentsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.assignments')]
    public function list(Request $request, AssignmentsList $page): Response
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
    #[Route('/AssignmentsAdd/{assignmentId:assignment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.assignments')]
    public function add(Request $request, AssignmentsAdd $page, ?Entity\Assignment $assignment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($assignment) {
            $page->CurrentRecord = $assignment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/AssignmentsView/{assignmentId:assignment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.assignments')]
    public function view(Request $request, AssignmentsView $page, ?Entity\Assignment $assignment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($assignment) {
            $page->CurrentRecord = $assignment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/AssignmentsEdit/{assignmentId:assignment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.assignments')]
    public function edit(Request $request, AssignmentsEdit $page, ?Entity\Assignment $assignment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($assignment) {
            $page->CurrentRecord = $assignment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/AssignmentsDelete/{assignmentId:assignment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.assignments')]
    public function delete(Request $request, AssignmentsDelete $page, ?Entity\Assignment $assignment = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($assignment) {
            $page->CurrentRecord = $assignment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
