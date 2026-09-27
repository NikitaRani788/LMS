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
 * SemesterCourses controller
 */
class SemesterCoursesController extends BaseController
{
    // list
    #[Route('/SemesterCoursesList', methods: ['GET', 'POST', 'OPTIONS'], name: 'list.semester_courses')]
    public function list(Request $request, SemesterCoursesList $page): Response
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
    #[Route('/SemesterCoursesAdd/{id:semesterCourse?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'add.semester_courses')]
    public function add(Request $request, SemesterCoursesAdd $page, ?Entity\SemesterCourse $semesterCourse = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($semesterCourse) {
            $page->CurrentRecord = $semesterCourse;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // view
    #[Route('/SemesterCoursesView/{id:semesterCourse?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'view.semester_courses')]
    public function view(Request $request, SemesterCoursesView $page, ?Entity\SemesterCourse $semesterCourse = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($semesterCourse) {
            $page->CurrentRecord = $semesterCourse;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // edit
    #[Route('/SemesterCoursesEdit/{id:semesterCourse?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'edit.semester_courses')]
    public function edit(Request $request, SemesterCoursesEdit $page, ?Entity\SemesterCourse $semesterCourse = null): Response
    {
        // Init page
        $page->init();

        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($semesterCourse) {
            $page->CurrentRecord = $semesterCourse;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }

    // delete
    #[Route('/SemesterCoursesDelete/{id:semesterCourse?}', methods: ['GET', 'POST', 'OPTIONS'], name: 'delete.semester_courses')]
    public function delete(Request $request, SemesterCoursesDelete $page, ?Entity\SemesterCourse $semesterCourse = null): Response
    {
        // Check resolved arguments
        $hasResolved = false;

        // Set current record
        if ($semesterCourse) {
            $page->CurrentRecord = $semesterCourse;
            $hasResolved = true;
        }

        // Run page
        return $this->runPage($page);
    }
}
