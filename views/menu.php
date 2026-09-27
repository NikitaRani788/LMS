<?php

namespace PHPMaker2026\Project1;

// Language
$language = Language();

// Navbar menu
$topMenu = new Menu("navbar", true, true);
echo $topMenu->toScript();

// Sidebar menu
$sideMenu = new Menu("menu", true, false);
$sideMenu->addMenuItem(1, "mi_announcements", $language->menuPhrase("1", "MenuText"), "AnnouncementsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(2, "mi_assignments", $language->menuPhrase("2", "MenuText"), "AssignmentsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(3, "mi_courses", $language->menuPhrase("3", "MenuText"), "CoursesList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(4, "mi_departments", $language->menuPhrase("4", "MenuText"), "DepartmentsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(5, "mi_enrollments", $language->menuPhrase("5", "MenuText"), "EnrollmentsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(6, "mi_mcq_answers", $language->menuPhrase("6", "MenuText"), "McqAnswersList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(7, "mi_mcq_questions", $language->menuPhrase("7", "MenuText"), "McqQuestionsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(8, "mi_notes", $language->menuPhrase("8", "MenuText"), "NotesList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(9, "mi_semester_courses", $language->menuPhrase("9", "MenuText"), "SemesterCoursesList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(10, "mi_submissions", $language->menuPhrase("10", "MenuText"), "SubmissionsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(11, "mi_systemsettings", $language->menuPhrase("11", "MenuText"), "SystemsettingsList", -1, "", true, false, false, "", "", false, true);
$sideMenu->addMenuItem(12, "mi_users", $language->menuPhrase("12", "MenuText"), "UsersList", -1, "", true, false, false, "", "", false, true);
echo $sideMenu->toScript();
