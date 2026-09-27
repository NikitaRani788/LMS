<?php

namespace PHPMaker2026\Project1;
?>
<?php if (!$Page->isExport()) { ?>
<div class="btn-toolbar ew-toolbar">
<?php $Page->ExportOptions->render("body") ?>
<?php $Page->OtherOptions->render("body") ?>
</div>
<?php } ?>
<?= $Page->getPageHeader() ?>
<?= $Page->getHtmlMessage() ?>
<main class="view">
<form name="fAssignmentsview" id="fAssignmentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { assignments: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fAssignmentsview;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fAssignmentsview")
        .setPageId("view")
        .build();
    window[form.id] = form;
    currentForm = form;
    ew.emit(form.id);
});
</script>
<script<?= Nonce() ?>>
ew.on("head", function () {
    // Write your table-specific client script here, no need to add script tags.
});
</script>
<?php } ?>
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="assignments">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
    <tr id="r_assignment_id"<?= $Page->assignment_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_assignment_id"><?= $Page->assignment_id->caption() ?></span></td>
        <td data-name="assignment_id"<?= $Page->assignment_id->cellAttributes() ?>>
<span id="el_assignments_assignment_id">
<span<?= $Page->assignment_id->viewAttributes() ?>>
<?= $Page->assignment_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
    <tr id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_course_id"><?= $Page->course_id->caption() ?></span></td>
        <td data-name="course_id"<?= $Page->course_id->cellAttributes() ?>>
<span id="el_assignments_course_id">
<span<?= $Page->course_id->viewAttributes() ?>>
<?= $Page->course_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assigned_by->Visible) { // assigned_by ?>
    <tr id="r_assigned_by"<?= $Page->assigned_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_assigned_by"><?= $Page->assigned_by->caption() ?></span></td>
        <td data-name="assigned_by"<?= $Page->assigned_by->cellAttributes() ?>>
<span id="el_assignments_assigned_by">
<span<?= $Page->assigned_by->viewAttributes() ?>>
<?= $Page->assigned_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
    <tr id="r_title"<?= $Page->title->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_title"><?= $Page->title->caption() ?></span></td>
        <td data-name="title"<?= $Page->title->cellAttributes() ?>>
<span id="el_assignments_title">
<span<?= $Page->title->viewAttributes() ?>>
<?= $Page->title->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <tr id="r_description"<?= $Page->description->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_description"><?= $Page->description->caption() ?></span></td>
        <td data-name="description"<?= $Page->description->cellAttributes() ?>>
<span id="el_assignments_description">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assignment_type->Visible) { // assignment_type ?>
    <tr id="r_assignment_type"<?= $Page->assignment_type->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_assignment_type"><?= $Page->assignment_type->caption() ?></span></td>
        <td data-name="assignment_type"<?= $Page->assignment_type->cellAttributes() ?>>
<span id="el_assignments_assignment_type">
<span<?= $Page->assignment_type->viewAttributes() ?>>
<?= $Page->assignment_type->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->due_date->Visible) { // due_date ?>
    <tr id="r_due_date"<?= $Page->due_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_due_date"><?= $Page->due_date->caption() ?></span></td>
        <td data-name="due_date"<?= $Page->due_date->cellAttributes() ?>>
<span id="el_assignments_due_date">
<span<?= $Page->due_date->viewAttributes() ?>>
<?= $Page->due_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->max_marks->Visible) { // max_marks ?>
    <tr id="r_max_marks"<?= $Page->max_marks->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_max_marks"><?= $Page->max_marks->caption() ?></span></td>
        <td data-name="max_marks"<?= $Page->max_marks->cellAttributes() ?>>
<span id="el_assignments_max_marks">
<span<?= $Page->max_marks->viewAttributes() ?>>
<?= $Page->max_marks->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->attachment_path->Visible) { // attachment_path ?>
    <tr id="r_attachment_path"<?= $Page->attachment_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_attachment_path"><?= $Page->attachment_path->caption() ?></span></td>
        <td data-name="attachment_path"<?= $Page->attachment_path->cellAttributes() ?>>
<span id="el_assignments_attachment_path">
<span<?= $Page->attachment_path->viewAttributes() ?>>
<?= $Page->attachment_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <tr id="r_status"<?= $Page->status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_status"><?= $Page->status->caption() ?></span></td>
        <td data-name="status"<?= $Page->status->cellAttributes() ?>>
<span id="el_assignments_status">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_assignments_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_assignments_created_at">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
</table>
</form>
</main>
<?= $Page->getPageFooter() ?>
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
<?php } ?>
