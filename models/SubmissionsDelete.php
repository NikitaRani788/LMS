<?php

namespace PHPMaker2026\Project1;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\OnClearEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\EventStreamResponse;
use Symfony\Component\HttpFoundation\ServerEvent;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsAlias;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemException;
use ParagonIE\CSPBuilder\CSPBuilder;
use InvalidArgumentException;
use Exception;
use Throwable;
use DateTimeInterface;
use DateTimeImmutable;
use DateInterval;
use DateTime;
use Closure;
use Traversable;
use PHPMaker2026\Project1\Entity as BaseEntity;
use PHPMaker2026\Project1\Db;
use PHPMaker2026\Project1\Db\Entity;

/**
 * Page class
 */
#[AsAlias("SubmissionsDelete", true)]
class SubmissionsDelete extends Submissions implements PageInterface
{
    use MessagesTrait;

    // Page result
    public ?Response $Response = null;

    // Headers
    public HeaderBag $Headers;

    // Page ID
    public string $PageID = "delete";

    // Project ID
    public string $ProjectID = PROJECT_ID;

    // View file path
    public ?string $View = null;

    // Title
    public ?string $Title = null; // Title for <title> tag

    // CSS class/style
    public string $CurrentPageName = "SubmissionsDelete"; // Route action

    // Page headings
    public string $Heading = "";
    public string $Subheading = "";
    public string $PageHeader = "";
    public string $PageFooter = "";

    // Page layout
    public bool $UseLayout = true;

    // Page terminated
    private bool $terminated = false;
    public int $RecordCount = 0;
    public array $RecordKeys = [];
    public int $StartRowCount = 1;

    // Constructor
    public function __construct(
        Language $language,
        AdvancedSecurity $security,
        CSPBuilder $cspBuilder,
        CacheInterface $cache,
        FieldFactory $fieldFactory,
        EventDispatcherInterface $dispatcher,
    ) {
        parent::__construct($language, $security, $cspBuilder, $cache, $fieldFactory, $dispatcher);
        global $httpContext;
        $this->Headers = new HeaderBag();
        $this->TableVar = 'submissions';
        $this->TableName = 'submissions';

        // Table CSS class
        $this->TableClass = "table table-bordered table-hover table-sm ew-table";

        // Initialize
        $httpContext["Page"] = $this;

        // Open connection
        $httpContext["Conn"] ??= $this->getConnection();
    }

    // Page heading
    public function pageHeading(): string
    {
        if ($this->Heading != "") {
            return $this->Heading;
        }
        if (method_exists($this, "tableCaption")) {
            return $this->tableCaption();
        }
        return "";
    }

    // Page subheading
    public function pageSubheading(): string
    {
        if ($this->Subheading != "") {
            return $this->Subheading;
        }
        if ($this->TableName) {
            return Language()->phrase($this->PageID);
        }
        return "";
    }

    // Page name
    public function pageName(): string
    {
        return CurrentPageName();
    }

    // Page URL
    public function pageUrl(bool $withArgs = true): string
    {
        if ($withArgs) {
            return CurrentPageUrl();
        } else {
            $route = GetRoute();
            $path = $route?->getPath() ?? "";
            // Remove all placeholders like `{id}`
            $stripped = preg_replace('/\{[^}]+\}/', '', $path);
            // Remove trailing slash unless it's if0_41817906 '/', then replace leading slash with BasePath(true)
            return preg_replace('/^\//', BasePath(true), $stripped !== '/' ? rtrim($stripped, '/') : '/');
        }
    }

    // Get Page Header
    public function getPageHeader(): string
    {
        $header = $this->PageHeader;
        $this->pageDataRendering($header);
        if ($header != "") { // Header exists, display
            $header = '<div id="ew-page-header">' . $header . '</div>';
        }
        return $header;
    }

    // Get Page Footer
    public function getPageFooter(): string
    {
        $footer = $this->PageFooter;
        $this->pageDataRendered($footer);
        if ($footer != "") { // Footer exists, display
            $footer = '<div id="ew-page-footer">' . $footer . '</div>';
        }
        return $footer;
    }

