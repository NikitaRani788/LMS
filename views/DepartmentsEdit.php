<?php

namespace PHPMaker2026\Project1;
?>
<?= $Page->getPageHeader() ?>
<?= $Page->getHtmlMessage() ?>
<main class="edit">
<?php $formAction = UrlFor("edit.departments", $Page->getUrlKey(true)) ?>
<form name="fDepartmentsedit" id="fDepartmentsedit" class="<?= $Page->FormClassName ?>" action="<?= $formAction ?>" method="post" novalidate autocomplete="off">
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { departments: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fDepartmentsedit;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fDepartmentsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["id", [fields.id.visible && fields.id.required ? ew.Validators.required(fields.id.caption) : null], fields.id.isInvalid],
            ["dept_name", [fields.dept_name.visible && fields.dept_name.required ? ew.Validators.required(fields.dept_name.caption) : null], fields.dept_name.isInvalid]
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
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="departments">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->id->Visible) { // id ?>
    <div id="r_id"<?= $Page->id->rowAttributes() ?>>
        <label id="elh_departments_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->id->caption() ?><?= $Page->id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->id->cellAttributes() ?>>
<span id="el_departments_id">
<span<?= $Page->id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= $Page->id->getDisplayValue($Page->id->getEditValue()) ?>"></span>
<input type="hidden" data-table="departments" data-field="x_id" data-hidden="1" name="x_id" id="x_id" value="<?= HtmlEncode(ConvertToString($Page->id->CurrentValue)) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->dept_name->Visible) { // dept_name ?>
    <div id="r_dept_name"<?= $Page->dept_name->rowAttributes() ?>>
        <label id="elh_departments_dept_name" for="x_dept_name" class="<?= $Page->LeftColumnClass ?>"><?= $Page->dept_name->caption() ?><?= $Page->dept_name->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->dept_name->cellAttributes() ?>>
<span id="el_departments_dept_name">
<input type="<?= $Page->dept_name->getInputTextType() ?>" name="x_dept_name" id="x_dept_name" data-table="departments" data-field="x_dept_name" value="<?= $Page->dept_name->getEditValue() ?>" size="30" maxlength="100" placeholder="<?= HtmlEncode($Page->dept_name->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->dept_name->formatPattern()) ?>"<?= $Page->dept_name->editAttributes() ?> aria-describedby="x_dept_name_help">
<?= $Page->dept_name->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->dept_name->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
</div><!-- /page* -->
<?= $Page->IsModal ? '<template class="ew-modal-buttons">' : '<div class="row ew-buttons">' ?><!-- buttons .row -->
    <div class="<?= $Page->OffsetColumnClass ?>"><!-- buttons offset -->
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fDepartmentsedit" formaction="<?= $formAction ?>"><?= Language()->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fDepartmentsedit" formaction="<?= $formAction ?>" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("departments");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
