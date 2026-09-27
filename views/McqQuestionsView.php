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
<form name="fMcqQuestionsview" id="fMcqQuestionsview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_questions: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fMcqQuestionsview;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqQuestionsview")
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
<input type="hidden" name="t" value="mcq_questions">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->question_id->Visible) { // question_id ?>
    <tr id="r_question_id"<?= $Page->question_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_question_id"><?= $Page->question_id->caption() ?></span></td>
        <td data-name="question_id"<?= $Page->question_id->cellAttributes() ?>>
<span id="el_mcq_questions_question_id">
<span<?= $Page->question_id->viewAttributes() ?>>
<?= $Page->question_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
    <tr id="r_assignment_id"<?= $Page->assignment_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_assignment_id"><?= $Page->assignment_id->caption() ?></span></td>
        <td data-name="assignment_id"<?= $Page->assignment_id->cellAttributes() ?>>
<span id="el_mcq_questions_assignment_id">
<span<?= $Page->assignment_id->viewAttributes() ?>>
<?= $Page->assignment_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->question_text->Visible) { // question_text ?>
    <tr id="r_question_text"<?= $Page->question_text->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_question_text"><?= $Page->question_text->caption() ?></span></td>
        <td data-name="question_text"<?= $Page->question_text->cellAttributes() ?>>
<span id="el_mcq_questions_question_text">
<span<?= $Page->question_text->viewAttributes() ?>>
<?= $Page->question_text->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->option_a->Visible) { // option_a ?>
    <tr id="r_option_a"<?= $Page->option_a->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_option_a"><?= $Page->option_a->caption() ?></span></td>
        <td data-name="option_a"<?= $Page->option_a->cellAttributes() ?>>
<span id="el_mcq_questions_option_a">
<span<?= $Page->option_a->viewAttributes() ?>>
<?= $Page->option_a->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->option_b->Visible) { // option_b ?>
    <tr id="r_option_b"<?= $Page->option_b->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_option_b"><?= $Page->option_b->caption() ?></span></td>
        <td data-name="option_b"<?= $Page->option_b->cellAttributes() ?>>
<span id="el_mcq_questions_option_b">
<span<?= $Page->option_b->viewAttributes() ?>>
<?= $Page->option_b->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->option_c->Visible) { // option_c ?>
    <tr id="r_option_c"<?= $Page->option_c->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_option_c"><?= $Page->option_c->caption() ?></span></td>
        <td data-name="option_c"<?= $Page->option_c->cellAttributes() ?>>
<span id="el_mcq_questions_option_c">
<span<?= $Page->option_c->viewAttributes() ?>>
<?= $Page->option_c->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->option_d->Visible) { // option_d ?>
    <tr id="r_option_d"<?= $Page->option_d->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_option_d"><?= $Page->option_d->caption() ?></span></td>
        <td data-name="option_d"<?= $Page->option_d->cellAttributes() ?>>
<span id="el_mcq_questions_option_d">
<span<?= $Page->option_d->viewAttributes() ?>>
<?= $Page->option_d->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->correct_option->Visible) { // correct_option ?>
    <tr id="r_correct_option"<?= $Page->correct_option->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_correct_option"><?= $Page->correct_option->caption() ?></span></td>
        <td data-name="correct_option"<?= $Page->correct_option->cellAttributes() ?>>
<span id="el_mcq_questions_correct_option">
<span<?= $Page->correct_option->viewAttributes() ?>>
<?= $Page->correct_option->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->marks->Visible) { // marks ?>
    <tr id="r_marks"<?= $Page->marks->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_marks"><?= $Page->marks->caption() ?></span></td>
        <td data-name="marks"<?= $Page->marks->cellAttributes() ?>>
<span id="el_mcq_questions_marks">
<span<?= $Page->marks->viewAttributes() ?>>
<?= $Page->marks->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <tr id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_questions_created_at"><?= $Page->created_at->caption() ?></span></td>
        <td data-name="created_at"<?= $Page->created_at->cellAttributes() ?>>
<span id="el_mcq_questions_created_at">
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
