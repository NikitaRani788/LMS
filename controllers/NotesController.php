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
 * Notes controller
 */
class NotesController extends BaseController
{
    // list
    #[Route('/NotesList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.notes')]
    public function list(Request $request, NotesList $page): Response
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
    #[Route('/NotesAdd/{noteId:note?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.notes')]
    public function add(Request $request, NotesAdd $page, ?Entity\Note $note = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($note) {
            $page->CurrentRecord = $note;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/NotesView/{noteId:note?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.notes')]
    public function view(Request $request, NotesView $page, ?Entity\Note $note = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($note) {
            $page->CurrentRecord = $note;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/NotesEdit/{noteId:note?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.notes')]
    public function edit(Request $request, NotesEdit $page, ?Entity\Note $note = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($note) {
            $page->CurrentRecord = $note;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/NotesDelete/{noteId:note?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.notes')]
    public function delete(Request $request, NotesDelete $page, ?Entity\Note $note = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($note) {
            $page->CurrentRecord = $note;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
