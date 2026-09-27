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
 * Announcements controller
 */
class AnnouncementsController extends BaseController
{
    // list
    #[Route('/AnnouncementsList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.announcements')]
    public function list(Request $request, AnnouncementsList $page): Response
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
    #[Route('/AnnouncementsAdd/{announcementId:announcement?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.announcements')]
    public function add(Request $request, AnnouncementsAdd $page, ?Entity\Announcement $announcement = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($announcement) {
            $page->CurrentRecord = $announcement;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/AnnouncementsView/{announcementId:announcement?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.announcements')]
    public function view(Request $request, AnnouncementsView $page, ?Entity\Announcement $announcement = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($announcement) {
            $page->CurrentRecord = $announcement;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/AnnouncementsEdit/{announcementId:announcement?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.announcements')]
    public function edit(Request $request, AnnouncementsEdit $page, ?Entity\Announcement $announcement = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($announcement) {
            $page->CurrentRecord = $announcement;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/AnnouncementsDelete/{announcementId:announcement?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.announcements')]
    public function delete(Request $request, AnnouncementsDelete $page, ?Entity\Announcement $announcement = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($announcement) {
            $page->CurrentRecord = $announcement;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
