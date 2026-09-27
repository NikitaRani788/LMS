<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { assignments: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fAssignmentsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fAssignmentsdelete")
        .setPageId("delete")
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
<?= $Page->getPageHeader() ?>
<?= $Page->getHtmlMessage() ?>
<form name="fAssignmentsdelete" id="fAssignmentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="assignments">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
        <th class="<?= $Page->assignment_id->headerCellClass() ?>"><span id="elh_assignments_assignment_id" class="assignments_assignment_id"><?= $Page->assignment_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
        <th class="<?= $Page->course_id->headerCellClass() ?>"><span id="elh_assignments_course_id" class="assignments_course_id"><?= $Page->course_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assigned_by->Visible) { // assigned_by ?>
        <th class="<?= $Page->assigned_by->headerCellClass() ?>"><span id="elh_assignments_assigned_by" class="assignments_assigned_by"><?= $Page->assigned_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
        <th class="<?= $Page->title->headerCellClass() ?>"><span id="elh_assignments_title" class="assignments_title"><?= $Page->title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><span id="elh_assignments_description" class="assignments_description"><?= $Page->description->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assignment_type->Visible) { // assignment_type ?>
        <th class="<?= $Page->assignment_type->headerCellClass() ?>"><span id="elh_assignments_assignment_type" class="assignments_assignment_type"><?= $Page->assignment_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->due_date->Visible) { // due_date ?>
        <th class="<?= $Page->due_date->headerCellClass() ?>"><span id="elh_assignments_due_date" class="assignments_due_date"><?= $Page->due_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->max_marks->Visible) { // max_marks ?>
        <th class="<?= $Page->max_marks->headerCellClass() ?>"><span id="elh_assignments_max_marks" class="assignments_max_marks"><?= $Page->max_marks->caption() ?></span></th>
<?php } ?>
<?php if ($Page->attachment_path->Visible) { // attachment_path ?>
        <th class="<?= $Page->attachment_path->headerCellClass() ?>"><span id="elh_assignments_attachment_path" class="assignments_attachment_path"><?= $Page->attachment_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_assignments_status" class="assignments_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_assignments_created_at" class="assignments_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
        <td<?= $Page->assignment_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->assignment_id->viewAttributes() ?>>
<?= $Page->assignment_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
        <td<?= $Page->course_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->course_id->viewAttributes() ?>>
<?= $Page->course_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->assigned_by->Visible) { // assigned_by ?>
        <td<?= $Page->assigned_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->assigned_by->viewAttributes() ?>>
<?= $Page->assigned_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
        <td<?= $Page->title->cellAttributes() ?>>
<span id="">
<span<?= $Page->title->viewAttributes() ?>>
<?= $Page->title->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <td<?= $Page->description->cellAttributes() ?>>
<span id="">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->assignment_type->Visible) { // assignment_type ?>
        <td<?= $Page->assignment_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->assignment_type->viewAttributes() ?>>
<?= $Page->assignment_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->due_date->Visible) { // due_date ?>
        <td<?= $Page->due_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->due_date->viewAttributes() ?>>
<?= $Page->due_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->max_marks->Visible) { // max_marks ?>
        <td<?= $Page->max_marks->cellAttributes() ?>>
<span id="">
<span<?= $Page->max_marks->viewAttributes() ?>>
<?= $Page->max_marks->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->attachment_path->Visible) { // attachment_path ?>
        <td<?= $Page->attachment_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->attachment_path->viewAttributes() ?>>
<?= $Page->attachment_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <td<?= $Page->status->cellAttributes() ?>>
<span id="">
<span<?= $Page->status->viewAttributes() ?>>
<?= $Page->status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit"><?= Language()->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
</div>
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
