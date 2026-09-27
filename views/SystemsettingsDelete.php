<?php

namespace PHPMaker2026\Project1;
?>
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { systemsettings: currentTable } });
var currentPageID = ew.PAGE_ID = "delete";
var currentForm;
var fSystemsettingsdelete;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fSystemsettingsdelete")
        .setPageId("delete")
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
<form name="fSystemsettingsdelete" id="fSystemsettingsdelete" class="ew-form ew-delete-form" action="<?= CurrentPageUrl(false) ?>" method="post" novalidate autocomplete="off">
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="systemsettings">
<input type="hidden" name="action" id="action" value="delete">
<?php foreach ($Page->Records as $record) { ?>
<input type="hidden" name="key_m[]" value="<?= HtmlEncode($record->identifierValuesAsString()) ?>">
<?php } ?>
<div class="card ew-card ew-grid <?= $Page->TableGridClass ?>">
<div class="card-body ew-grid-middle-panel <?= $Page->TableContainerClass ?>">
<table class="<?= $Page->TableClass ?>">
    <thead>
    <tr class="ew-table-header">
<?php if ($Page->id->Visible) { // id ?>
        <th class="<?= $Page->id->headerCellClass() ?>"><span id="elh_systemsettings_id" class="systemsettings_id"><?= $Page->id->caption() ?></span></th>
<?php } ?>
<?php if ($Page->setting_name->Visible) { // setting_name ?>
        <th class="<?= $Page->setting_name->headerCellClass() ?>"><span id="elh_systemsettings_setting_name" class="systemsettings_setting_name"><?= $Page->setting_name->caption() ?></span></th>
<?php } ?>
<?php if ($Page->setting_value->Visible) { // setting_value ?>
        <th class="<?= $Page->setting_value->headerCellClass() ?>"><span id="elh_systemsettings_setting_value" class="systemsettings_setting_value"><?= $Page->setting_value->caption() ?></span></th>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <th class="<?= $Page->description->headerCellClass() ?>"><span id="elh_systemsettings_description" class="systemsettings_description"><?= $Page->description->caption() ?></span></th>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <th class="<?= $Page->created_at->headerCellClass() ?>"><span id="elh_systemsettings_created_at" class="systemsettings_created_at"><?= $Page->created_at->caption() ?></span></th>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <th class="<?= $Page->updated_at->headerCellClass() ?>"><span id="elh_systemsettings_updated_at" class="systemsettings_updated_at"><?= $Page->updated_at->caption() ?></span></th>
<?php } ?>
    </tr>
    </thead>
    <tbody>
<?php
while ($Page->getRowData()) {
?>
    <tr <?= $Page->rowAttributes() ?>>
<?php if ($Page->id->Visible) { // id ?>
        <td<?= $Page->id->cellAttributes() ?>>
<span id="">
<span<?= $Page->id->viewAttributes() ?>>
<?= $Page->id->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->setting_name->Visible) { // setting_name ?>
        <td<?= $Page->setting_name->cellAttributes() ?>>
<span id="">
<span<?= $Page->setting_name->viewAttributes() ?>>
<?= $Page->setting_name->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->setting_value->Visible) { // setting_value ?>
        <td<?= $Page->setting_value->cellAttributes() ?>>
<span id="">
<span<?= $Page->setting_value->viewAttributes() ?>>
<?= $Page->setting_value->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->description->Visible) { // description ?>
        <td<?= $Page->description->cellAttributes() ?>>
<span id="">
<span<?= $Page->description->viewAttributes() ?>>
<?= $Page->description->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->created_at->Visible) { // created_at ?>
        <td<?= $Page->created_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->created_at->viewAttributes() ?>>
<?= $Page->created_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
<?php if ($Page->updated_at->Visible) { // updated_at ?>
        <td<?= $Page->updated_at->cellAttributes() ?>>
<span id="">
<span<?= $Page->updated_at->viewAttributes() ?>>
<?= $Page->updated_at->getViewValue() ?></span>
</span>
</td>
<?php } ?>
    </tr>
<?php
}
?>
</tbody>
</table>
</div>
</div>
<div class="ew-buttons ew-desktop-buttons">
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit"><?= Language()->phrase("DeleteBtn") ?></button>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
</div>
</form>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
