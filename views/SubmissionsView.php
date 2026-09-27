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
<form name="fSubmissionsview" id="fSubmissionsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { submissions: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fSubmissionsview;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fSubmissionsview")
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
<input type="hidden" name="t" value="submissions">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->submission_id->Visible) { // submission_id ?>
    <tr id="r_submission_id"<?= $Page->submission_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_submission_id"><?= $Page->submission_id->caption() ?></span></td>
        <td data-name="submission_id"<?= $Page->submission_id->cellAttributes() ?>>
<span id="el_submissions_submission_id">
<span<?= $Page->submission_id->viewAttributes() ?>>
<?= $Page->submission_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
    <tr id="r_assignment_id"<?= $Page->assignment_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_assignment_id"><?= $Page->assignment_id->caption() ?></span></td>
        <td data-name="assignment_id"<?= $Page->assignment_id->cellAttributes() ?>>
<span id="el_submissions_assignment_id">
<span<?= $Page->assignment_id->viewAttributes() ?>>
<?= $Page->assignment_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_submissions_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->submission_path->Visible) { // submission_path ?>
    <tr id="r_submission_path"<?= $Page->submission_path->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_submission_path"><?= $Page->submission_path->caption() ?></span></td>
        <td data-name="submission_path"<?= $Page->submission_path->cellAttributes() ?>>
<span id="el_submissions_submission_path">
<span<?= $Page->submission_path->viewAttributes() ?>>
<?= $Page->submission_path->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <tr id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_submitted_at"><?= $Page->submitted_at->caption() ?></span></td>
        <td data-name="submitted_at"<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_submissions_submitted_at">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->attempt_no->Visible) { // attempt_no ?>
    <tr id="r_attempt_no"<?= $Page->attempt_no->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_attempt_no"><?= $Page->attempt_no->caption() ?></span></td>
        <td data-name="attempt_no"<?= $Page->attempt_no->cellAttributes() ?>>
<span id="el_submissions_attempt_no">
<span<?= $Page->attempt_no->viewAttributes() ?>>
<?= $Page->attempt_no->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->submission_status->Visible) { // submission_status ?>
    <tr id="r_submission_status"<?= $Page->submission_status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_submission_status"><?= $Page->submission_status->caption() ?></span></td>
        <td data-name="submission_status"<?= $Page->submission_status->cellAttributes() ?>>
<span id="el_submissions_submission_status">
<span<?= $Page->submission_status->viewAttributes() ?>>
<?= $Page->submission_status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
    <tr id="r_remarks"<?= $Page->remarks->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_remarks"><?= $Page->remarks->caption() ?></span></td>
        <td data-name="remarks"<?= $Page->remarks->cellAttributes() ?>>
<span id="el_submissions_remarks">
<span<?= $Page->remarks->viewAttributes() ?>>
<?= $Page->remarks->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->checked_by->Visible) { // checked_by ?>
    <tr id="r_checked_by"<?= $Page->checked_by->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_checked_by"><?= $Page->checked_by->caption() ?></span></td>
        <td data-name="checked_by"<?= $Page->checked_by->cellAttributes() ?>>
<span id="el_submissions_checked_by">
<span<?= $Page->checked_by->viewAttributes() ?>>
<?= $Page->checked_by->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->marks_obtained->Visible) { // marks_obtained ?>
    <tr id="r_marks_obtained"<?= $Page->marks_obtained->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_marks_obtained"><?= $Page->marks_obtained->caption() ?></span></td>
        <td data-name="marks_obtained"<?= $Page->marks_obtained->cellAttributes() ?>>
<span id="el_submissions_marks_obtained">
<span<?= $Page->marks_obtained->viewAttributes() ?>>
<?= $Page->marks_obtained->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->checked_at->Visible) { // checked_at ?>
    <tr id="r_checked_at"<?= $Page->checked_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_submissions_checked_at"><?= $Page->checked_at->caption() ?></span></td>
        <td data-name="checked_at"<?= $Page->checked_at->cellAttributes() ?>>
<span id="el_submissions_checked_at">
<span<?= $Page->checked_at->viewAttributes() ?>>
<?= $Page->checked_at->getViewValue() ?></span>
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
