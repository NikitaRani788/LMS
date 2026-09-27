<?php

namespace PHPMaker2026\Project1;
?>
<?= $Page->getPageHeader() ?>
<?= $Page->getHtmlMessage() ?>
<main class="edit">
<?php $formAction = UrlFor("edit.submissions", $Page->getUrlKey(true)) ?>
<form name="fSubmissionsedit" id="fSubmissionsedit" class="<?= $Page->FormClassName ?>" action="<?= $formAction ?>" method="post" novalidate autocomplete="off">
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { submissions: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fSubmissionsedit;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fSubmissionsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["submission_id", [fields.submission_id.visible && fields.submission_id.required ? ew.Validators.required(fields.submission_id.caption) : null], fields.submission_id.isInvalid],
            ["assignment_id", [fields.assignment_id.visible && fields.assignment_id.required ? ew.Validators.required(fields.assignment_id.caption) : null, ew.Validators.integer], fields.assignment_id.isInvalid],
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["submission_path", [fields.submission_path.visible && fields.submission_path.required ? ew.Validators.required(fields.submission_path.caption) : null], fields.submission_path.isInvalid],
            ["submitted_at", [fields.submitted_at.visible && fields.submitted_at.required ? ew.Validators.required(fields.submitted_at.caption) : null, ew.Validators.datetime(fields.submitted_at.clientFormatPattern)], fields.submitted_at.isInvalid],
            ["attempt_no", [fields.attempt_no.visible && fields.attempt_no.required ? ew.Validators.required(fields.attempt_no.caption) : null, ew.Validators.integer], fields.attempt_no.isInvalid],
            ["submission_status", [fields.submission_status.visible && fields.submission_status.required ? ew.Validators.required(fields.submission_status.caption) : null], fields.submission_status.isInvalid],
            ["remarks", [fields.remarks.visible && fields.remarks.required ? ew.Validators.required(fields.remarks.caption) : null], fields.remarks.isInvalid],
            ["checked_by", [fields.checked_by.visible && fields.checked_by.required ? ew.Validators.required(fields.checked_by.caption) : null, ew.Validators.integer], fields.checked_by.isInvalid],
            ["marks_obtained", [fields.marks_obtained.visible && fields.marks_obtained.required ? ew.Validators.required(fields.marks_obtained.caption) : null, ew.Validators.integer], fields.marks_obtained.isInvalid],
            ["checked_at", [fields.checked_at.visible && fields.checked_at.required ? ew.Validators.required(fields.checked_at.caption) : null, ew.Validators.datetime(fields.checked_at.clientFormatPattern)], fields.checked_at.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "submission_status": <?= $Page->submission_status->toClientList($Page) ?>,
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
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="submissions">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->submission_id->Visible) { // submission_id ?>
    <div id="r_submission_id"<?= $Page->submission_id->rowAttributes() ?>>
        <label id="elh_submissions_submission_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submission_id->caption() ?><?= $Page->submission_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submission_id->cellAttributes() ?>>
<span id="el_submissions_submission_id">
<span<?= $Page->submission_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= $Page->submission_id->getDisplayValue($Page->submission_id->getEditValue()) ?>"></span>
<input type="hidden" data-table="submissions" data-field="x_submission_id" data-hidden="1" name="x_submission_id" id="x_submission_id" value="<?= HtmlEncode(ConvertToString($Page->submission_id->CurrentValue)) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->assignment_id->Visible) { // assignment_id ?>
    <div id="r_assignment_id"<?= $Page->assignment_id->rowAttributes() ?>>
        <label id="elh_submissions_assignment_id" for="x_assignment_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assignment_id->caption() ?><?= $Page->assignment_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assignment_id->cellAttributes() ?>>
<span id="el_submissions_assignment_id">
<input type="<?= $Page->assignment_id->getInputTextType() ?>" name="x_assignment_id" id="x_assignment_id" data-table="submissions" data-field="x_assignment_id" value="<?= $Page->assignment_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->assignment_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->assignment_id->formatPattern()) ?>"<?= $Page->assignment_id->editAttributes() ?> aria-describedby="x_assignment_id_help">
<?= $Page->assignment_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assignment_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_submissions_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_submissions_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="submissions" data-field="x_user_id" value="<?= $Page->user_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->submission_path->Visible) { // submission_path ?>
    <div id="r_submission_path"<?= $Page->submission_path->rowAttributes() ?>>
        <label id="elh_submissions_submission_path" for="x_submission_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submission_path->caption() ?><?= $Page->submission_path->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submission_path->cellAttributes() ?>>
<span id="el_submissions_submission_path">
<input type="<?= $Page->submission_path->getInputTextType() ?>" name="x_submission_path" id="x_submission_path" data-table="submissions" data-field="x_submission_path" value="<?= $Page->submission_path->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->submission_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->submission_path->formatPattern()) ?>"<?= $Page->submission_path->editAttributes() ?> aria-describedby="x_submission_path_help">
<?= $Page->submission_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->submission_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->submitted_at->Visible) { // submitted_at ?>
    <div id="r_submitted_at"<?= $Page->submitted_at->rowAttributes() ?>>
        <label id="elh_submissions_submitted_at" for="x_submitted_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submitted_at->caption() ?><?= $Page->submitted_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submitted_at->cellAttributes() ?>>
<span id="el_submissions_submitted_at">
<input type="<?= $Page->submitted_at->getInputTextType() ?>" name="x_submitted_at" id="x_submitted_at" data-table="submissions" data-field="x_submitted_at" value="<?= $Page->submitted_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->submitted_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->submitted_at->formatPattern()) ?>"<?= $Page->submitted_at->editAttributes() ?> aria-describedby="x_submitted_at_help">
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
        "fSubmissionsedit",
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
<?php if ($Page->attempt_no->Visible) { // attempt_no ?>
    <div id="r_attempt_no"<?= $Page->attempt_no->rowAttributes() ?>>
        <label id="elh_submissions_attempt_no" for="x_attempt_no" class="<?= $Page->LeftColumnClass ?>"><?= $Page->attempt_no->caption() ?><?= $Page->attempt_no->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->attempt_no->cellAttributes() ?>>
<span id="el_submissions_attempt_no">
<input type="<?= $Page->attempt_no->getInputTextType() ?>" name="x_attempt_no" id="x_attempt_no" data-table="submissions" data-field="x_attempt_no" value="<?= $Page->attempt_no->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->attempt_no->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->attempt_no->formatPattern()) ?>"<?= $Page->attempt_no->editAttributes() ?> aria-describedby="x_attempt_no_help">
<?= $Page->attempt_no->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->attempt_no->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->submission_status->Visible) { // submission_status ?>
    <div id="r_submission_status"<?= $Page->submission_status->rowAttributes() ?>>
        <label id="elh_submissions_submission_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->submission_status->caption() ?><?= $Page->submission_status->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->submission_status->cellAttributes() ?>>
<span id="el_submissions_submission_status">
<template id="tp_x_submission_status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="submissions" data-field="x_submission_status" name="x_submission_status" id="x_submission_status"<?= $Page->submission_status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_submission_status" class="ew-item-list"></div>
<selection-list hidden
    id="x_submission_status"
    name="x_submission_status"
    value="<?= HtmlEncode($Page->submission_status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_submission_status"
    data-target="dsl_x_submission_status"
    data-repeatcolumn="5"
    class="form-control<?= $Page->submission_status->isInvalidClass() ?>"
    data-table="submissions"
    data-field="x_submission_status"
    data-value-separator="<?= $Page->submission_status->displayValueSeparatorAttribute() ?>"
    <?= $Page->submission_status->editAttributes() ?>></selection-list>
<?= $Page->submission_status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->submission_status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
    <div id="r_remarks"<?= $Page->remarks->rowAttributes() ?>>
        <label id="elh_submissions_remarks" for="x_remarks" class="<?= $Page->LeftColumnClass ?>"><?= $Page->remarks->caption() ?><?= $Page->remarks->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->remarks->cellAttributes() ?>>
<span id="el_submissions_remarks">
<input type="<?= $Page->remarks->getInputTextType() ?>" name="x_remarks" id="x_remarks" data-table="submissions" data-field="x_remarks" value="<?= $Page->remarks->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->remarks->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->remarks->formatPattern()) ?>"<?= $Page->remarks->editAttributes() ?> aria-describedby="x_remarks_help">
<?= $Page->remarks->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->remarks->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->checked_by->Visible) { // checked_by ?>
    <div id="r_checked_by"<?= $Page->checked_by->rowAttributes() ?>>
        <label id="elh_submissions_checked_by" for="x_checked_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->checked_by->caption() ?><?= $Page->checked_by->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->checked_by->cellAttributes() ?>>
<span id="el_submissions_checked_by">
<input type="<?= $Page->checked_by->getInputTextType() ?>" name="x_checked_by" id="x_checked_by" data-table="submissions" data-field="x_checked_by" value="<?= $Page->checked_by->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->checked_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->checked_by->formatPattern()) ?>"<?= $Page->checked_by->editAttributes() ?> aria-describedby="x_checked_by_help">
<?= $Page->checked_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->checked_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->marks_obtained->Visible) { // marks_obtained ?>
    <div id="r_marks_obtained"<?= $Page->marks_obtained->rowAttributes() ?>>
        <label id="elh_submissions_marks_obtained" for="x_marks_obtained" class="<?= $Page->LeftColumnClass ?>"><?= $Page->marks_obtained->caption() ?><?= $Page->marks_obtained->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->marks_obtained->cellAttributes() ?>>
<span id="el_submissions_marks_obtained">
<input type="<?= $Page->marks_obtained->getInputTextType() ?>" name="x_marks_obtained" id="x_marks_obtained" data-table="submissions" data-field="x_marks_obtained" value="<?= $Page->marks_obtained->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->marks_obtained->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->marks_obtained->formatPattern()) ?>"<?= $Page->marks_obtained->editAttributes() ?> aria-describedby="x_marks_obtained_help">
<?= $Page->marks_obtained->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->marks_obtained->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->checked_at->Visible) { // checked_at ?>
    <div id="r_checked_at"<?= $Page->checked_at->rowAttributes() ?>>
        <label id="elh_submissions_checked_at" for="x_checked_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->checked_at->caption() ?><?= $Page->checked_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->checked_at->cellAttributes() ?>>
<span id="el_submissions_checked_at">
<input type="<?= $Page->checked_at->getInputTextType() ?>" name="x_checked_at" id="x_checked_at" data-table="submissions" data-field="x_checked_at" value="<?= $Page->checked_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->checked_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->checked_at->formatPattern()) ?>"<?= $Page->checked_at->editAttributes() ?> aria-describedby="x_checked_at_help">
<?= $Page->checked_at->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->checked_at->getErrorMessage() ?></div>
<?php if (!$Page->checked_at->ReadOnly && !$Page->checked_at->Disabled && !isset($Page->checked_at->EditAttrs["readonly"]) && !isset($Page->checked_at->EditAttrs["disabled"])) { ?>
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
        "fSubmissionsedit",
        "x_checked_at",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fSubmissionsedit" formaction="<?= $formAction ?>"><?= Language()->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fSubmissionsedit" formaction="<?= $formAction ?>" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("submissions");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
