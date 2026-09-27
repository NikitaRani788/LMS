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
 * McqQuestions controller
 */
class McqQuestionsController extends BaseController
{
    // list
    #[Route('/McqQuestionsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.mcq_questions')]
    public function list(Request $request, McqQuestionsList $page): Response
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
    #[Route('/McqQuestionsAdd/{questionId:mcqQuestion?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.mcq_questions')]
    public function add(Request $request, McqQuestionsAdd $page, ?Entity\McqQuestion $mcqQuestion = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqQuestion) {
            $page->CurrentRecord = $mcqQuestion;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/McqQuestionsView/{questionId:mcqQuestion?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.mcq_questions')]
    public function view(Request $request, McqQuestionsView $page, ?Entity\McqQuestion $mcqQuestion = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqQuestion) {
            $page->CurrentRecord = $mcqQuestion;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/McqQuestionsEdit/{questionId:mcqQuestion?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.mcq_questions')]
    public function edit(Request $request, McqQuestionsEdit $page, ?Entity\McqQuestion $mcqQuestion = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqQuestion) {
            $page->CurrentRecord = $mcqQuestion;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/McqQuestionsDelete/{questionId:mcqQuestion?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.mcq_questions')]
    public function delete(Request $request, McqQuestionsDelete $page, ?Entity\McqQuestion $mcqQuestion = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($mcqQuestion) {
            $page->CurrentRecord = $mcqQuestion;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
