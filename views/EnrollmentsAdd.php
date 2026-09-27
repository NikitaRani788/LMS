<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { enrollments: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fEnrollmentsadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fEnrollmentsadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["user_id", [fields.user_id.visible && fields.user_id.required ? ew.Validators.required(fields.user_id.caption) : null, ew.Validators.integer], fields.user_id.isInvalid],
            ["course_id", [fields.course_id.visible && fields.course_id.required ? ew.Validators.required(fields.course_id.caption) : null, ew.Validators.integer], fields.course_id.isInvalid],
            ["enrollment_date", [fields.enrollment_date.visible && fields.enrollment_date.required ? ew.Validators.required(fields.enrollment_date.caption) : null, ew.Validators.datetime(fields.enrollment_date.clientFormatPattern)], fields.enrollment_date.isInvalid],
            ["enrollment_status", [fields.enrollment_status.visible && fields.enrollment_status.required ? ew.Validators.required(fields.enrollment_status.caption) : null], fields.enrollment_status.isInvalid],
            ["remarks", [fields.remarks.visible && fields.remarks.required ? ew.Validators.required(fields.remarks.caption) : null], fields.remarks.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "enrollment_status": <?= $Page->enrollment_status->toClientList($Page) ?>,
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
<form name="fEnrollmentsadd" id="fEnrollmentsadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="enrollments">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->user_id->Visible) { // user_id ?>
    <div id="r_user_id"<?= $Page->user_id->rowAttributes() ?>>
        <label id="elh_enrollments_user_id" for="x_user_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->user_id->caption() ?><?= $Page->user_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->user_id->cellAttributes() ?>>
<span id="el_enrollments_user_id">
<input type="<?= $Page->user_id->getInputTextType() ?>" name="x_user_id" id="x_user_id" data-table="enrollments" data-field="x_user_id" value="<?= $Page->user_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->user_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->user_id->formatPattern()) ?>"<?= $Page->user_id->editAttributes() ?> aria-describedby="x_user_id_help">
<?= $Page->user_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->user_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
    <div id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <label id="elh_enrollments_course_id" for="x_course_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->course_id->caption() ?><?= $Page->course_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->course_id->cellAttributes() ?>>
<span id="el_enrollments_course_id">
<input type="<?= $Page->course_id->getInputTextType() ?>" name="x_course_id" id="x_course_id" data-table="enrollments" data-field="x_course_id" value="<?= $Page->course_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->course_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->course_id->formatPattern()) ?>"<?= $Page->course_id->editAttributes() ?> aria-describedby="x_course_id_help">
<?= $Page->course_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->course_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->enrollment_date->Visible) { // enrollment_date ?>
    <div id="r_enrollment_date"<?= $Page->enrollment_date->rowAttributes() ?>>
        <label id="elh_enrollments_enrollment_date" for="x_enrollment_date" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enrollment_date->caption() ?><?= $Page->enrollment_date->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->enrollment_date->cellAttributes() ?>>
<span id="el_enrollments_enrollment_date">
<input type="<?= $Page->enrollment_date->getInputTextType() ?>" name="x_enrollment_date" id="x_enrollment_date" data-table="enrollments" data-field="x_enrollment_date" value="<?= $Page->enrollment_date->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->enrollment_date->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->enrollment_date->formatPattern()) ?>"<?= $Page->enrollment_date->editAttributes() ?> aria-describedby="x_enrollment_date_help">
<?= $Page->enrollment_date->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->enrollment_date->getErrorMessage() ?></div>
<?php if (!$Page->enrollment_date->ReadOnly && !$Page->enrollment_date->Disabled && !isset($Page->enrollment_date->EditAttrs["readonly"]) && !isset($Page->enrollment_date->EditAttrs["disabled"])) { ?>
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
        "fEnrollmentsadd",
        "x_enrollment_date",
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
<?php if ($Page->enrollment_status->Visible) { // enrollment_status ?>
    <div id="r_enrollment_status"<?= $Page->enrollment_status->rowAttributes() ?>>
        <label id="elh_enrollments_enrollment_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->enrollment_status->caption() ?><?= $Page->enrollment_status->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->enrollment_status->cellAttributes() ?>>
<span id="el_enrollments_enrollment_status">
<template id="tp_x_enrollment_status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="enrollments" data-field="x_enrollment_status" name="x_enrollment_status" id="x_enrollment_status"<?= $Page->enrollment_status->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_enrollment_status" class="ew-item-list"></div>
<selection-list hidden
    id="x_enrollment_status"
    name="x_enrollment_status"
    value="<?= HtmlEncode($Page->enrollment_status->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_enrollment_status"
    data-target="dsl_x_enrollment_status"
    data-repeatcolumn="5"
    class="form-control<?= $Page->enrollment_status->isInvalidClass() ?>"
    data-table="enrollments"
    data-field="x_enrollment_status"
    data-value-separator="<?= $Page->enrollment_status->displayValueSeparatorAttribute() ?>"
    <?= $Page->enrollment_status->editAttributes() ?>></selection-list>
<?= $Page->enrollment_status->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->enrollment_status->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->remarks->Visible) { // remarks ?>
    <div id="r_remarks"<?= $Page->remarks->rowAttributes() ?>>
        <label id="elh_enrollments_remarks" for="x_remarks" class="<?= $Page->LeftColumnClass ?>"><?= $Page->remarks->caption() ?><?= $Page->remarks->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->remarks->cellAttributes() ?>>
<span id="el_enrollments_remarks">
<input type="<?= $Page->remarks->getInputTextType() ?>" name="x_remarks" id="x_remarks" data-table="enrollments" data-field="x_remarks" value="<?= $Page->remarks->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->remarks->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->remarks->formatPattern()) ?>"<?= $Page->remarks->editAttributes() ?> aria-describedby="x_remarks_help">
<?= $Page->remarks->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->remarks->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fEnrollmentsadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fEnrollmentsadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("enrollments");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
