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
 * Submissions controller
 */
class SubmissionsController extends BaseController
{
    // list
    #[Route('/SubmissionsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.submissions')]
    public function list(Request $request, SubmissionsList $page): Response
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
    #[Route('/SubmissionsAdd/{submissionId:submission?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.submissions')]
    public function add(Request $request, SubmissionsAdd $page, ?Entity\Submission $submission = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($submission) {
            $page->CurrentRecord = $submission;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/SubmissionsView/{submissionId:submission?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.submissions')]
    public function view(Request $request, SubmissionsView $page, ?Entity\Submission $submission = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($submission) {
            $page->CurrentRecord = $submission;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/SubmissionsEdit/{submissionId:submission?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.submissions')]
    public function edit(Request $request, SubmissionsEdit $page, ?Entity\Submission $submission = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($submission) {
            $page->CurrentRecord = $submission;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/SubmissionsDelete/{submissionId:submission?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.submissions')]
    public function delete(Request $request, SubmissionsDelete $page, ?Entity\Submission $submission = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($submission) {
            $page->CurrentRecord = $submission;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