    // Set field visibility
    public function setVisibility(): void
    {
        $this->submission_id->setVisibility();
        $this->assignment_id->setVisibility();
        $this->user_id->setVisibility();
        $this->submission_path->setVisibility();
        $this->submitted_at->setVisibility();
        $this->attempt_no->setVisibility();
        $this->submission_status->setVisibility();
        $this->remarks->setVisibility();
        $this->checked_by->setVisibility();
        $this->marks_obtained->setVisibility();
        $this->checked_at->setVisibility();
    }

    // Is lookup
    public function isLookup(): bool
    {
        return SameText(RouteAction(), Config("API_LOOKUP_ACTION"));
    }

    // Is AutoFill
    public function isAutoFill(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "autofill");
    }

    // Is AutoSuggest
    public function isAutoSuggest(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "autosuggest");
    }

    // Is modal lookup
    public function isModalLookup(): bool
    {
        return $this->isLookup() && SameText(Post("ajax"), "modal");
    }

    // Is terminated
    public function isTerminated(): bool
    {
        return $this->terminated;
    }

    /**
     * Terminate page
     *
     * @param ?string $url URL for redirection
     * @return void
     */
    public function terminate(?string $url = null): void
    {
        if ($this->terminated) {
            return;
        }
        global $httpContext;

        // Page is terminated
        $this->terminated = true;

        // Page Unload event
        if (method_exists($this, "pageUnload")) {
            $this->pageUnload();
        }
        DispatchEvent(new PageUnloadedEvent($this), PageUnloadedEvent::class);
        if (!IsApi() && method_exists($this, "pageRedirecting")) {
            $this->pageRedirecting($url);
        }

        // Return for API
        if (IsApi()) {
            if (!$this->Response) { // Show response for API
                $ar = array_merge($this->getMessages(), $url ? ["url" => GetUrl($url)] : []);
                $this->Response = new JsonResponse($ar);
            }
            $this->clearMessages(); // Clear messages for API request
            return;
        } else { // Check if response is JSON
            if (IsJsonResponse($this->Response)) { // Has JSON response
                $this->clearMessages();
                return;
            }
        }

        // Go to URL if specified
        if ($url !== null) {
            $this->Response = new RedirectResponse(GetUrl($url), Config("REDIRECT_STATUS_CODE"));
        }
        return; // Return to controller
    }

    // Get row(s) from array of entities
    protected function getRowsFromEntities(array $entities, bool $first = false): array
    {
        $rows = [];
        if (array_is_list($entities)) {
            foreach ($entities as $entity) {
                $row = $this->getRowFromEntity($entity);
                if ($first) {
                    return $row;
                } else {
                    $rows[] = $row;
                }
            }
        }
        return $rows;
    }

    // Get row from entity
    protected function getRowFromEntity(BaseEntity $entity): array
    {
        $row = [];
        foreach ($entity as $fldname => $val) {
            if ($this->TableName == Config("USER_TABLE_NAME") && $fldname == Config("PASSWORD_FIELD_NAME")) { // Skip user password field
                continue;
            }
            if (isset($this->Fields[$fldname]) && ($this->Fields[$fldname]->Visible || $this->Fields[$fldname]->IsPrimaryKey)) { // Primary key or Visible
                $fld = $this->Fields[$fldname];
                if ($fld->HtmlTag == "FILE") { // Upload field
                    if (IsEmpty($val)) {
                        $row[$fldname] = null;
                    } else {
                        if ($fld->DataType == DataType::BLOB) {
                            $row[$fldname] = ["type" => ContentType($val)];
                        } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                            $row[$fldname] = ["type" => MimeContentType($val), "name" => $val];
                        } else { // Multiple files
                            $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                            $ar = [];
                            foreach ($files as $file) {
                                if (!IsEmpty($file)) {
                                    $ar[] = ["type" => MimeContentType($file), "name" => $file];
                                }
                            }
                            $row[$fldname] = $ar;
                        }
                    }
                } else {
                    if ($val instanceof DateTimeInterface) {
                        $val = $val->format(DATE_ATOM);
                    }
                    $row[$fldname] = $val;
                }
            }
        }
        return $row;
    }

    // Hide fields for add/edit
    protected function hideFieldsForAddEdit(): void
    {
        if ($this->isAdd() || $this->isCopy() || $this->isGridAdd()) {
            $this->submission_id->Visible = false;
        }
    }

    /**
     * Page init
     *
     * @return void
     */
    public function init(): void
    {
    }

    /**
     * Page run
     *
     * @return void
     */
    public function run(): void
    {
        global $httpContext;

        // Use layout
        $this->UseLayout = $this->UseLayout && ParamBool(Config("PAGE_LAYOUT"), true);

        // View
        $this->View = Get(Config("VIEW"));
        $this->CurrentAction = Param("action"); // Set up current action
        $this->setVisibility();

        // Global Page Loading event (in userfn*.php)
        DispatchEvent(new PageLoadingEvent($this), PageLoadingEvent::class);

        // Page Load event
        if (method_exists($this, "pageLoad")) {
            $this->pageLoad();
        }

        // Hide fields for add/edit
        if (!$this->UseAjaxActions) {
            $this->hideFieldsForAddEdit();
        }
        // Use inline delete
        if ($this->UseAjaxActions) {
            $this->InlineDelete = true;
        }

        // Set up lookup cache
        $this->setupLookupOptions($this->submission_status);

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Check records
        if (empty($this->Records) && !$this->CurrentRecord) {
            $this->terminate("SubmissionsList"); // Prevent SQL injection, return to list
            return;
        }
        $this->Records = count($this->Records) > 0 ? $this->Records : ($this->CurrentRecord ? [$this->CurrentRecord] : []);

        // Get action
        if (IsApi()) {
            $this->CurrentAction = "delete"; // Delete record directly
        } elseif (Param("action") !== null) {
            $this->CurrentAction = Param("action") == "delete" ? "delete" : "show";
        } else {
            $this->CurrentAction = $this->InlineDelete ?
                "delete" : // Delete record directly
                "show"; // Display record
        }
        if ($this->isDelete()) {
            if ($this->deleteRows()) { // Delete rows
                if (!$this->peekSuccessMessage()) {
                    $this->setSuccessMessage($this->language->phrase("DeleteSuccess")); // Set up success message
                }
                if (IsJsonResponse()) {
                    $this->terminate();
                    return;
                } else {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                }
            } else { // Delete failed
                if (IsJsonResponse()) {
                    $this->terminate();
                    return;
                }
                // Return JSON error message if UseAjaxActions
                if ($this->UseAjaxActions) {
                    $this->Response = new JsonResponse(["success" => false, "error" => $this->getFailureMessage()]);
                    $this->terminate();
                    return;
                }
                if ($this->InlineDelete) {
                    $this->terminate($this->getReturnUrl()); // Return to caller
                    return;
                } else {
                    $this->CurrentAction = "show"; // Display record
                }
            }
        }
        if ($this->isShow()) { // Load records for display
            $this->TotalRecords = count($this->Records);
        }

        // Set LoginStatus / Page_Rendering / Page_Render
        if (!IsApi() && !$this->isTerminated()) {
            // Pass login status to client side
            SetClientVar("login", LoginStatus());

            // Global Page Rendering event (in userfn*.php)
            DispatchEvent(new PageRenderingEvent($this), PageRenderingEvent::class);

            // Page Render event
            if (method_exists($this, "pageRender")) {
                $this->pageRender();
            }

            // Render search option
            if (method_exists($this, "renderSearchOptions")) {
                $this->renderSearchOptions();
            }
        }
    }

    /**
     * Get row data
     *
     * @return bool
     */
    public function getRowData(): bool
    {
        if ($row = $this->fetch(++$this->RecordCount)) {
            $this->RowCount++;

            // Get the field contents
            $this->loadRowValues($row);

            // Render row
            $this->renderRow(RowType::VIEW);
            return true;
        }
        return false;
    }

    /**
     * Load entities
     *
     * @param int $offset Offset
     * @param int $rowcnt Maximum number of rows
     * @return array of entity / array
     */
    public function loadRecords(int $offset = -1, int $rowcnt = -1): array
    {
        // Load List page SQL (QueryBuilder)
        $sql = $this->getListSql();

        // Load result set
        if ($offset > -1) {
            $sql->setFirstResult($offset);
        }
        if ($rowcnt > 0) {
            $sql->setMaxResults($rowcnt);
        }
        $entities = $this->loadEntities($sql);

        // Set total number of records
        if (property_exists($this, "TotalRecords") && $rowcnt < 0) {
            $this->TotalRecords = count($entities);
        }

        // Call Records Selected event
        $this->recordsSelected($entities);
        return $entities;
    }

    /**
     * Load row based on key values
     *
     * @return bool
     */
    public function loadRow(): bool
    {
        $result = $this->CurrentRecord !== null;
        if ($result) {
            $this->loadRowValues($this->CurrentRecord); // Load row values
        }
        return $result;
    }

    /**
     * Load row values from result set or record
     *
     * @param ?BaseEntity $row Record
     * @return void
     */
    public function loadRowValues(?BaseEntity $row = null): void
    {
        if ($row instanceof BaseEntity) { // Get array from entity
        }
        $row ??= $this->newRow();

        // Call Row Selected event
        $this->rowSelected($row);
        $this->submission_id->setDbValue($row['submission_id']);
        $this->assignment_id->setDbValue($row['assignment_id']);
        $this->user_id->setDbValue($row['user_id']);
        $this->submission_path->setDbValue($row['submission_path']);
        $this->submitted_at->setDbValue($row['submitted_at']);
        $this->attempt_no->setDbValue($row['attempt_no']);
        $this->submission_status->setDbValue($row['submission_status']);
        $this->remarks->setDbValue($row['remarks']);
        $this->checked_by->setDbValue($row['checked_by']);
        $this->marks_obtained->setDbValue($row['marks_obtained']);
        $this->checked_at->setDbValue($row['checked_at']);
    }

    /**
     * Return a row with default values
     *
     * @return BaseEntity
     */
    protected function newRow(): BaseEntity
    {
        $row = new $this->EntityClass();
        if (!IsEmpty($this->submission_id->DefaultValue)) {
            $row['submission_id'] = intval($this->submission_id->DefaultValue);
        }
        if (!IsEmpty($this->assignment_id->DefaultValue)) {
            $row['assignment_id'] = intval($this->assignment_id->DefaultValue);
        }
        if (!IsEmpty($this->user_id->DefaultValue)) {
            $row['user_id'] = intval($this->user_id->DefaultValue);
        }
        if (!IsEmpty($this->submission_path->DefaultValue)) {
            $row['submission_path'] = strval($this->submission_path->DefaultValue);
        }
        if (!IsEmpty($this->submitted_at->DefaultValue)) {
            $row['submitted_at'] = $this->submitted_at->DefaultValue instanceof DateTimeInterface ? $this->submitted_at->DefaultValue : new DateTimeImmutable($this->submitted_at->DefaultValue);
        }
        if (!IsEmpty($this->attempt_no->DefaultValue)) {
            $row['attempt_no'] = intval($this->attempt_no->DefaultValue);
        }
        if (!IsEmpty($this->submission_status->DefaultValue)) {
            $row['submission_status'] = strval($this->submission_status->DefaultValue);
        }
        if (!IsEmpty($this->remarks->DefaultValue)) {
            $row['remarks'] = strval($this->remarks->DefaultValue);
        }
        if (!IsEmpty($this->checked_by->DefaultValue)) {
            $row['checked_by'] = intval($this->checked_by->DefaultValue);
        }
        if (!IsEmpty($this->marks_obtained->DefaultValue)) {
            $row['marks_obtained'] = intval($this->marks_obtained->DefaultValue);
        }
        if (!IsEmpty($this->checked_at->DefaultValue)) {
            $row['checked_at'] = $this->checked_at->DefaultValue instanceof DateTimeInterface ? $this->checked_at->DefaultValue : new DateTimeImmutable($this->checked_at->DefaultValue);
        }
        return $row;
    }

    /**
     * Render row
     *
     * @param RowType $rowType Row type
     * @param bool $resetAttributes Reset attributes
     * @return void
     */
    public function renderRow(RowType $rowType = RowType::VIEW, bool $resetAttributes = true): void
    {
        global $httpContext;

        // Set up row type
        $this->RowType = $rowType;

        // Reset attributes
        if ($resetAttributes) {
            $this->resetAttributes();
        }

        // Initialize URLs

        // Call Row_Rendering event
        $this->rowRendering();

        // Common render codes for all row types

        // submission_id

        // assignment_id

        // user_id

        // submission_path

        // submitted_at

        // attempt_no

        // submission_status

        // remarks

        // checked_by

        // marks_obtained

        // checked_at

        // View row
        if ($this->RowType == RowType::VIEW) {
            // submission_id
            $this->submission_id->ViewValue = $this->submission_id->CurrentValue;

            // assignment_id
            $this->assignment_id->ViewValue = $this->assignment_id->CurrentValue;
            $this->assignment_id->ViewValue = FormatNumber($this->assignment_id->ViewValue, $this->assignment_id->formatPattern());

            // user_id
            $this->user_id->ViewValue = $this->user_id->CurrentValue;
            $this->user_id->ViewValue = FormatNumber($this->user_id->ViewValue, $this->user_id->formatPattern());

            // submission_path
            $this->submission_path->ViewValue = $this->submission_path->CurrentValue;

            // submitted_at
            $this->submitted_at->ViewValue = $this->submitted_at->CurrentValue;
            $this->submitted_at->ViewValue = FormatDateTime($this->submitted_at->ViewValue, $this->submitted_at->formatPattern());

            // attempt_no
            $this->attempt_no->ViewValue = $this->attempt_no->CurrentValue;
            $this->attempt_no->ViewValue = FormatNumber($this->attempt_no->ViewValue, $this->attempt_no->formatPattern());

            // submission_status
            if (ConvertToString($this->submission_status->CurrentValue) != "") {
                $this->submission_status->ViewValue = $this->submission_status->optionCaption(ConvertToString($this->submission_status->CurrentValue));
            } else {
                $this->submission_status->ViewValue = null;
            }

            // remarks
            $this->remarks->ViewValue = $this->remarks->CurrentValue;

            // checked_by
            $this->checked_by->ViewValue = $this->checked_by->CurrentValue;
            $this->checked_by->ViewValue = FormatNumber($this->checked_by->ViewValue, $this->checked_by->formatPattern());

            // marks_obtained
            $this->marks_obtained->ViewValue = $this->marks_obtained->CurrentValue;
            $this->marks_obtained->ViewValue = FormatNumber($this->marks_obtained->ViewValue, $this->marks_obtained->formatPattern());

            // checked_at
            $this->checked_at->ViewValue = $this->checked_at->CurrentValue;
            $this->checked_at->ViewValue = FormatDateTime($this->checked_at->ViewValue, $this->checked_at->formatPattern());

            // submission_id
            $this->submission_id->HrefValue = "";
            $this->submission_id->TooltipValue = "";

            // assignment_id
            $this->assignment_id->HrefValue = "";
            $this->assignment_id->TooltipValue = "";

            // user_id
            $this->user_id->HrefValue = "";
            $this->user_id->TooltipValue = "";

            // submission_path
            $this->submission_path->HrefValue = "";
            $this->submission_path->TooltipValue = "";

            // submitted_at
            $this->submitted_at->HrefValue = "";
            $this->submitted_at->TooltipValue = "";

            // attempt_no
            $this->attempt_no->HrefValue = "";
            $this->attempt_no->TooltipValue = "";

            // submission_status
            $this->submission_status->HrefValue = "";
            $this->submission_status->TooltipValue = "";

            // remarks
            $this->remarks->HrefValue = "";
            $this->remarks->TooltipValue = "";

            // checked_by
            $this->checked_by->HrefValue = "";
            $this->checked_by->TooltipValue = "";

            // marks_obtained
            $this->marks_obtained->HrefValue = "";
            $this->marks_obtained->TooltipValue = "";

            // checked_at
            $this->checked_at->HrefValue = "";
            $this->checked_at->TooltipValue = "";
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Delete current record
    protected function deleteRow(): ?bool
    {
        $records = $this->Records;
        $this->Records = [];
        try {
            return $this->deleteRows();
        } finally {
            $this->Records = $records;
        }
    }

    // Delete records
    protected function deleteRows(): ?bool
    {
        $rows = count($this->Records) > 0 ? $this->Records : ($this->CurrentRecord ? [$this->CurrentRecord] : []);
        if (count($rows) == 0) {
            $this->setFailureMessage($this->language->phrase("NoRecord")); // No record found
            return false;
        }

        // Get Entity Manager
        $em = $this->getEntityManager();
        if ($this->UseTransaction) {
            $em->beginTransaction();
        }

        // Delete row(s)
        $oldRows = [];
        $successKeys = [];
        $failKeys = [];
        $skipRecords = [];
        $rowindex = 0;
        foreach ($rows as $row) {
            $oldRow = clone $row;
            $oldRows[] = $oldRow;
            $rowindex++;
            $key = $row->identifierValuesAsString();

            // Call row deleting event
            $delete = method_exists($this, "rowDeleting") ? $this->rowDeleting($row) : true;
            if ($delete) { // Delete
                try {
                    $updateTableRow = null;
                    if (!$this->UpdateTable || $this->UpdateTable == $this->TableName) { // Update table is the same as current table
                        $em->remove($row);
                    } else { // Update table is different from current table
                        $id = $row->identifierValues();
                        $updateTableRow = $em->find($this->UpdateTableEntityClass, $id);
                        if (!$updateTableRow) {
                            throw new \RuntimeException("Cannot delete: related entity not found.");
                        }
                        $em->detach($row);
                        $em->remove($updateTableRow);
                    }
                    $em->flush();
                } catch (Exception $e) {
                    $this->dispatcher->dispatch(new RowDeleteFailedEvent($updateTableRow ?? $row, $e));
                    $this->setFailureMessage($e->getMessage());
                    $delete = false;
                }
            }
            if ($delete === null) { // Row skipped
                $skipRecords[] = $rowindex . (!IsEmpty($key) ? ": " . $key : ""); // Record count and key if exists
            } elseif ($delete === false) { // Row not deleted
                if ($this->UseTransaction) {
                    $successKeys = []; // Reset success keys
                    break;
                }
                $failKeys[] = $key;
            } elseif ($delete) { // Row deleted
                if (Config("DELETE_UPLOADED_FILES")) { // Delete old files
                    $this->deleteUploadedFiles($oldRow); // Use old row
                }

                // Call Row Deleted event
                if (method_exists($this, "rowDeleted")) {
                    $this->rowDeleted($oldRow); // Use old row
                }
                $successKeys[] = $key;
            }
        }

        // Any records deleted
        $deleted = count($successKeys) > 0;
        if (!$deleted) {
            // Set up error message
            if ($this->peekSuccessMessage() || $this->peekFailureMessage()) {
                // Use the message, do nothing
            } elseif ($this->CancelMessage != "") {
                $this->setFailureMessage($this->CancelMessage);
                $this->CancelMessage = "";
            } else {
                $this->setFailureMessage($this->language->phrase("DeleteCancelled"));
            }
        }
        if ($deleted) {
            if ($this->UseTransaction) { // Commit transaction
                $em->commit();
            }

            // Set warning message if some records skipped
            if (count($skipRecords) > 0) {
                $this->setWarningMessage(sprintf($this->language->phrase("RecordsSkipped"), count($skipRecords)));
                Log("Records skipped", $skipRecords);
            }

            // Set warning message if delete some records failed
            if (count($failKeys) > 0) {
                $this->setWarningMessage(sprintf($this->language->phrase("DeleteRecordsFailed"), count($successKeys), count($failKeys)));
                Log("Delete records failed", ["success" => $successKeys, "failure" => $failKeys]);
            }
        } else {
            if ($this->UseTransaction) { // Rollback transaction
                $em->rollback();
            }
        }

        // Write JSON response
        if ((IsJsonResponse() || IsInfiniteScroll()) && $deleted) {
            $rows = $this->getRowsFromEntities($oldRows);
            $table = $this->TableVar;
            if (Param("key_m") === null) { // Single delete
                $rows = $rows[0]; // Return object
            }
            $this->Response = new JsonResponse(["success" => true, "action" => Config("API_DELETE_ACTION"), $table => $rows]);
        }
        return $deleted;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SubmissionsList"), "", $this->TableVar, true);
        $pageId = "delete";
        $breadcrumb->add("delete", $pageId, $url);
    }

    // Setup lookup options
    public function setupLookupOptions(DbField $fld): void
    {
        if ($fld->Lookup && $fld->Lookup->Options === null) {
            // Get default connection and filter
            $conn = $this->getConnection();
            $lookupFilter = "";

            // No need to check any more
            $fld->Lookup->Options = [];

            // Set up lookup SQL and connection
            switch ($fld->FieldVar) {
                case "x_submission_status":
                    break;
                default:
                    $lookupFilter = "";
                    break;
            }

            // Always call to Lookup->getSql so that user can setup Lookup->Options in Lookup_Selecting server event
            $qb = $fld->Lookup->getSqlBuilder(false, "", $lookupFilter, $this);

            // Set up lookup cache
            if (!$fld->hasLookupOptions() && $fld->UseLookupCache && $qb != null && count($fld->Lookup->Options) == 0 && count($fld->Lookup->FilterFields) == 0) {
                $totalCnt = $this->getRecordCount($qb, $conn);
                if ($totalCnt > $fld->LookupCacheCount) { // Total count > cache count, do not cache
                    return;
                }

                // Define a structured and consistent cache key prefix
                $cachePrefix = "lookup.result." . Container($fld->Lookup->LinkTable)->TableVar . ".";

                // Generate a unique cache key using SQL and parameters
                $sqlHash = hash("sha256", $qb->getSQL() . serialize($qb->getParameters()));
                $cacheKey = $cachePrefix . $sqlHash;

                // Fetch rows from cache or database
                $rows = $this->cache->get($cacheKey, fn (ItemInterface $item) => $qb->executeQuery()->fetchAllAssociative());
                $ar = [];
                foreach ($rows as $row) {
                    $row = $fld->Lookup->renderViewRow($row);
                    $key = $row["lf"];
                    if (IsFloatType($fld->Type)) { // Handle float field
                        $key = (float)$key;
                    }
                    $ar[strval($key)] = $row;
                }
                $fld->Lookup->Options = $ar;
            }
        }
    }

    // Page Load event
    public function pageLoad(): void
    {
        //Log("Page Load");
    }

    // Page Unload event
    public function pageUnload(): void
    {
        //Log("Page Unload");
    }

    // Page Redirecting event
    public function pageRedirecting(?string &$url): void
    {
        // Example:
        //$url = "your URL";
    }

    // Message Showing event
    // $type = ''|'success'|'danger'|'warning'
    public function messageShowing(string &$message, string $type): void
    {
        if ($type == "success") {
            //$message = "your success message";
        } elseif ($type == "danger") {
            //$message = "your failure message";
        } elseif ($type == "warning") {
            //$message = "your warning message";
        } else {
            //$message = "your message";
        }
    }

    // Page Render event
    public function pageRender(): void
    {
        //Log("Page Render");
    }

    // Page Data Rendering event
    public function pageDataRendering(string &$header): void
    {
        // Example:
        //$header = "your header";
    }

    // Page Data Rendered event
    public function pageDataRendered(string &$footer): void
    {
        // Example:
        //$footer = "your footer";
    }

    // Page Breaking event
    public function pageBreaking(bool &$break, string &$content): void
    {
        // Example:
        //$break = false; // Skip page break, or
        //$content = "<div style=\"break-after:page;\"></div>"; // Modify page break content
    }
}
