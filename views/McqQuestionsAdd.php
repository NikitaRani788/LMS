<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_questions: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fMcqQuestionsadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqQuestionsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["assignment_id", [fields.assignment_id.visible && fields.assignment_id.required ? ew.Validators.required(fields.assignment_id.caption) : null, ew.Validators.integer], fields.assignment_id.isInvalid],
            ["question_text", [fields.question_text.visible && fields.question_text.required ? ew.Validators.required(fields.question_text.caption) : null], fields.question_text.isInvalid],
            ["option_a", [fields.option_a.visible && fields.option_a.required ? ew.Validators.required(fields.option_a.caption) : null], fields.option_a.isInvalid],
            ["option_b", [fields.option_b.visible && fields.option_b.required ? ew.Validators.required(fields.option_b.caption) : null], fields.option_b.isInvalid],
            ["option_c", [fields.option_c.visible && fields.option_c.required ? ew.Validators.required(fields.option_c.caption) : null], fields.option_c.isInvalid],
            ["option_d", [fields.option_d.visible && fields.option_d.required ? ew.Validators.required(fields.option_d.caption) : null], fields.option_d.isInvalid],
            ["correct_option", [fields.correct_option.visible && fields.correct_option.required ? ew.Validators.required(fields.correct_option.caption) : null], fields.correct_option.isInvalid],
            ["marks", [fields.marks.visible && fields.marks.required ? ew.Validators.required(fields.marks.caption) : null, ew.Validators.integer], fields.marks.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
        })
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
<form name="fMcqQuestionsadd" id="fMcqQuestionsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mcq_questions">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
    <div id="r_assignment_id"<?= $Page->assignment_id->rowAttributes() ?>>
        <label id="elh_mcq_questions_assignment_id" for="x_assignment_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assignment_id->caption() ?><?= $Page->assignment_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assignment_id->cellAttributes() ?>>
