<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { announcements: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fAnnouncementsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fAnnouncementsdelete")
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
<form name="fAnnouncementsdelete" id="fAnnouncementsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="announcements">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->announcement_id->Visible) { // announcement_id ?>
        <th class="<?= $Page->announcement_id->headerCellClass() ?>"><span id="elh_announcements_announcement_id" class="announcements_announcement_id"><?= $Page->announcement_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
        <th class="<?= $Page->course_id->headerCellClass() ?>"><span id="elh_announcements_course_id" class="announcements_course_id"><?= $Page->course_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->posted_by->Visible) { // posted_by ?>
        <th class="<?= $Page->posted_by->headerCellClass() ?>"><span id="elh_announcements_posted_by" class="announcements_posted_by"><?= $Page->posted_by->caption() ?></span></th>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
        <th class="<?= $Page->title->headerCellClass() ?>"><span id="elh_announcements_title" class="announcements_title"><?= $Page->title->caption() ?></span></th>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
        <th class="<?= $Page->_message->headerCellClass() ?>"><span id="elh_announcements__message" class="announcements__message"><?= $Page->_message->caption() ?></span></th>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
        <th class="<?= $Page->priority->headerCellClass() ?>"><span id="elh_announcements_priority" class="announcements_priority"><?= $Page->priority->caption() ?></span></th>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
        <th class="<?= $Page->status->headerCellClass() ?>"><span id="elh_announcements_status" class="announcements_status"><?= $Page->status->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_announcements_created_at" class="announcements_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->announcement_id->Visible) { // announcement_id ?>
        <td<?= $Page->announcement_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->announcement_id->viewAttributes() ?>>
<?= $Page->announcement_id->getViewValue() ?></span>
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
<?php if ($Page->posted_by->Visible) { // posted_by ?>
        <td<?= $Page->posted_by->cellAttributes() ?>>
<span id="">
<span<?= $Page->posted_by->viewAttributes() ?>>
<?= $Page->posted_by->getViewValue() ?></span>
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
<?php if ($Page->_message->Visible) { // message ?>
        <td<?= $Page->_message->cellAttributes() ?>>
<span id="">
<span<?= $Page->_message->viewAttributes() ?>>
<?= $Page->_message->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
        <td<?= $Page->priority->cellAttributes() ?>>
<span id="">
<span<?= $Page->priority->viewAttributes() ?>>
<?= $Page->priority->getViewValue() ?></span>
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
