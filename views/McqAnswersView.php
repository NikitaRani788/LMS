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
<form name="fMcqAnswersview" id="fMcqAnswersview" class="ew-form ew-view-form overlay-wrapper" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (!$Page->isExport()) { ?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_answers: currentTable } });
var currentPageID = ew.PAGE_ID = "view";
var currentForm;
var fMcqAnswersview;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqAnswersview")
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
<input type="hidden" name="t" value="mcq_answers">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<table class="<?= $Page->TableClass ?>">
<?php if ($Page->answer_id->Visible) { // answer_id ?>
    <tr id="r_answer_id"<?= $Page->answer_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_answers_answer_id"><?= $Page->answer_id->caption() ?></span></td>
        <td data-name="answer_id"<?= $Page->answer_id->cellAttributes() ?>>
<span id="el_mcq_answers_answer_id">
<span<?= $Page->answer_id->viewAttributes() ?>>
<?= $Page->answer_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->question_id->Visible) { // question_id ?>
    <tr id="r_question_id"<?= $Page->question_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_answers_question_id"><?= $Page->question_id->caption() ?></span></td>
        <td data-name="question_id"<?= $Page->question_id->cellAttributes() ?>>
<span id="el_mcq_answers_question_id">
<span<?= $Page->question_id->viewAttributes() ?>>
<?= $Page->question_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <tr id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_answers_user_id"><?= $Page->user_id->caption() ?></span></td>
        <td data-name="user_id"<?= $Page->user_id->cellAttributes() ?>>
<span id="el_mcq_answers_user_id">
<span<?= $Page->user_id->viewAttributes() ?>>
<?= $Page->user_id->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->selected_option->Visible) { // selected_option ?>
    <tr id="r_selected_option"<?= $Page->selected_option->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_answers_selected_option"><?= $Page->selected_option->caption() ?></span></td>
        <td data-name="selected_option"<?= $Page->selected_option->cellAttributes() ?>>
<span id="el_mcq_answers_selected_option">
<span<?= $Page->selected_option->viewAttributes() ?>>
<?= $Page->selected_option->getViewValue() ?></span>
</span>
</td>
    </tr>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <tr id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <td class="<?= $Page->TableLeftColumnClass ?>"><span id="elh_mcq_answers_submitted_at"><?= $Page->submitted_at->caption() ?></span></td>
        <td data-name="submitted_at"<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_mcq_answers_submitted_at">
<span<?= $Page->submitted_at->viewAttributes() ?>>
<?= $Page->submitted_at->getViewValue() ?></span>
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
