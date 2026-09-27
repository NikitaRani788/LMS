<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { submissions: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fSubmissionsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fSubmissionsdelete")
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
<form name="fSubmissionsdelete" id="fSubmissionsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submissions">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->submission_id->Visible) { // submission_id ?>
        <th class="<?= $Page->submission_id->headerCellClass() ?>"><span id="elh_submissions_submission_id" class="submissions_submission_id"><?= $Page->submission_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
        <th class="<?= $Page->assignment_id->headerCellClass() ?>"><span id="elh_submissions_assignment_id" class="submissions_assignment_id"><?= $Page->assignment_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_submissions_user_id" class="submissions_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->submission_path->Visible) { // submission_path ?>
        <th class="<?= $Page->submission_path->headerCellClass() ?>"><span id="elh_submissions_submission_path" class="submissions_submission_path"><?= $Page->submission_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <th class="<?= $Page->submitted_at->headerCellClass() ?>"><span id="elh_submissions_submitted_at" class="submissions_submitted_at"><?= $Page->submitted_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->attempt_no->Visible) { // attempt_no ?>
        <th class="<?= $Page->attempt_no->headerCellClass() ?>"><span id="elh_submissions_attempt_no" class="submissions_attempt_no"><?= $Page->attempt_no->caption() ?></span></th>
<?php } ?>
<?php if ($Page->submission_status->Visible) { // submission_status ?>
        <th class="<?= $Page->submission_status->headerCellClass() ?>"><span id="elh_submissions_submission_status" class="submissions_submission_status"><?= $Page->submission_status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
        <th class="<?= $Page->remarks->headerCellClass() ?>"><span id="elh_submissions_remarks" class="submissions_remarks"><?= $Page->remarks->caption() ?></span></th>
<?php } ?>
<?php if ($Page->checked_by->Visible) { // checked_by ?>
        <th class="<?= $Page->checked_by->headerCellClass() ?>"><span id="elh_submissions_checked_by" class="submissions_checked_by"><?= $Page->checked_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->marks_obtained->Visible) { // marks_obtained ?>
        <th class="<?= $Page->marks_obtained->headerCellClass() ?>"><span id="elh_submissions_marks_obtained" class="submissions_marks_obtained"><?= $Page->marks_obtained->caption() ?></span></th>
<?php } ?>
<?php if ($Page->checked_at->Visible) { // checked_at ?>
        <th class="<?= $Page->checked_at->headerCellClass() ?>"><span id="elh_submissions_checked_at" class="submissions_checked_at"><?= $Page->checked_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->submission_id->Visible) { // submission_id ?>
        <td<?= $Page->submission_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->submission_id->viewAttributes() ?>>
<?= $Page->submission_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
        <td<?= $Page->assignment_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->assignment_id->viewAttributes() ?>>
<?= $Page->assignment_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <td<?= $Page->user_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->submission_path->Visible) { // submission_path ?>
        <td<?= $Page->submission_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->submission_path->viewAttributes() ?>>
<?= $Page->submission_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <td<?= $Page->submitted_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->attempt_no->Visible) { // attempt_no ?>
        <td<?= $Page->attempt_no->cellAttributes() ?>>
<span id="">
<span<?= $Page->attempt_no->viewAttributes() ?>>
<?= $Page->attempt_no->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->submission_status->Visible) { // submission_status ?>
        <td<?= $Page->submission_status->cellAttributes() ?>>
<span id="">
<span<?= $Page->submission_status->viewAttributes() ?>>
<?= $Page->submission_status->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
        <td<?= $Page->remarks->cellAttributes() ?>>
<span id="">
<span<?= $Page->remarks->viewAttributes() ?>>
<?= $Page->remarks->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->checked_by->Visible) { // checked_by ?>
        <td<?= $Page->checked_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->checked_by->viewAttributes() ?>>
<?= $Page->checked_by->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->marks_obtained->Visible) { // marks_obtained ?>
        <td<?= $Page->marks_obtained->cellAttributes() ?>>
<span id="">
<span<?= $Page->marks_obtained->viewAttributes() ?>>
<?= $Page->marks_obtained->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->checked_at->Visible) { // checked_at ?>
        <td<?= $Page->checked_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->checked_at->viewAttributes() ?>>
<?= $Page->checked_at->getViewValue() ?></span>
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
