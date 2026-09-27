<?php

namespace PHPMaker2026\Project1;

return [
    /**
     * User levels
     *
     * @var array<int, string, string>
     * [0] int User level ID
     * [1] string User level name
     * [2] string User level hierarchy
     */
    'user.levels' => [
    ['-2', 'Anonymous', '']
],

    /**
     * User roles
     *
     * @var array<int, string>
     * [0] int User level ID
     * [1] string User role name
     */
    'user.roles' => [
    ['-1', 'ROLE_ADMIN'],
    ['', 'ROLE_UNDEFINED']
],

    /**
     * User level permissions
     *
     * @var array<string, int, int>
     * [0] string Project ID + Table name
     * [1] int User level ID
     * [2] int Permissions
     */
    'user.level.privs' => [
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}announcements', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}assignments', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}courses', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}departments', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}enrollments', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}mcq_answers', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}mcq_questions', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}notes', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}semester_courses', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}submissions', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}systemsettings', '-2', '0'],
    ['{D7120869-FBA8-461F-A089-E9CE85917D8A}users', '-2', '0']
],

    /**
     * Tables
     *
     * @var array<string, string, string, bool, string>
     * [0] string Table name
     * [1] string Table variable name
     * [2] string Table caption
     * [3] bool Allowed for update (for userpriv.php)
     * [4] string Project ID
     * [5] string URL (for AppController::index)
     */
    'user.level.tables' => [
    ['announcements', 'announcements', 'announcements', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'AnnouncementsList'],
    ['assignments', 'assignments', 'assignments', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'AssignmentsList'],
    ['courses', 'courses', 'courses', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'CoursesList'],
    ['departments', 'departments', 'departments', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'DepartmentsList'],
    ['enrollments', 'enrollments', 'enrollments', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'EnrollmentsList'],
    ['mcq_answers', 'mcq_answers', 'mcq answers', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'McqAnswersList'],
    ['mcq_questions', 'mcq_questions', 'mcq questions', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'McqQuestionsList'],
    ['notes', 'notes', 'notes', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'NotesList'],
    ['semester_courses', 'semester_courses', 'semester courses', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'SemesterCoursesList'],
    ['submissions', 'submissions', 'submissions', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'SubmissionsList'],
    ['systemsettings', 'systemsettings', 'systemsettings', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'SystemsettingsList'],
    ['users', 'users', 'users', true, '{D7120869-FBA8-461F-A089-E9CE85917D8A}', 'UsersList']
],
];
