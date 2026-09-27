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
<form name="fEnrollmentsview" id="fEnrollmentsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { enrollments: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fEnrollmentsview;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fEnrollmentsview")
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
<input type="hidden" name="t" value="enrollments">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->enrollment_id->Visible) { // enrollment_id ?>
    <tr id="r_enrollment_id"<?= $Page->enrollment_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_enrollment_id"><?= $Page->enrollment_id->caption() ?></span></td>
        <td data-name="enrollment_id"<?= $Page->enrollment_id->cellAttributes() ?>>
<span id="el_enrollments_enrollment_id">
<span<?= $Page->enrollment_id->viewAttributes() ?>>
<?= $Page->enrollment_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_enrollments_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
    <tr id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_course_id"><?= $Page->course_id->caption() ?></span></td>
        <td data-name="course_id"<?= $Page->course_id->cellAttributes() ?>>
<span id="el_enrollments_course_id">
<span<?= $Page->course_id->viewAttributes() ?>>
<?= $Page->course_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->enrollment_date->Visible) { // enrollment_date ?>
    <tr id="r_enrollment_date"<?= $Page->enrollment_date->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_enrollment_date"><?= $Page->enrollment_date->caption() ?></span></td>
        <td data-name="enrollment_date"<?= $Page->enrollment_date->cellAttributes() ?>>
<span id="el_enrollments_enrollment_date">
<span<?= $Page->enrollment_date->viewAttributes() ?>>
<?= $Page->enrollment_date->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->enrollment_status->Visible) { // enrollment_status ?>
    <tr id="r_enrollment_status"<?= $Page->enrollment_status->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_enrollment_status"><?= $Page->enrollment_status->caption() ?></span></td>
        <td data-name="enrollment_status"<?= $Page->enrollment_status->cellAttributes() ?>>
<span id="el_enrollments_enrollment_status">
<span<?= $Page->enrollment_status->viewAttributes() ?>>
<?= $Page->enrollment_status->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
    <tr id="r_remarks"<?= $Page->remarks->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_enrollments_remarks"><?= $Page->remarks->caption() ?></span></td>
        <td data-name="remarks"<?= $Page->remarks->cellAttributes() ?>>
<span id="el_enrollments_remarks">
<span<?= $Page->remarks->viewAttributes() ?>>
<?= $Page->remarks->getViewValue() ?></span>
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
