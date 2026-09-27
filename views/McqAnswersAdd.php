<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { mcq_answers: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fMcqAnswersadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fMcqAnswersadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["question_id", [fields.question_id.visible && fields.question_id.required ? ew.Validators.required(fields.question_id.caption) : null, ew.Validators.integer], fields.question_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["selected_option", [fields.selected_option.visible && fields.selected_option.required ? ew.Validators.required(fields.selected_option.caption) : null], fields.selected_option.isInvalid],
            ["submitted_at", [fields.submitted_at.visible && fields.submitted_at.required ? ew.Validators.required(fields.submitted_at.caption) : null, ew.Validators.datetime(fields.submitted_at.clientFormatPattern)], fields.submitted_at.isInvalid]
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
<form name="fMcqAnswersadd" id="fMcqAnswersadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="mcq_answers">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->question_id->Visible) { // question_id ?>
    <div id="r_question_id"<?= $Page->question_id->rowAttributes() ?>>
        <label id="elh_mcq_answers_question_id" for="x_question_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->question_id->caption() ?><?= $Page->question_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->question_id->cellAttributes() ?>>
<span id="el_mcq_answers_question_id">
<input type="<?= $Page->question_id->getInputTextType() ?>" name="x_question_id" id="x_question_id" data-table="mcq_answers" data-field="x_question_id" value="<?= $Page->question_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->question_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->question_id->formatPattern()) ?>"<?= $Page->question_id->editAttributes() ?> aria-describedby="x_question_id_help">
<?= $Page->question_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->question_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_mcq_answers_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_mcq_answers_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="mcq_answers" data-field="x_user_id" value="<?= $Page->user_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->selected_option->Visible) { // selected_option ?>
    <div id="r_selected_option"<?= $Page->selected_option->rowAttributes() ?>>
        <label id="elh_mcq_answers_selected_option" for="x_selected_option" class="<?= $Page->LeftColumnClass ?>"><?= $Page->selected_option->caption() ?><?= $Page->selected_option->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->selected_option->cellAttributes() ?>>
<span id="el_mcq_answers_selected_option">
<input type="<?= $Page->selected_option->getInputTextType() ?>" name="x_selected_option" id="x_selected_option" data-table="mcq_answers" data-field="x_selected_option" value="<?= $Page->selected_option->getEditValue() ?>" size="30" maxlength="1" placeholder="<?= HtmlEncode($Page->selected_option->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->selected_option->formatPattern()) ?>"<?= $Page->selected_option->editAttributes() ?> aria-describedby="x_selected_option_help">
<?= $Page->selected_option->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->selected_option->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <div id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <label id="elh_mcq_answers_submitted_at" for="x_submitted_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submitted_at->caption() ?><?= $Page->submitted_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_mcq_answers_submitted_at">
<input type="<?= $Page->submitted_at->getInputTextType() ?>" name="x_submitted_at" id="x_submitted_at" data-table="mcq_answers" data-field="x_submitted_at" value="<?= $Page->submitted_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->submitted_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->submitted_at->formatPattern()) ?>"<?= $Page->submitted_at->editAttributes() ?> aria-describedby="x_submitted_at_help">
<?= $Page->submitted_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->submitted_at->getErrorMessage() ?></div>
<?php if (!$Page->submitted_at->ReadOnly && !$Page->submitted_at->Disabled && !isset($Page->submitted_at->EditAttrs["readonly"]) && !isset($Page->submitted_at->EditAttrs["disabled"])) { ?>
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
        "fMcqAnswersadd",
        "x_submitted_at",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fMcqAnswersadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fMcqAnswersadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("mcq_answers");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
