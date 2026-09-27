<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { enrollments: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fEnrollmentsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fEnrollmentsdelete")
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
<form name="fEnrollmentsdelete" id="fEnrollmentsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="enrollments">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->enrollment_id->Visible) { // enrollment_id ?>
        <th class="<?= $Page->enrollment_id->headerCellClass() ?>"><span id="elh_enrollments_enrollment_id" class="enrollments_enrollment_id"><?= $Page->enrollment_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_enrollments_user_id" class="enrollments_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
        <th class="<?= $Page->course_id->headerCellClass() ?>"><span id="elh_enrollments_course_id" class="enrollments_course_id"><?= $Page->course_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->enrollment_date->Visible) { // enrollment_date ?>
        <th class="<?= $Page->enrollment_date->headerCellClass() ?>"><span id="elh_enrollments_enrollment_date" class="enrollments_enrollment_date"><?= $Page->enrollment_date->caption() ?></span></th>
<?php } ?>
<?php if ($Page->enrollment_status->Visible) { // enrollment_status ?>
        <th class="<?= $Page->enrollment_status->headerCellClass() ?>"><span id="elh_enrollments_enrollment_status" class="enrollments_enrollment_status"><?= $Page->enrollment_status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
        <th class="<?= $Page->remarks->headerCellClass() ?>"><span id="elh_enrollments_remarks" class="enrollments_remarks"><?= $Page->remarks->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->enrollment_id->Visible) { // enrollment_id ?>
        <td<?= $Page->enrollment_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->enrollment_id->viewAttributes() ?>>
<?= $Page->enrollment_id->getViewValue() ?></span>
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
<?php if ($Page->course_id->Visible) { // course_id ?>
        <td<?= $Page->course_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->course_id->viewAttributes() ?>>
<?= $Page->course_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->enrollment_date->Visible) { // enrollment_date ?>
        <td<?= $Page->enrollment_date->cellAttributes() ?>>
<span id="">
<span<?= $Page->enrollment_date->viewAttributes() ?>>
<?= $Page->enrollment_date->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->enrollment_status->Visible) { // enrollment_status ?>
        <td<?= $Page->enrollment_status->cellAttributes() ?>>
<span id="">
<span<?= $Page->enrollment_status->viewAttributes() ?>>
<?= $Page->enrollment_status->getViewValue() ?></span>
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
