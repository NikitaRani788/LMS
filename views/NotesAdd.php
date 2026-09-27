<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { notes: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fNotesadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fNotesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["course_id", [fields.course_id.visible && fields.course_id.required ? ew.Validators.required(fields.course_id.caption) : null, ew.Validators.integer], fields.course_id.isInvalid],
            ["uploaded_by", [fields.uploaded_by.visible && fields.uploaded_by.required ? ew.Validators.required(fields.uploaded_by.caption) : null, ew.Validators.integer], fields.uploaded_by.isInvalid],
            ["title", [fields.title.visible && fields.title.required ? ew.Validators.required(fields.title.caption) : null], fields.title.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["file_path", [fields.file_path.visible && fields.file_path.required ? ew.Validators.required(fields.file_path.caption) : null], fields.file_path.isInvalid],
            ["note_type", [fields.note_type.visible && fields.note_type.required ? ew.Validators.required(fields.note_type.caption) : null], fields.note_type.isInvalid],
            ["visibility_status", [fields.visibility_status.visible && fields.visibility_status.required ? ew.Validators.required(fields.visibility_status.caption) : null], fields.visibility_status.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "note_type": <?= $Page->note_type->toClientList($Page) ?>,
            "visibility_status": <?= $Page->visibility_status->toClientList($Page) ?>,
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
<form name="fNotesadd" id="fNotesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="notes">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->course_id->Visible) { // course_id ?>
    <div id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <label id="elh_notes_course_id" for="x_course_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->course_id->caption() ?><?= $Page->course_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->course_id->cellAttributes() ?>>
<span id="el_notes_course_id">
<input type="<?= $Page->course_id->getInputTextType() ?>" name="x_course_id" id="x_course_id" data-table="notes" data-field="x_course_id" value="<?= $Page->course_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->course_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->course_id->formatPattern()) ?>"<?= $Page->course_id->editAttributes() ?> aria-describedby="x_course_id_help">
<?= $Page->course_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->course_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->uploaded_by->Visible) { // uploaded_by ?>
    <div id="r_uploaded_by"<?= $Page->uploaded_by->rowAttributes() ?>>
        <label id="elh_notes_uploaded_by" for="x_uploaded_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->uploaded_by->caption() ?><?= $Page->uploaded_by->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->uploaded_by->cellAttributes() ?>>
<span id="el_notes_uploaded_by">
<input type="<?= $Page->uploaded_by->getInputTextType() ?>" name="x_uploaded_by" id="x_uploaded_by" data-table="notes" data-field="x_uploaded_by" value="<?= $Page->uploaded_by->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->uploaded_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->uploaded_by->formatPattern()) ?>"<?= $Page->uploaded_by->editAttributes() ?> aria-describedby="x_uploaded_by_help">
<?= $Page->uploaded_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->uploaded_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
    <div id="r_title"<?= $Page->title->rowAttributes() ?>>
        <label id="elh_notes_title" for="x_title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->title->caption() ?><?= $Page->title->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->title->cellAttributes() ?>>
<span id="el_notes_title">
<input type="<?= $Page->title->getInputTextType() ?>" name="x_title" id="x_title" data-table="notes" data-field="x_title" value="<?= $Page->title->getEditValue() ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->title->formatPattern()) ?>"<?= $Page->title->editAttributes() ?> aria-describedby="x_title_help">
<?= $Page->title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_notes_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_notes_description">
<input type="<?= $Page->description->getInputTextType() ?>" name="x_description" id="x_description" data-table="notes" data-field="x_description" value="<?= $Page->description->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->description->formatPattern()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help">
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->file_path->Visible) { // file_path ?>
    <div id="r_file_path"<?= $Page->file_path->rowAttributes() ?>>
        <label id="elh_notes_file_path" for="x_file_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->file_path->caption() ?><?= $Page->file_path->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->file_path->cellAttributes() ?>>
<span id="el_notes_file_path">
<input type="<?= $Page->file_path->getInputTextType() ?>" name="x_file_path" id="x_file_path" data-table="notes" data-field="x_file_path" value="<?= $Page->file_path->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->file_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->file_path->formatPattern()) ?>"<?= $Page->file_path->editAttributes() ?> aria-describedby="x_file_path_help">
<?= $Page->file_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->file_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->note_type->Visible) { // note_type ?>
    <div id="r_note_type"<?= $Page->note_type->rowAttributes() ?>>
        <label id="elh_notes_note_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->note_type->caption() ?><?= $Page->note_type->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->note_type->cellAttributes() ?>>
<span id="el_notes_note_type">
<template id="tp_x_note_type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="notes" data-field="x_note_type" name="x_note_type" id="x_note_type"<?= $Page->note_type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_note_type" class="ew-item-list"></div>
<selection-list hidden
    id="x_note_type"
    name="x_note_type"
    value="<?= HtmlEncode($Page->note_type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_note_type"
    data-target="dsl_x_note_type"
    data-repeatcolumn="5"
    class="form-control<?= $Page->note_type->isInvalidClass() ?>"
    data-table="notes"
    data-field="x_note_type"
    data-value-separator="<?= $Page->note_type->displayValueSeparatorAttribute() ?>"
    <?= $Page->note_type->editAttributes() ?>></selection-list>
<?= $Page->note_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->note_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->visibility_status->Visible) { // visibility_status ?>
    <div id="r_visibility_status"<?= $Page->visibility_status->rowAttributes() ?>>
        <label id="elh_notes_visibility_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->visibility_status->caption() ?><?= $Page->visibility_status->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->visibility_status->cellAttributes() ?>>
<span id="el_notes_visibility_status">
<template id="tp_x_visibility_status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="notes" data-field="x_visibility_status" name="x_visibility_status" id="x_visibility_status"<?= $Page->visibility_status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_visibility_status" class="ew-item-list"></div>
<selection-list hidden
    id="x_visibility_status"
    name="x_visibility_status"
    value="<?= HtmlEncode($Page->visibility_status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_visibility_status"
    data-target="dsl_x_visibility_status"
    data-repeatcolumn="5"
    class="form-control<?= $Page->visibility_status->isInvalidClass() ?>"
    data-table="notes"
    data-field="x_visibility_status"
    data-value-separator="<?= $Page->visibility_status->displayValueSeparatorAttribute() ?>"
    <?= $Page->visibility_status->editAttributes() ?>></selection-list>
<?= $Page->visibility_status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->visibility_status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_notes_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_notes_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="notes" data-field="x_created_at" value="<?= $Page->created_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
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
        "fNotesadd",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fNotesadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fNotesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("notes");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
