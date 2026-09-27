<?php

namespace PHPMaker2026\Project1;
?>
<?= $Page->getPageHeader() ?>
<?= $Page->getHtmlMessage() ?>
<main class="edit">
<?php $formAction = UrlFor("edit.announcements", $Page->getUrlKey(true)) ?>
<form name="fAnnouncementsedit" id="fAnnouncementsedit" class="<?= $Page->FormClassName ?>" action="<?= $formAction ?>" method="post" novalidate autocomplete="off">
<script<?= Nonce() ?>>
var currentTable = <?= json_encode($Page->getClientVars()) ?>;
ew.deepAssign(ew.vars, { tables: { announcements: currentTable } });
var currentPageID = ew.PAGE_ID = "edit";
var currentForm;
var fAnnouncementsedit;
ew.on("wrapper", function () {
    let $ = jQuery;
    let fields = currentTable.fields;

    // Form object
    let form = new ew.FormBuilder()
        .setId("fAnnouncementsedit")
        .setPageId("edit")

        // Add fields
        .setFields([
            ["announcement_id", [fields.announcement_id.visible && fields.announcement_id.required ? ew.Validators.required(fields.announcement_id.caption) : null], fields.announcement_id.isInvalid],
            ["course_id", [fields.course_id.visible && fields.course_id.required ? ew.Validators.required(fields.course_id.caption) : null, ew.Validators.integer], fields.course_id.isInvalid],
            ["posted_by", [fields.posted_by.visible && fields.posted_by.required ? ew.Validators.required(fields.posted_by.caption) : null, ew.Validators.integer], fields.posted_by.isInvalid],
            ["title", [fields.title.visible && fields.title.required ? ew.Validators.required(fields.title.caption) : null], fields.title.isInvalid],
            ["_message", [fields._message.visible && fields._message.required ? ew.Validators.required(fields._message.caption) : null], fields._message.isInvalid],
            ["priority", [fields.priority.visible && fields.priority.required ? ew.Validators.required(fields.priority.caption) : null], fields.priority.isInvalid],
            ["status", [fields.status.visible && fields.status.required ? ew.Validators.required(fields.status.caption) : null], fields.status.isInvalid],
            ["created_at", [fields.created_at.visible && fields.created_at.required ? ew.Validators.required(fields.created_at.caption) : null, ew.Validators.datetime(fields.created_at.clientFormatPattern)], fields.created_at.isInvalid]
        ])

        // Use JavaScript validation or not
        .setValidateRequired(ew.CLIENT_VALIDATE)

        // Dynamic selection lists
        .setLists({
            "priority": <?= $Page->priority->toClientList($Page) ?>,
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
<?php if (Config("CSRF_PROTECTION")) { ?>
<input type="hidden" name="<?= $TokenNameKey ?>" value="<?= $TokenName ?>"><!-- CSRF token ID -->
<input type="hidden" name="<?= $TokenValueKey ?>" value="<?= $TokenValue ?>"><!-- CSRF token value -->
<?php } ?>
<input type="hidden" name="t" value="announcements">
<input type="hidden" name="action" id="action" value="update">
<input type="hidden" name="modal" value="<?= (int)$Page->IsModal ?>">
<?php if (IsJsonResponse()) { ?>
<input type="hidden" name="json" value="1">
<?php } ?>
<input type="hidden" name="<?= $Page->getFormOldKeyName() ?>" value="<?= $Page->getOldKeyAsString() ?>">
<div class="ew-edit-div"><!-- page* -->
<?php if ($Page->announcement_id->Visible) { // announcement_id ?>
    <div id="r_announcement_id"<?= $Page->announcement_id->rowAttributes() ?>>
        <label id="elh_announcements_announcement_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->announcement_id->caption() ?><?= $Page->announcement_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->announcement_id->cellAttributes() ?>>
<span id="el_announcements_announcement_id">
<span<?= $Page->announcement_id->viewAttributes() ?>>
<input type="text" readonly class="form-control-plaintext" value="<?= $Page->announcement_id->getDisplayValue($Page->announcement_id->getEditValue()) ?>"></span>
<input type="hidden" data-table="announcements" data-field="x_announcement_id" data-hidden="1" name="x_announcement_id" id="x_announcement_id" value="<?= HtmlEncode(ConvertToString($Page->announcement_id->CurrentValue)) ?>">
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->course_id->Visible) { // course_id ?>
    <div id="r_course_id"<?= $Page->course_id->rowAttributes() ?>>
        <label id="elh_announcements_course_id" for="x_course_id" class="<?= $Page->LeftColumnClass ?>"><?= $Page->course_id->caption() ?><?= $Page->course_id->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->course_id->cellAttributes() ?>>
<span id="el_announcements_course_id">
<input type="<?= $Page->course_id->getInputTextType() ?>" name="x_course_id" id="x_course_id" data-table="announcements" data-field="x_course_id" value="<?= $Page->course_id->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->course_id->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->course_id->formatPattern()) ?>"<?= $Page->course_id->editAttributes() ?> aria-describedby="x_course_id_help">
<?= $Page->course_id->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->course_id->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->posted_by->Visible) { // posted_by ?>
    <div id="r_posted_by"<?= $Page->posted_by->rowAttributes() ?>>
        <label id="elh_announcements_posted_by" for="x_posted_by" class="<?= $Page->LeftColumnClass ?>"><?= $Page->posted_by->caption() ?><?= $Page->posted_by->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->posted_by->cellAttributes() ?>>
<span id="el_announcements_posted_by">
<input type="<?= $Page->posted_by->getInputTextType() ?>" name="x_posted_by" id="x_posted_by" data-table="announcements" data-field="x_posted_by" value="<?= $Page->posted_by->getEditValue() ?>" size="30" placeholder="<?= HtmlEncode($Page->posted_by->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->posted_by->formatPattern()) ?>"<?= $Page->posted_by->editAttributes() ?> aria-describedby="x_posted_by_help">
<?= $Page->posted_by->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->posted_by->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->title->Visible) { // title ?>
    <div id="r_title"<?= $Page->title->rowAttributes() ?>>
        <label id="elh_announcements_title" for="x_title" class="<?= $Page->LeftColumnClass ?>"><?= $Page->title->caption() ?><?= $Page->title->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->title->cellAttributes() ?>>
<span id="el_announcements_title">
<input type="<?= $Page->title->getInputTextType() ?>" name="x_title" id="x_title" data-table="announcements" data-field="x_title" value="<?= $Page->title->getEditValue() ?>" size="30" maxlength="150" placeholder="<?= HtmlEncode($Page->title->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->title->formatPattern()) ?>"<?= $Page->title->editAttributes() ?> aria-describedby="x_title_help">
<?= $Page->title->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->title->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->_message->Visible) { // message ?>
    <div id="r__message"<?= $Page->_message->rowAttributes() ?>>
        <label id="elh_announcements__message" for="x__message" class="<?= $Page->LeftColumnClass ?>"><?= $Page->_message->caption() ?><?= $Page->_message->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->_message->cellAttributes() ?>>
<span id="el_announcements__message">
<input type="<?= $Page->_message->getInputTextType() ?>" name="x__message" id="x__message" data-table="announcements" data-field="x__message" value="<?= $Page->_message->getEditValue() ?>" size="30" maxlength="65535" placeholder="<?= HtmlEncode($Page->_message->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->_message->formatPattern()) ?>"<?= $Page->_message->editAttributes() ?> aria-describedby="x__message_help">
<?= $Page->_message->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->_message->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->priority->Visible) { // priority ?>
    <div id="r_priority"<?= $Page->priority->rowAttributes() ?>>
        <label id="elh_announcements_priority" class="<?= $Page->LeftColumnClass ?>"><?= $Page->priority->caption() ?><?= $Page->priority->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->priority->cellAttributes() ?>>
<span id="el_announcements_priority">
<template id="tp_x_priority">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="announcements" data-field="x_priority" name="x_priority" id="x_priority"<?= $Page->priority->editAttributes() ?>>
        <label class="form-check-label"></label>
    </div>
</template>
<div id="dsl_x_priority" class="ew-item-list"></div>
<selection-list hidden
    id="x_priority"
    name="x_priority"
    value="<?= HtmlEncode($Page->priority->CurrentValue) ?>"
    data-type="select-one"
    data-template="tp_x_priority"
    data-target="dsl_x_priority"
    data-repeatcolumn="5"
    class="form-control<?= $Page->priority->isInvalidClass() ?>"
    data-table="announcements"
    data-field="x_priority"
    data-value-separator="<?= $Page->priority->displayValueSeparatorAttribute() ?>"
    <?= $Page->priority->editAttributes() ?>></selection-list>
<?= $Page->priority->getCustomMessage() ?>
<div class="invalid-feedback"><?= $Page->priority->getErrorMessage() ?></div>
</span>
</div></div>
    </div>
<?php } ?>
<?php if ($Page->status->Visible) { // status ?>
    <div id="r_status"<?= $Page->status->rowAttributes() ?>>
        <label id="elh_announcements_status" class="<?= $Page->LeftColumnClass ?>"><?= $Page->status->caption() ?><?= $Page->status->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->status->cellAttributes() ?>>
<span id="el_announcements_status">
<template id="tp_x_status">
    <div class="form-check">
        <input type="radio" class="form-check-input" data-table="announcements" data-field="x_status" name="x_status" id="x_status"<?= $Page->status->editAttributes() ?>>
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
    data-table="announcements"
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
        <label id="elh_announcements_created_at" for="x_created_at" class="<?= $Page->LeftColumnClass ?>"><?= $Page->created_at->caption() ?><?= $Page->created_at->Required ? Language()->phrase("FieldRequiredIndicator") : "" ?></label>
        <div class="<?= $Page->RightColumnClass ?>"><div<?= $Page->created_at->cellAttributes() ?>>
<span id="el_announcements_created_at">
<input type="<?= $Page->created_at->getInputTextType() ?>" name="x_created_at" id="x_created_at" data-table="announcements" data-field="x_created_at" value="<?= $Page->created_at->getEditValue() ?>" placeholder="<?= HtmlEncode($Page->created_at->getPlaceHolder()) ?>" data-format-pattern="<?= HtmlEncode($Page->created_at->formatPattern()) ?>"<?= $Page->created_at->editAttributes() ?> aria-describedby="x_created_at_help">
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
        "fAnnouncementsedit",
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
<button class="btn btn-primary ew-btn ew-submit" name="btn-action" id="btn-action" type="submit" form="fAnnouncementsedit" formaction="<?= $formAction ?>"><?= Language()->phrase("SaveBtn") ?></button>
<?php if (IsJsonResponse()) { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" data-bs-dismiss="modal"><?= Language()->phrase("CancelBtn") ?></button>
<?php } else { ?>
<button class="btn btn-default ew-btn" name="btn-cancel" id="btn-cancel" type="button" form="fAnnouncementsedit" formaction="<?= $formAction ?>" data-href="<?= HtmlEncode(GetUrl($Page->getReturnUrl())) ?>"><?= Language()->phrase("CancelBtn") ?></button>
<?php } ?>
    </div><!-- /buttons offset -->
<?= $Page->IsModal ? "</template>" : "</div>" ?><!-- /buttons .row -->
</form>
</main>
<?= $Page->getPageFooter() ?>
<script<?= Nonce() ?>>
// Field event handlers
ew.on("head", function() {
    ew.addEventHandlers("announcements");
});
</script>
<script<?= Nonce() ?>>
ew.on("load", function () {
    // Write your table-specific startup script here, no need to add script tags.
});
</script>
