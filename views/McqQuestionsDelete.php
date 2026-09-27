<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_questions: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fMcqQuestionsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqQuestionsdelete")
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
<form name="fMcqQuestionsdelete" id="fMcqQuestionsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mcq_questions">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->question_id->Visible) { // question_id ?>
        <th class="<?= $Page->question_id->headerCellClass() ?>"><span id="elh_mcq_questions_question_id" class="mcq_questions_question_id"><?= $Page->question_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
        <th class="<?= $Page->assignment_id->headerCellClass() ?>"><span id="elh_mcq_questions_assignment_id" class="mcq_questions_assignment_id"><?= $Page->assignment_id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->question_text->Visible) { // question_text ?>
        <th class="<?= $Page->question_text->headerCellClass() ?>"><span id="elh_mcq_questions_question_text" class="mcq_questions_question_text"><?= $Page->question_text->caption() ?></span></th>
<?php } ?>
<?php if ($Page->option_a->Visible) { // option_a ?>
        <th class="<?= $Page->option_a->headerCellClass() ?>"><span id="elh_mcq_questions_option_a" class="mcq_questions_option_a"><?= $Page->option_a->caption() ?></span></th>
<?php } ?>
<?php if ($Page->option_b->Visible) { // option_b ?>
        <th class="<?= $Page->option_b->headerCellClass() ?>"><span id="elh_mcq_questions_option_b" class="mcq_questions_option_b"><?= $Page->option_b->caption() ?></span></th>
<?php } ?>
<?php if ($Page->option_c->Visible) { // option_c ?>
        <th class="<?= $Page->option_c->headerCellClass() ?>"><span id="elh_mcq_questions_option_c" class="mcq_questions_option_c"><?= $Page->option_c->caption() ?></span></th>
<?php } ?>
<?php if ($Page->option_d->Visible) { // option_d ?>
        <th class="<?= $Page->option_d->headerCellClass() ?>"><span id="elh_mcq_questions_option_d" class="mcq_questions_option_d"><?= $Page->option_d->caption() ?></span></th>
<?php } ?>
<?php if ($Page->correct_option->Visible) { // correct_option ?>
        <th class="<?= $Page->correct_option->headerCellClass() ?>"><span id="elh_mcq_questions_correct_option" class="mcq_questions_correct_option"><?= $Page->correct_option->caption() ?></span></th>
<?php } ?>
<?php if ($Page->marks->Visible) { // marks ?>
        <th class="<?= $Page->marks->headerCellClass() ?>"><span id="elh_mcq_questions_marks" class="mcq_questions_marks"><?= $Page->marks->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_mcq_questions_created_at" class="mcq_questions_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->question_id->Visible) { // question_id ?>
        <td<?= $Page->question_id->cellAttributes() ?>>
<span id="">
<span<?= $Page->question_id->viewAttributes() ?>>
<?= $Page->question_id->getViewValue() ?></span>
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
<?php if ($Page->question_text->Visible) { // question_text ?>
        <td<?= $Page->question_text->cellAttributes() ?>>
<span id="">
<span<?= $Page->question_text->viewAttributes() ?>>
<?= $Page->question_text->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->option_a->Visible) { // option_a ?>
        <td<?= $Page->option_a->cellAttributes() ?>>
<span id="">
<span<?= $Page->option_a->viewAttributes() ?>>
<?= $Page->option_a->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->option_b->Visible) { // option_b ?>
        <td<?= $Page->option_b->cellAttributes() ?>>
<span id="">
<span<?= $Page->option_b->viewAttributes() ?>>
<?= $Page->option_b->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->option_c->Visible) { // option_c ?>
        <td<?= $Page->option_c->cellAttributes() ?>>
<span id="">
<span<?= $Page->option_c->viewAttributes() ?>>
<?= $Page->option_c->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->option_d->Visible) { // option_d ?>
        <td<?= $Page->option_d->cellAttributes() ?>>
<span id="">
<span<?= $Page->option_d->viewAttributes() ?>>
<?= $Page->option_d->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->correct_option->Visible) { // correct_option ?>
        <td<?= $Page->correct_option->cellAttributes() ?>>
<span id="">
<span<?= $Page->correct_option->viewAttributes() ?>>
<?= $Page->correct_option->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->marks->Visible) { // marks ?>
        <td<?= $Page->marks->cellAttributes() ?>>
<span id="">
<span<?= $Page->marks->viewAttributes() ?>>
<?= $Page->marks->getViewValue() ?></span>
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
