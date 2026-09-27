<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_answers: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fMcqAnswersdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqAnswersdelete")
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
<form name="fMcqAnswersdelete" id="fMcqAnswersdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mcq_answers">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->answer_id->Visible) { // answer_id ?>
        <th class="<?= $Page->answer_id->headerCellClass() ?>"><span id="elh_mcq_answers_answer_id" class="mcq_answers_answer_id"><?= $Page->answer_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->question_id->Visible) { // question_id ?>
        <th class="<?= $Page->question_id->headerCellClass() ?>"><span id="elh_mcq_answers_question_id" class="mcq_answers_question_id"><?= $Page->question_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
        <th class="<?= $Page->user_id->headerCellClass() ?>"><span id="elh_mcq_answers_user_id" class="mcq_answers_user_id"><?= $Page->user_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->selected_option->Visible) { // selected_option ?>
        <th class="<?= $Page->selected_option->headerCellClass() ?>"><span id="elh_mcq_answers_selected_option" class="mcq_answers_selected_option"><?= $Page->selected_option->caption() ?></span></th>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
        <th class="<?= $Page->submitted_at->headerCellClass() ?>"><span id="elh_mcq_answers_submitted_at" class="mcq_answers_submitted_at"><?= $Page->submitted_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->answer_id->Visible) { // answer_id ?>
        <td<?= $Page->answer_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->answer_id->viewAttributes() ?>>
<?= $Page->answer_id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->question_id->Visible) { // question_id ?>
        <td<?= $Page->question_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->question_id->viewAttributes() ?>>
<?= $Page->question_id->getViewValue() ?></span>
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
<?php if ($Page->selected_option->Visible) { // selected_option ?>
        <td<?= $Page->selected_option->cellAttributes() ?>>
<span id="">
<span<?= $Page->selected_option->viewAttributes() ?>>
<?= $Page->selected_option->getViewValue() ?></span>
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
