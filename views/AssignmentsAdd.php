<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { assignments: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fAssignmentsadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fAssignmentsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["course_id", [fields.course_id.visible && fields.course_id.required ? ew.Validators.required(fields.course_id.caption) : null, ew.Validators.integer], fields.course_id.isInvalid],
            ["assigned_by", [fields.assigned_by.visible && fields.assigned_by.required ? ew.Validators.required(fields.assigned_by.caption) : null, ew.Validators.integer], fields.assigned_by.isInvalid],
            ["title", [fields.title.visible && fields.title.required ? ew.Validators.required(fields.title.caption) : null], fields.title.isInvalid],
            ["description", [fields.description.visible && fields.description.required ? ew.Validators.required(fields.description.caption) : null], fields.description.isInvalid],
            ["assignment_type", [fields.assignment_type.visible && fields.assignment_type.required ? ew.Validators.required(fields.assignment_type.caption) : null], fields.assignment_type.isInvalid],
            ["due_date", [fields.due_date.visible && fields.due_date.required ? ew.Validators.required(fields.due_date.caption) : null, ew.Validators.datetime(fields.due_date.clientFormatPattern)], fields.due_date.isInvalid],
            ["max_marks", [fields.max_marks.visible && fields.max_marks.required ? ew.Validators.required(fields.max_marks.caption) : null, ew.Validators.integer], fields.max_marks.isInvalid],
            ["attachment_path", [fields.attachment_path.visible && fields.attachment_path.required ? ew.Validators.required(fields.attachment_path.caption) : null], fields.attachment_path.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "assignment_type": <?= $Page->assignment_type->toClientList($Page) ?>,
            "status": <?= $Page->status->toClientList($Page) ?>,
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
<form name="fAssignmentsadd" id="fAssignmentsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="assignments">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->course_id->Visible) { // course_id ?>
    <div id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <label id="elh_assignments_course_id" for="x_course_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->course_id->caption() ?><?= $Page->course_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->course_id->cellAttributes() ?>>
<span id="el_assignments_course_id">
<input type="<?= $Page->course_id->getInputTextType() ?>" name="x_course_id" id="x_course_id" data-table="assignments" data-field="x_course_id" value="<?= $Page->course_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->course_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->course_id->formatPattern()) ?>"<?= $Page->course_id->editAttributes() ?> aria-describedby="x_course_id_help">
<?= $Page->course_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->course_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->assigned_by->Visible) { // assigned_by ?>
    <div id="r_assigned_by"<?= $Page->assigned_by->rowAttributes() ?>>
        <label id="elh_assignments_assigned_by" for="x_assigned_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assigned_by->caption() ?><?= $Page->assigned_by->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assigned_by->cellAttributes() ?>>
<span id="el_assignments_assigned_by">
<input type="<?= $Page->assigned_by->getInputTextType() ?>" name="x_assigned_by" id="x_assigned_by" data-table="assignments" data-field="x_assigned_by" value="<?= $Page->assigned_by->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->assigned_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->assigned_by->formatPattern()) ?>"<?= $Page->assigned_by->editAttributes() ?> aria-describedby="x_assigned_by_help">
<?= $Page->assigned_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assigned_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
    <div id="r_title"<?= $Page->title->rowAttributes() ?>>
        <label id="elh_assignments_title" for="x_title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->title->caption() ?><?= $Page->title->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->title->cellAttributes() ?>>
<span id="el_assignments_title">
<input type="<?= $Page->title->getInputTextType() ?>" name="x_title" id="x_title" data-table="assignments" data-field="x_title" value="<?= $Page->title->getEditValue() ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->title->formatPattern()) ?>"<?= $Page->title->editAttributes() ?> aria-describedby="x_title_help">
<?= $Page->title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
    <div id="r_description"<?= $Page->description->rowAttributes() ?>>
        <label id="elh_assignments_description" for="x_description" class="<?= $Page->LeftColumnClass ?>"><?= $Page->description->caption() ?><?= $Page->description->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->description->cellAttributes() ?>>
<span id="el_assignments_description">
<input type="<?= $Page->description->getInputTextType() ?>" name="x_description" id="x_description" data-table="assignments" data-field="x_description" value="<?= $Page->description->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->description->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->description->formatPattern()) ?>"<?= $Page->description->editAttributes() ?> aria-describedby="x_description_help">
<?= $Page->description->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->description->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->assignment_type->Visible) { // assignment_type ?>
    <div id="r_assignment_type"<?= $Page->assignment_type->rowAttributes() ?>>
        <label id="elh_assignments_assignment_type" class="<?= $Page->LeftColumnClass ?>"><?= $Page->assignment_type->caption() ?><?= $Page->assignment_type->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->assignment_type->cellAttributes() ?>>
<span id="el_assignments_assignment_type">
<template id="tp_x_assignment_type">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="assignments" data-field="x_assignment_type" name="x_assignment_type" id="x_assignment_type"<?= $Page->assignment_type->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_assignment_type" class="ew-item-list"></div>
<selection-list hidden
    id="x_assignment_type"
    name="x_assignment_type"
    value="<?= HtmlEncode($Page->assignment_type->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_assignment_type"
    data-target="dsl_x_assignment_type"
    data-repeatcolumn="5"
    class="form-control<?= $Page->assignment_type->isInvalidClass() ?>"
    data-table="assignments"
    data-field="x_assignment_type"
    data-value-separator="<?= $Page->assignment_type->displayValueSeparatorAttribute() ?>"
    <?= $Page->assignment_type->editAttributes() ?>></selection-list>
<?= $Page->assignment_type->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->assignment_type->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->due_date->Visible) { // due_date ?>
    <div id="r_due_date"<?= $Page->due_date->rowAttributes() ?>>
        <label id="elh_assignments_due_date" for="x_due_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->due_date->caption() ?><?= $Page->due_date->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->due_date->cellAttributes() ?>>
<span id="el_assignments_due_date">
<input type="<?= $Page->due_date->getInputTextType() ?>" name="x_due_date" id="x_due_date" data-table="assignments" data-field="x_due_date" value="<?= $Page->due_date->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->due_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->due_date->formatPattern()) ?>"<?= $Page->due_date->editAttributes() ?> aria-describedby="x_due_date_help">
<?= $Page->due_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->due_date->getErrorMessage() ?></div>
<?php if (!$Page->due_date->ReadOnly && !$Page->due_date->Disabled && !isset($Page->due_date->EditAttrs["readonly"]) && !isset($Page->due_date->EditAttrs["disabled"])) { ?>
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
        "fAssignmentsadd",
        "x_due_date",
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
<?php if ($Page->max_marks->Visible) { // max_marks ?>
    <div id="r_max_marks"<?= $Page->max_marks->rowAttributes() ?>>
        <label id="elh_assignments_max_marks" for="x_max_marks" class="<?= $Page->LeftColumnClass ?>"><?= $Page->max_marks->caption() ?><?= $Page->max_marks->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->max_marks->cellAttributes() ?>>
<span id="el_assignments_max_marks">
<input type="<?= $Page->max_marks->getInputTextType() ?>" name="x_max_marks" id="x_max_marks" data-table="assignments" data-field="x_max_marks" value="<?= $Page->max_marks->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->max_marks->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->max_marks->formatPattern()) ?>"<?= $Page->max_marks->editAttributes() ?> aria-describedby="x_max_marks_help">
<?= $Page->max_marks->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->max_marks->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->attachment_path->Visible) { // attachment_path ?>
    <div id="r_attachment_path"<?= $Page->attachment_path->rowAttributes() ?>>
        <label id="elh_assignments_attachment_path" for="x_attachment_path" class="<?= $Page->LeftColumnClass ?>"><?= $Page->attachment_path->caption() ?><?= $Page->attachment_path->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->attachment_path->cellAttributes() ?>>
<span id="el_assignments_attachment_path">
<input type="<?= $Page->attachment_path->getInputTextType() ?>" name="x_attachment_path" id="x_attachment_path" data-table="assignments" data-field="x_attachment_path" value="<?= $Page->attachment_path->getEditValue() ?>" size="30" maxlength="255" placeholder="<?= HtmlEncode($Page->attachment_path->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->attachment_path->formatPattern()) ?>"<?= $Page->attachment_path->editAttributes() ?> aria-describedby="x_attachment_path_help">
<?= $Page->attachment_path->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->attachment_path->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_assignments_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_assignments_status">
<template id="tp_x_status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="assignments" data-field="x_status" name="x_status" id="x_status"<?= $Page->status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_status" class="ew-item-list"></div>
<selection-list hidden
    id="x_status"
    name="x_status"
    value="<?= HtmlEncode($Page->status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_status"
    data-target="dsl_x_status"
    data-repeatcolumn="5"
    class="form-control<?= $Page->status->isInvalidClass() ?>"
    data-table="assignments"
    data-field="x_status"
    data-value-separator="<?= $Page->status->displayValueSeparatorAttribute() ?>"
    <?= $Page->status->editAttributes() ?>></selection-list>
<?= $Page->status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_assignments_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_assignments_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="assignments" data-field="x_created_at" value="<?= $Page->created_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
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
        "fAssignmentsadd",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fAssignmentsadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fAssignmentsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("assignments");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
