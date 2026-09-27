<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { semester_courses: currentTable } });
var currentPageID = ew.PAGE_ID = "add";
var currentForm;
var fSemesterCoursesadd;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fSemesterCoursesadd")
        .setPageId("add")

        // Add fields
        .setFields([
            ["course_id", [fields.course_id.visible && fields.course_id.required ? ew.Validators.required(fields.course_id.caption) : null, ew.Validators.integer], fields.course_id.isInvalid],
            ["semester", [fields.semester.visible && fields.semester.required ? ew.Validators.required(fields.semester.caption) : null, ew.Validators.integer], fields.semester.isInvalid],
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
<form name="fSemesterCoursesadd" id="fSemesterCoursesadd" class="<?= $Page->FormClassName ?>" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="semester_courses">
<input type="hidden" name="action" id="action" value="insert">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-add-div"><!-- page* -->
<?php if ($Page->course_id->Visible) { // course_id ?>
    <div id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <label id="elh_semester_courses_course_id" for="x_course_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->course_id->caption() ?><?= $Page->course_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->course_id->cellAttributes() ?>>
<span id="el_semester_courses_course_id">
<input type="<?= $Page->course_id->getInputTextType() ?>" name="x_course_id" id="x_course_id" data-table="semester_courses" data-field="x_course_id" value="<?= $Page->course_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->course_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->course_id->formatPattern()) ?>"<?= $Page->course_id->editAttributes() ?> aria-describedby="x_course_id_help">
<?= $Page->course_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->course_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->semester->Visible) { // semester ?>
    <div id="r_semester"<?= $Page->semester->rowAttributes() ?>>
        <label id="elh_semester_courses_semester" for="x_semester" class="<?= $Page->LeftColumnClass ?>"><?= $Page->semester->caption() ?><?= $Page->semester->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->semester->cellAttributes() ?>>
<span id="el_semester_courses_semester">
<input type="<?= $Page->semester->getInputTextType() ?>" name="x_semester" id="x_semester" data-table="semester_courses" data-field="x_semester" value="<?= $Page->semester->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->semester->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->semester->formatPattern()) ?>"<?= $Page->semester->editAttributes() ?> aria-describedby="x_semester_help">
<?= $Page->semester->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->semester->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
    <div id="r_created_at"<?= $Page->created_at->rowAttributes() ?>>
        <label id="elh_semester_courses_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_semester_courses_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="semester_courses" data-field="x_created_at" value="<?= $Page->created_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
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
        "fSemesterCoursesadd",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fSemesterCoursesadd"><?= Language()->phrase("AddBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fSemesterCoursesadd" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("semester_courses");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
