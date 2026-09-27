<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { notes: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fNotesdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fNotesdelete")
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
<form name="fNotesdelete" id="fNotesdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notes">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->note_id->Visible) { // note_id ?>
        <th class="<?= $Page->note_id->headerCellClass() ?>"><span id="elh_notes_note_id" class="notes_note_id"><?= $Page->note_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
        <th class="<?= $Page->course_id->headerCellClass() ?>"><span id="elh_notes_course_id" class="notes_course_id"><?= $Page->course_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
        <th class="<?= $Page->uploaded_by->headerCellClass() ?>"><span id="elh_notes_uploaded_by" class="notes_uploaded_by"><?= $Page->uploaded_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
        <th class="<?= $Page->title->headerCellClass() ?>"><span id="elh_notes_title" class="notes_title"><?= $Page->title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><span id="elh_notes_description" class="notes_description"><?= $Page->description->caption() ?></span></th>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
        <th class="<?= $Page->file_path->headerCellClass() ?>"><span id="elh_notes_file_path" class="notes_file_path"><?= $Page->file_path->caption() ?></span></th>
<?php } ?>
<?php if ($Page->note_type->Visible) { // note_type ?>
        <th class="<?= $Page->note_type->headerCellClass() ?>"><span id="elh_notes_note_type" class="notes_note_type"><?= $Page->note_type->caption() ?></span></th>
<?php } ?>
<?php if ($Page->visibility_status->Visible) { // visibility_status ?>
        <th class="<?= $Page->visibility_status->headerCellClass() ?>"><span id="elh_notes_visibility_status" class="notes_visibility_status"><?= $Page->visibility_status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_notes_created_at" class="notes_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->note_id->Visible) { // note_id ?>
        <td<?= $Page->note_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->note_id->viewAttributes() ?>>
<?= $Page->note_id->getViewValue() ?></span>
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
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
        <td<?= $Page->uploaded_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->uploaded_by->viewAttributes() ?>>
<?= $Page->uploaded_by->getViewValue() ?></span>
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
<?php if ($Page->file_path->Visible) { // file_path ?>
        <td<?= $Page->file_path->cellAttributes() ?>>
<span id="">
<span<?= $Page->file_path->viewAttributes() ?>>
<?= $Page->file_path->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->note_type->Visible) { // note_type ?>
        <td<?= $Page->note_type->cellAttributes() ?>>
<span id="">
<span<?= $Page->note_type->viewAttributes() ?>>
<?= $Page->note_type->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->visibility_status->Visible) { // visibility_status ?>
        <td<?= $Page->visibility_status->cellAttributes() ?>>
<span id="">
<span<?= $Page->visibility_status->viewAttributes() ?>>
<?= $Page->visibility_status->getViewValue() ?></span>
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
