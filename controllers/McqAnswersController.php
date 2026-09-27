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
 * McqAnswers controller
 */
class McqAnswersController extends BaseController
{
    // list
    #[Route('/McqAnswersList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.mcq_answers')]
    public function list(Request $request, McqAnswersList $page): Response
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
    #[Route('/McqAnswersAdd/{answerId:mcqAnswer?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.mcq_answers')]
    public function add(Request $request, McqAnswersAdd $page, ?Entity\McqAnswer $mcqAnswer = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqAnswer) {
            $page->CurrentRecord = $mcqAnswer;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/McqAnswersView/{answerId:mcqAnswer?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.mcq_answers')]
    public function view(Request $request, McqAnswersView $page, ?Entity\McqAnswer $mcqAnswer = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqAnswer) {
            $page->CurrentRecord = $mcqAnswer;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/McqAnswersEdit/{answerId:mcqAnswer?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.mcq_answers')]
    public function edit(Request $request, McqAnswersEdit $page, ?Entity\McqAnswer $mcqAnswer = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqAnswer) {
            $page->CurrentRecord = $mcqAnswer;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/McqAnswersDelete/{answerId:mcqAnswer?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.mcq_answers')]
    public function delete(Request $request, McqAnswersDelete $page, ?Entity\McqAnswer $mcqAnswer = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqAnswer) {
            $page->CurrentRecord = $mcqAnswer;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
