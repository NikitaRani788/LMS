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
 * Enrollments controller
 */
class EnrollmentsController extends BaseController
{
    // list
    #[Route('/EnrollmentsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.enrollments')]
    public function list(Request $request, EnrollmentsList $page): Response
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
    #[Route('/EnrollmentsAdd/{enrollmentId:enrollment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.enrollments')]
    public function add(Request $request, EnrollmentsAdd $page, ?Entity\Enrollment $enrollment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($enrollment) {
            $page->CurrentRecord = $enrollment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/EnrollmentsView/{enrollmentId:enrollment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.enrollments')]
    public function view(Request $request, EnrollmentsView $page, ?Entity\Enrollment $enrollment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($enrollment) {
            $page->CurrentRecord = $enrollment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/EnrollmentsEdit/{enrollmentId:enrollment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.enrollments')]
    public function edit(Request $request, EnrollmentsEdit $page, ?Entity\Enrollment $enrollment = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($enrollment) {
            $page->CurrentRecord = $enrollment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/EnrollmentsDelete/{enrollmentId:enrollment?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.enrollments')]
    public function delete(Request $request, EnrollmentsDelete $page, ?Entity\Enrollment $enrollment = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($enrollment) {
            $page->CurrentRecord = $enrollment;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