<span id="el_mcq_questions_assignment_id">
<input type="<?= $Page->assignment_id->getInputTextType() ?>" name="x_assignment_id" id="x_assignment_id" data-table="mcq_questions" data-field="x_assignment_id" value="<?= $Page->assignment_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->assignment_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->assignment_id->formatPattern()) ?>"<?= $Page->assignment_id->editAttributes() ?> aria-describedby="x_assignment_id_help">
<?= $Page->assignment_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assignment_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->question_text->Visible) { // question_text ?>
    <div id="r_question_text"<?= $Page->question_text->rowAttributes() ?>>
        <label id="elh_mcq_questions_question_text" for="x_question_text" class="<?= $Page->LeftColumnClass ?>"><?= $Page->question_text->caption() ?><?= $Page->question_text->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->question_text->cellAttributes() ?>>
<span id="el_mcq_questions_question_text">
<input type="<?= $Page->question_text->getInputTextType() ?>" name="x_question_text" id="x_question_text" data-table="mcq_questions" data-field="x_question_text" value="<?= $Page->question_text->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->question_text->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->question_text->formatPattern()) ?>"<?= $Page->question_text->editAttributes() ?> aria-describedby="x_question_text_help">
<?= $Page->question_text->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->question_text->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->option_a->Visible) { // option_a ?>
    <div id="r_option_a"<?= $Page->option_a->rowAttributes() ?>>
        <label id="elh_mcq_questions_option_a" for="x_option_a" class="<?= $Page->LeftColumnClass ?>"><?= $Page->option_a->caption() ?><?= $Page->option_a->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->option_a->cellAttributes() ?>>
<span id="el_mcq_questions_option_a">
<input type="<?= $Page->option_a->getInputTextType() ?>" name="x_option_a" id="x_option_a" data-table="mcq_questions" data-field="x_option_a" value="<?= $Page->option_a->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->option_a->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->option_a->formatPattern()) ?>"<?= $Page->option_a->editAttributes() ?> aria-describedby="x_option_a_help">
<?= $Page->option_a->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->option_a->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->option_b->Visible) { // option_b ?>
    <div id="r_option_b"<?= $Page->option_b->rowAttributes() ?>>
        <label id="elh_mcq_questions_option_b" for="x_option_b" class="<?= $Page->LeftColumnClass ?>"><?= $Page->option_b->caption() ?><?= $Page->option_b->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->option_b->cellAttributes() ?>>
<span id="el_mcq_questions_option_b">
<input type="<?= $Page->option_b->getInputTextType() ?>" name="x_option_b" id="x_option_b" data-table="mcq_questions" data-field="x_option_b" value="<?= $Page->option_b->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->option_b->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->option_b->formatPattern()) ?>"<?= $Page->option_b->editAttributes() ?> aria-describedby="x_option_b_help">
<?= $Page->option_b->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->option_b->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->option_c->Visible) { // option_c ?>
    <div id="r_option_c"<?= $Page->option_c->rowAttributes() ?>>
        <label id="elh_mcq_questions_option_c" for="x_option_c" class="<?= $Page->LeftColumnClass ?>"><?= $Page->option_c->caption() ?><?= $Page->option_c->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->option_c->cellAttributes() ?>>
<span id="el_mcq_questions_option_c">
<input type="<?= $Page->option_c->getInputTextType() ?>" name="x_option_c" id="x_option_c" data-table="mcq_questions" data-field="x_option_c" value="<?= $Page->option_c->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->option_c->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->option_c->formatPattern()) ?>"<?= $Page->option_c->editAttributes() ?> aria-describedby="x_option_c_help">
<?= $Page->option_c->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->option_c->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->option_d->Visible) { // option_d ?>
    <div id="r_option_d"<?= $Page->option_d->rowAttributes() ?>>
        <label id="elh_mcq_questions_option_d" for="x_option_d" class="<?= $Page->LeftColumnClass ?>"><?= $Page->option_d->caption() ?><?= $Page->option_d->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->option_d->cellAttributes() ?>>
<span id="el_mcq_questions_option_d">
<input type="<?= $Page->option_d->getInputTextType() ?>" name="x_option_d" id="x_option_d" data-table="mcq_questions" data-field="x_option_d" value="<?= $Page->option_d->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->option_d->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->option_d->formatPattern()) ?>"<?= $Page->option_d->editAttributes() ?> aria-describedby="x_option_d_help">
<?= $Page->option_d->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->option_d->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->correct_option->Visible) { // correct_option ?>
    <div id="r_correct_option"<?= $Page->correct_option->rowAttributes() ?>>
        <label id="elh_mcq_questions_correct_option" for="x_correct_option" class="<?= $Page->LeftColumnClass ?>"><?= $Page->correct_option->caption() ?><?= $Page->correct_option->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->correct_option->cellAttributes() ?>>
<span id="el_mcq_questions_correct_option">
<input type="<?= $Page->correct_option->getInputTextType() ?>" name="x_correct_option" id="x_correct_option" data-table="mcq_questions" data-field="x_correct_option" value="<?= $Page->correct_option->getEditValue() ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->correct_option->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->correct_option->formatPattern()) ?>"<?= $Page->correct_option->editAttributes() ?> aria-describedby="x_correct_option_help">
<?= $Page->correct_option->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->correct_option->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->marks->Visible) { // marks ?>
    <div id="r_marks"<?= $Page->marks->rowAttributes() ?>>
        <label id="elh_mcq_questions_marks" for="x_marks" class="<?= $Page->LeftColumnClass ?>"><?= $Page->marks->caption() ?><?= $Page->marks->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->marks->cellAttributes() ?>>
<span id="el_mcq_questions_marks">
<input type="<?= $Page->marks->getInputTextType() ?>" name="x_marks" id="x_marks" data-table="mcq_questions" data-field="x_marks" value="<?= $Page->marks->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->marks->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->marks->formatPattern()) ?>"<?= $Page->marks->editAttributes() ?> aria-describedby="x_marks_help">
<?= $Page->marks->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->marks->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_mcq_questions_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_mcq_questions_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="mcq_questions" data-field="x_created_at" value="<?= $Page->created_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
<?= $Page->created_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->created_at->getErrorMessage() ?></div>
<?php if (!$Page->created_at->ReadOnly && !$Page->created_at->Disabled && !isset($Page->created_at->EditAttrs["readonly"]) && !isset($Page->created_at->EditAttrs["disabled"])) { ?>
<script<?= Nonce() ?>>
(function () {
    let format = "<?= DateFormat(0) ?>",
        options = {
            localization: {
                locale: ew.LANGUAGE_ID + "-u-nu-" + ew.getNumberingSystem(),
                hourCycle: format.match(/H/) ? "h24" : "h12",
                format,
                ...ew.language.phrase("datetimepicker")
            },
            display: {
                icons: {
                    previous: ew.IS_RTL ? "fa-solid fa-chevron-right" : "fa-solid fa-chevron-left",
                    next: ew.IS_RTL ? "fa-solid fa-chevron-left" : "fa-solid fa-chevron-right"
                },
                components: {
                    clock: !!format.match(/h/i) || !!format.match(/m/) || !!format.match(/s/i),
                    hours: !!format.match(/h/i),
                    minutes: !!format.match(/m/),
                    seconds: !!format.match(/s/i)
                },
                theme: ew.getPreferredTheme()
            }
        };
    ew.createDateTimePicker(
        "fMcqQuestionsadd",
        "x_created_at",
        ew.deepAssign({"useCurrent":false,"display":{"sideBySide":false}}, options),
        {"inputGroup":true,"minDateField":null,"maxDateField":null}
    );
})();
</script>
<?php } ?>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fMcqQuestionsadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fMcqQuestionsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("mcq_questions");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
