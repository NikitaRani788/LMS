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
#[AsAlias("AssignmentsAdd", true)]
class AssignmentsAdd extends Assignments implements PageInterface
{
    use MessagesTrait;
    use FormTrait;

    // Page result
    public ?Response $Response = null;

    // Headers
    public HeaderBag $Headers;

    // Page ID
    public string $PageID = "add";

    // Project ID
    public string $ProjectID = PROJECT_ID;

    // View file path
    public ?string $View = null;

    // Title
    public ?string $Title = null; // Title for <title> tag

    // CSS class/style
    public string $CurrentPageName = "AssignmentsAdd"; // Route action

    // Page headings
    public string $Heading = "";
    public string $Subheading = "";
    public string $PageHeader = "";
    public string $PageFooter = "";

    // Page layout
    public bool $UseLayout = true;

    // Page terminated
    private bool $terminated = false;
    public string $FormClassName = "ew-form ew-add-form";
    public bool $IsModal = false;
    public bool $IsMobileOrModal = false;
    public int $Priv = 0;
    public bool $CopyRecord = false;
    public array $DetailGrids = [];

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
        $this->TableVar = 'assignments';
        $this->TableName = 'assignments';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-add-table";

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
        $this->assignment_id->Visible = false;
        $this->course_id->setVisibility();
        $this->assigned_by->setVisibility();
        $this->title->setVisibility();
        $this->description->setVisibility();
        $this->assignment_type->setVisibility();
        $this->due_date->setVisibility();
        $this->max_marks->setVisibility();
        $this->attachment_path->setVisibility();
        $this->status->setVisibility();
        $this->created_at->setVisibility();
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
            // Handle modal response
            if ($this->IsModal) { // Show as modal
                $pageName = GetPageName($url);
                $result = ["url" => GetUrl($url), "modal" => "1"];  // Assume return to modal for simplicity
                if (
                    SameString($pageName, GetPageName($this->getListUrl()))
                    || SameString($pageName, GetPageName($this->getViewUrl()))
                    || SameString($pageName, GetPageName(CurrentMasterTable()?->getViewUrl() ?? ""))
                ) { // List / View / Master View page
                    if (!SameString($pageName, GetPageName($this->getListUrl()))) { // Not List page
                        $result["caption"] = $this->getModalCaption($pageName);
                        $result["view"] = SameString($pageName, "AssignmentsView"); // If View page, no primary button
                    } else { // List page
                        $result["error"] = $this->getFailureMessage(); // List page should not be shown as modal => error
                    }
                } else { // Other pages (add messages and then clear messages)
                    $result = array_merge($this->getMessages(), ["modal" => "1"]);
                    // $this->clearMessages();
                }
                $this->Response = new JsonResponse($result);
            } else {
                $this->Response = new RedirectResponse(GetUrl($url), Config("REDIRECT_STATUS_CODE"));
            }
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
                            $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                "/" . $fld->TableVar . "/" . $fld->Param . "/" . $this->getKeyAsString($entity, Config("ROUTE_COMPOSITE_KEY_SEPARATOR"))));
                            $row[$fldname] = ["type" => ContentType($val), "url" => $url, "name" => $fld->Param . ContentExtension($val)];
                        } elseif (!$fld->UploadMultiple || !ContainsString($val, Config("MULTIPLE_UPLOAD_SEPARATOR"))) { // Single file
                            $key = SessionId() . ServerVar("ENCRYPTION_KEY");
                            $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                "/" . $fld->TableVar . "/" . Encrypt($fld->uploadPath() . $val, $key)));
                            $row[$fldname] = ["type" => MimeContentType($val), "url" => $url, "name" => $val];
                        } else { // Multiple files
                            $files = explode(Config("MULTIPLE_UPLOAD_SEPARATOR"), $val);
                            $ar = [];
                            $key = SessionId() . ServerVar("ENCRYPTION_KEY");
                            foreach ($files as $file) {
                                if (!IsEmpty($file)) {
                                    $url = FullUrl(GetApiUrl(Config("API_FILE_ACTION") .
                                        "/" . $fld->TableVar . "/" . Encrypt($fld->uploadPath() . $file, $key)));
                                    $ar[] = ["type" => MimeContentType($file), "url" => $url, "name" => $file];
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
            $this->assignment_id->Visible = false;
        }
    }

    // Lookup data
    public function lookup(array $req = []): array
    {
        // Get lookup object
        $fieldName = $req["field"] ?? null;
        if (!$fieldName) {
            return [];
        }
        $fld = $this->Fields[$fieldName];
        $lookup = $fld->Lookup;
        $name = $req["name"] ?? "";
        if (ContainsString($name, "query_builder_rule")) {
            $lookup->FilterFields = []; // Skip parent fields if any
        }

        // Get lookup parameters
        $lookupType = $req["ajax"] ?? "unknown";
        $pageSize = -1;
        $offset = -1;
        $searchValue = "";
        if (SameText($lookupType, "modal") || SameText($lookupType, "filter")) {
            $searchValue = $req["q"] ?? $req["sv"] ?? "";
            $pageSize = $req["n"] ?? $req["recperpage"] ?? 10;
        } elseif (SameText($lookupType, "autosuggest")) {
            $searchValue = $req["q"] ?? "";
            $pageSize = $req["n"] ?? -1;
            $pageSize = is_numeric($pageSize) ? (int)$pageSize : -1;
            if ($pageSize <= 0) {
                $pageSize = Config("AUTO_SUGGEST_MAX_ENTRIES");
            }
        }
        $start = $req["start"] ?? -1;
        $start = is_numeric($start) ? (int)$start : -1;
        $page = $req["page"] ?? -1;
        $page = is_numeric($page) ? (int)$page : -1;
        $offset = $start >= 0 ? $start : ($page > 0 && $pageSize > 0 ? ($page - 1) * $pageSize : 0);
        $userSelect = Decrypt($req["s"] ?? "");
        $userFilter = Decrypt($req["f"] ?? "");
        $userOrderBy = Decrypt($req["o"] ?? "");
        $keys = $req["keys"] ?? null;
        $lookup->LookupType = $lookupType; // Lookup type
        $lookup->FilterValues = []; // Clear filter values first
        if ($keys !== null) { // Selected records from modal
            if (is_array($keys)) {
                $keys = implode(Config("MULTIPLE_OPTION_SEPARATOR"), $keys);
            }
            $lookup->FilterFields = []; // Skip parent fields if any
            $lookup->FilterValues[] = $keys; // Lookup values
            $pageSize = -1; // Show all records
        } else { // Lookup values
            $lookup->FilterValues[] = $req["v0"] ?? $req["lookupValue"] ?? "";
        }
        $cnt = is_array($lookup->FilterFields) ? count($lookup->FilterFields) : 0;
        for ($i = 1; $i <= $cnt; $i++) {
            $lookup->FilterValues[] = $req["v" . $i] ?? "";
        }
        $lookup->SearchValue = $searchValue;
        $lookup->PageSize = $pageSize;
        $lookup->Offset = $offset;
        if ($userSelect != "") {
            $lookup->UserSelect = $userSelect;
        }
        if ($userFilter != "") {
            $lookup->UserFilter = $userFilter;
        }
        if ($userOrderBy != "") {
            $lookup->UserOrderBy = $userOrderBy;
        }
        return $lookup->toJson($this); // Use settings from current page
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

        // Is modal
        $this->IsModal = IsModal();
        $this->UseLayout = $this->UseLayout && !$this->IsModal;

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
        $this->setupLookupOptions($this->assignment_type);
        $this->setupLookupOptions($this->status);

        // Load default values for add
        $this->loadDefaultValues();

        // Check modal
        if ($this->IsModal) {
            $httpContext["SkipHeaderFooter"] = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $postBack = false;

        // Set up current action
        if (IsApi()) {
            $this->CurrentAction = "insert"; // Add record directly
            $postBack = true;
        } elseif (Post("action", "") !== "") {
            $this->CurrentAction = Post("action"); // Get form action
            $this->setKey($this->getFormOldKey());
            $postBack = true;
        } else {
            // Load key values from query string
            if (($keyValue = Route("assignmentId") ?? Get("assignment_id")) !== null) {
                $this->assignment_id->setQueryStringValue($keyValue);
            } elseif (IsApi() && ($keyValue = Key(0)) !== null) {
                $this->assignment_id->setQueryStringValue($keyValue);
            }
            $this->OldKey = $this->getKey(true); // Get key from CurrentValue
            $this->CopyRecord = !IsEmpty($this->OldKey);
            if ($this->CopyRecord) {
                $this->CurrentAction = "copy"; // Copy record
                $this->setKey($this->OldKey); // Set up record key
            } else {
                $this->CurrentAction = "show"; // Display blank record
            }
        }

        // Load old record or default values
        $oldRow = $this->loadOldRecord();

        // Load form values
        if ($postBack) {
            $this->loadFormValues(); // Load form values
        }

        // Validate form if post back
        if ($postBack) {
            if (!$this->validateForm()) {
                $this->EventCancelled = true; // Event cancelled
                if (IsApi()) {
                    $this->Response = new JsonResponse(["success" => false, "version" => PRODUCT_VERSION, "validation" => $this->getValidationErrors()]);
                    $this->terminate();
                    return;
                } else {
                    $this->restoreFormValues(); // Restore form values
                    $this->CurrentAction = "show"; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "copy": // Copy an existing record
                if (!$oldRow) { // Record not loaded
                    if (!$this->peekFailureMessage()) {
                        $this->setFailureMessage($this->language->phrase("NoRecord")); // No record found
                    }
                    $this->terminate("AssignmentsList"); // No matching record, return to list
                    return;
                }
                break;
            case "insert": // Add new record
                if ($newRow = $this->addRow($oldRow)) { // Add successful
                    CleanUploadTempPaths(SessionId());
                    if (!$this->peekSuccessMessage() && Post("addopt") != "1") { // Skip success message for addopt (done in JavaScript)
                        $this->setSuccessMessage($this->language->phrase("AddSuccess")); // Set up success message
                    }
                    $returnUrl = $this->getReturnUrl();
                    if (GetPageName($returnUrl) == "AssignmentsList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "AssignmentsView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "AssignmentsList") {
                            FlashBag()->add("X-Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "AssignmentsList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate();
                    } else {
                        if ($this->IsModal && GetPageName($returnUrl) != "AssignmentsList") {
                            $this->IsModal = false;
                            FlashBag()->add("X-Refresh-Url", GetUrl("AssignmentsList")); // Refresh page after add before going to return page
                            $returnUrl = BuildUrl($returnUrl, "modal=1"); // Redirection, but BaseController will only add the header if not redirection.
                        }
                        $this->terminate($returnUrl); // Return to caller
                    }
                    return;
                } elseif (IsApi()) { // API request, return
                    $this->terminate();
                    return;
                } elseif ($this->IsModal && $this->UseAjaxActions) { // Return JSON error message
                    $this->Response = new JsonResponse(["success" => false, "validation" => $this->getValidationErrors(), "error" => $this->getFailureMessage()]);
                    $this->terminate();
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Add failed, restore form values
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row
        $this->renderRow(
            RowType::ADD
        );

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

    // Get upload files
    protected function getUploadFiles(): void
    {
    }

    // Load default values
    protected function loadDefaultValues(): void
    {
    }

    // Load form values
    protected function loadFormValues(): void
    {
        $validate = !Config("SERVER_VALIDATE");

        // course_id
        if (!$this->course_id->IsDetailKey) {
            $val = $this->hasInputValue($this->course_id) ? $this->getInputValue($this->course_id) : null;
            $this->course_id->setFormValue($val, true, $validate);
        }

        // assigned_by
        if (!$this->assigned_by->IsDetailKey) {
            $val = $this->hasInputValue($this->assigned_by) ? $this->getInputValue($this->assigned_by) : null;
            $this->assigned_by->setFormValue($val, true, $validate);
        }

        // title
        if (!$this->title->IsDetailKey) {
            $val = $this->hasInputValue($this->title) ? $this->getInputValue($this->title) : null;
            $this->title->setFormValue($val);
        }

        // description
        if (!$this->description->IsDetailKey) {
            $val = $this->hasInputValue($this->description) ? $this->getInputValue($this->description) : null;
            $this->description->setFormValue($val);
        }

        // assignment_type
        if (!$this->assignment_type->IsDetailKey) {
            $val = $this->hasInputValue($this->assignment_type) ? $this->getInputValue($this->assignment_type) : null;
            $this->assignment_type->setFormValue($val);
        }

        // due_date
        if (!$this->due_date->IsDetailKey) {
            $val = $this->hasInputValue($this->due_date) ? $this->getInputValue($this->due_date) : null;
            $this->due_date->setFormValue($val, true, $validate);
            $this->due_date->CurrentValue = UnformatDateTime($this->due_date->CurrentValue, $this->due_date->formatPattern());
        }

        // max_marks
        if (!$this->max_marks->IsDetailKey) {
            $val = $this->hasInputValue($this->max_marks) ? $this->getInputValue($this->max_marks) : null;
            $this->max_marks->setFormValue($val, true, $validate);
        }

        // attachment_path
        if (!$this->attachment_path->IsDetailKey) {
            $val = $this->hasInputValue($this->attachment_path) ? $this->getInputValue($this->attachment_path) : null;
            $this->attachment_path->setFormValue($val);
        }

        // status
        if (!$this->status->IsDetailKey) {
            $val = $this->hasInputValue($this->status) ? $this->getInputValue($this->status) : null;
            $this->status->setFormValue($val);
        }

        // created_at
        if (!$this->created_at->IsDetailKey) {
            $val = $this->hasInputValue($this->created_at) ? $this->getInputValue($this->created_at) : null;
            $this->created_at->setFormValue($val, true, $validate);
            $this->created_at->CurrentValue = UnformatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        }

        // assignment_id
        $val = $this->getInputValue($this->assignment_id);
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->course_id->CurrentValue = $this->course_id->FormValue;
        $this->assigned_by->CurrentValue = $this->assigned_by->FormValue;
        $this->title->CurrentValue = $this->title->FormValue;
        $this->description->CurrentValue = $this->description->FormValue;
        $this->assignment_type->CurrentValue = $this->assignment_type->FormValue;
        $this->due_date->CurrentValue = $this->due_date->FormValue;
        $this->due_date->CurrentValue = UnformatDateTime($this->due_date->CurrentValue, $this->due_date->formatPattern());
        $this->max_marks->CurrentValue = $this->max_marks->FormValue;
        $this->attachment_path->CurrentValue = $this->attachment_path->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnformatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
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
        $this->assignment_id->setDbValue($row['assignment_id']);
        $this->course_id->setDbValue($row['course_id']);
        $this->assigned_by->setDbValue($row['assigned_by']);
        $this->title->setDbValue($row['title']);
        $this->description->setDbValue($row['description']);
        $this->assignment_type->setDbValue($row['assignment_type']);
        $this->due_date->setDbValue($row['due_date']);
        $this->max_marks->setDbValue($row['max_marks']);
        $this->attachment_path->setDbValue($row['attachment_path']);
        $this->status->setDbValue($row['status']);
        $this->created_at->setDbValue($row['created_at']);
    }

    /**
     * Return a row with default values
     *
     * @return BaseEntity
     */
    protected function newRow(): BaseEntity
    {
        $row = new $this->EntityClass();
        if (!IsEmpty($this->assignment_id->DefaultValue)) {
            $row['assignment_id'] = intval($this->assignment_id->DefaultValue);
        }
        if (!IsEmpty($this->course_id->DefaultValue)) {
            $row['course_id'] = intval($this->course_id->DefaultValue);
        }
        if (!IsEmpty($this->assigned_by->DefaultValue)) {
            $row['assigned_by'] = intval($this->assigned_by->DefaultValue);
        }
        if (!IsEmpty($this->title->DefaultValue)) {
            $row['title'] = strval($this->title->DefaultValue);
        }
        if (!IsEmpty($this->description->DefaultValue)) {
            $row['description'] = strval($this->description->DefaultValue);
        }
        if (!IsEmpty($this->assignment_type->DefaultValue)) {
            $row['assignment_type'] = strval($this->assignment_type->DefaultValue);
        }
        if (!IsEmpty($this->due_date->DefaultValue)) {
            $row['due_date'] = $this->due_date->DefaultValue instanceof DateTimeInterface ? $this->due_date->DefaultValue : new DateTimeImmutable($this->due_date->DefaultValue);
        }
        if (!IsEmpty($this->max_marks->DefaultValue)) {
            $row['max_marks'] = intval($this->max_marks->DefaultValue);
        }
        if (!IsEmpty($this->attachment_path->DefaultValue)) {
            $row['attachment_path'] = strval($this->attachment_path->DefaultValue);
        }
        if (!IsEmpty($this->status->DefaultValue)) {
            $row['status'] = strval($this->status->DefaultValue);
        }
        if (!IsEmpty($this->created_at->DefaultValue)) {
            $row['created_at'] = $this->created_at->DefaultValue instanceof DateTimeInterface ? $this->created_at->DefaultValue : new DateTimeImmutable($this->created_at->DefaultValue);
        }
        return $row;
    }

    // Load old record
    protected function loadOldRecord(): ?object
    {
        if ($this->CurrentRecord !== null) {
            $this->loadRowValues($this->CurrentRecord);
            return $this->CurrentRecord;
        }
        $this->loadRowValues(); // Load default row values
        return null;
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

        // assignment_id
        $this->assignment_id->RowCssClass = "row";

        // course_id
        $this->course_id->RowCssClass = "row";

        // assigned_by
        $this->assigned_by->RowCssClass = "row";

        // title
        $this->title->RowCssClass = "row";

        // description
        $this->description->RowCssClass = "row";

        // assignment_type
        $this->assignment_type->RowCssClass = "row";

        // due_date
        $this->due_date->RowCssClass = "row";

        // max_marks
        $this->max_marks->RowCssClass = "row";

        // attachment_path
        $this->attachment_path->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // assignment_id
            $this->assignment_id->ViewValue = $this->assignment_id->CurrentValue;

            // course_id
            $this->course_id->ViewValue = $this->course_id->CurrentValue;
            $this->course_id->ViewValue = FormatNumber($this->course_id->ViewValue, $this->course_id->formatPattern());

            // assigned_by
            $this->assigned_by->ViewValue = $this->assigned_by->CurrentValue;
            $this->assigned_by->ViewValue = FormatNumber($this->assigned_by->ViewValue, $this->assigned_by->formatPattern());

            // title
            $this->title->ViewValue = $this->title->CurrentValue;

            // description
            $this->description->ViewValue = $this->description->CurrentValue;

            // assignment_type
            if (ConvertToString($this->assignment_type->CurrentValue) != "") {
                $this->assignment_type->ViewValue = $this->assignment_type->optionCaption(ConvertToString($this->assignment_type->CurrentValue));
            } else {
                $this->assignment_type->ViewValue = null;
            }

            // due_date
            $this->due_date->ViewValue = $this->due_date->CurrentValue;
            $this->due_date->ViewValue = FormatDateTime($this->due_date->ViewValue, $this->due_date->formatPattern());

            // max_marks
            $this->max_marks->ViewValue = $this->max_marks->CurrentValue;
            $this->max_marks->ViewValue = FormatNumber($this->max_marks->ViewValue, $this->max_marks->formatPattern());

            // attachment_path
            $this->attachment_path->ViewValue = $this->attachment_path->CurrentValue;

            // status
            if (ConvertToString($this->status->CurrentValue) != "") {
                $this->status->ViewValue = $this->status->optionCaption(ConvertToString($this->status->CurrentValue));
            } else {
                $this->status->ViewValue = null;
            }

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // course_id
            $this->course_id->HrefValue = "";

            // assigned_by
            $this->assigned_by->HrefValue = "";

            // title
            $this->title->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // assignment_type
            $this->assignment_type->HrefValue = "";

            // due_date
            $this->due_date->HrefValue = "";

            // max_marks
            $this->max_marks->HrefValue = "";

            // attachment_path
            $this->attachment_path->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // course_id
            $this->course_id->setupEditAttributes();
            $this->course_id->EditValue = $this->course_id->CurrentValue;
            $this->course_id->PlaceHolder = RemoveHtml($this->course_id->caption());
            if (strval($this->course_id->EditValue) != "" && is_numeric($this->course_id->EditValue)) {
                $this->course_id->EditValue = FormatNumber($this->course_id->EditValue, null);
            }

            // assigned_by
            $this->assigned_by->setupEditAttributes();
            $this->assigned_by->EditValue = $this->assigned_by->CurrentValue;
            $this->assigned_by->PlaceHolder = RemoveHtml($this->assigned_by->caption());
            if (strval($this->assigned_by->EditValue) != "" && is_numeric($this->assigned_by->EditValue)) {
                $this->assigned_by->EditValue = FormatNumber($this->assigned_by->EditValue, null);
            }

            // title
            $this->title->setupEditAttributes();
            $this->title->EditValue = !$this->title->Raw ? HtmlDecode($this->title->CurrentValue) : $this->title->CurrentValue;
            $this->title->PlaceHolder = RemoveHtml($this->title->caption());

            // description
            $this->description->setupEditAttributes();
            $this->description->EditValue = !$this->description->Raw ? HtmlDecode($this->description->CurrentValue) : $this->description->CurrentValue;
            $this->description->PlaceHolder = RemoveHtml($this->description->caption());

            // assignment_type
            $this->assignment_type->EditValue = $this->assignment_type->options(false);
            $this->assignment_type->PlaceHolder = RemoveHtml($this->assignment_type->caption());

            // due_date
            $this->due_date->setupEditAttributes();
            $this->due_date->EditValue = FormatDateTime($this->due_date->CurrentValue, $this->due_date->formatPattern());
            $this->due_date->PlaceHolder = RemoveHtml($this->due_date->caption());

            // max_marks
            $this->max_marks->setupEditAttributes();
            $this->max_marks->EditValue = $this->max_marks->CurrentValue;
            $this->max_marks->PlaceHolder = RemoveHtml($this->max_marks->caption());
            if (strval($this->max_marks->EditValue) != "" && is_numeric($this->max_marks->EditValue)) {
                $this->max_marks->EditValue = FormatNumber($this->max_marks->EditValue, null);
            }

            // attachment_path
            $this->attachment_path->setupEditAttributes();
            $this->attachment_path->EditValue = !$this->attachment_path->Raw ? HtmlDecode($this->attachment_path->CurrentValue) : $this->attachment_path->CurrentValue;
            $this->attachment_path->PlaceHolder = RemoveHtml($this->attachment_path->caption());

            // status
            $this->status->EditValue = $this->status->options(false);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // Add refer script

            // course_id
            $this->course_id->HrefValue = "";

            // assigned_by
            $this->assigned_by->HrefValue = "";

            // title
            $this->title->HrefValue = "";

            // description
            $this->description->HrefValue = "";

            // assignment_type
            $this->assignment_type->HrefValue = "";

            // due_date
            $this->due_date->HrefValue = "";

            // max_marks
            $this->max_marks->HrefValue = "";

            // attachment_path
            $this->attachment_path->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";
        }
        if ($this->RowType == RowType::ADD || $this->RowType == RowType::EDIT || $this->RowType == RowType::SEARCH) { // Add/Edit/Search row
            $this->setupFieldTitles();
        }

        // Call Row Rendered event
        if ($this->RowType != RowType::AGGREGATEINIT) {
            $this->rowRendered();
        }
    }

    // Validate form
    protected function validateForm(): bool
    {
        // Check if validation required
        if (!Config("SERVER_VALIDATE")) {
            return true;
        }
        $validateForm = true;
        if ($this->course_id->Visible) {
            if ($this->course_id->Required) {
                if (!$this->course_id->IsDetailKey && IsEmpty($this->course_id->FormValue)) {
                    $this->course_id->addErrorMessage(str_replace("%s", $this->course_id->caption(), $this->course_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->course_id->FormValue)) {
                $this->course_id->addErrorMessage($this->course_id->getErrorMessage(false));
            }
        }
        if ($this->assigned_by->Visible) {
            if ($this->assigned_by->Required) {
                if (!$this->assigned_by->IsDetailKey && IsEmpty($this->assigned_by->FormValue)) {
                    $this->assigned_by->addErrorMessage(str_replace("%s", $this->assigned_by->caption(), $this->assigned_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->assigned_by->FormValue)) {
                $this->assigned_by->addErrorMessage($this->assigned_by->getErrorMessage(false));
            }
        }
        if ($this->title->Visible) {
            if ($this->title->Required) {
                if (!$this->title->IsDetailKey && IsEmpty($this->title->FormValue)) {
                    $this->title->addErrorMessage(str_replace("%s", $this->title->caption(), $this->title->RequiredErrorMessage));
                }
            }
        }
        if ($this->description->Visible) {
            if ($this->description->Required) {
                if (!$this->description->IsDetailKey && IsEmpty($this->description->FormValue)) {
                    $this->description->addErrorMessage(str_replace("%s", $this->description->caption(), $this->description->RequiredErrorMessage));
                }
            }
        }
        if ($this->assignment_type->Visible) {
            if ($this->assignment_type->Required) {
                if ($this->assignment_type->FormValue == "") {
                    $this->assignment_type->addErrorMessage(str_replace("%s", $this->assignment_type->caption(), $this->assignment_type->RequiredErrorMessage));
                }
            }
        }
        if ($this->due_date->Visible) {
            if ($this->due_date->Required) {
                if (!$this->due_date->IsDetailKey && IsEmpty($this->due_date->FormValue)) {
                    $this->due_date->addErrorMessage(str_replace("%s", $this->due_date->caption(), $this->due_date->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->due_date->FormValue, $this->due_date->formatPattern())) {
                $this->due_date->addErrorMessage($this->due_date->getErrorMessage(false));
            }
        }
        if ($this->max_marks->Visible) {
            if ($this->max_marks->Required) {
                if (!$this->max_marks->IsDetailKey && IsEmpty($this->max_marks->FormValue)) {
                    $this->max_marks->addErrorMessage(str_replace("%s", $this->max_marks->caption(), $this->max_marks->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->max_marks->FormValue)) {
                $this->max_marks->addErrorMessage($this->max_marks->getErrorMessage(false));
            }
        }
        if ($this->attachment_path->Visible) {
            if ($this->attachment_path->Required) {
                if (!$this->attachment_path->IsDetailKey && IsEmpty($this->attachment_path->FormValue)) {
                    $this->attachment_path->addErrorMessage(str_replace("%s", $this->attachment_path->caption(), $this->attachment_path->RequiredErrorMessage));
                }
            }
        }
        if ($this->status->Visible) {
            if ($this->status->Required) {
                if ($this->status->FormValue == "") {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
                }
            }
        }
        if ($this->created_at->Visible) {
            if ($this->created_at->Required) {
                if (!$this->created_at->IsDetailKey && IsEmpty($this->created_at->FormValue)) {
                    $this->created_at->addErrorMessage(str_replace("%s", $this->created_at->caption(), $this->created_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->created_at->FormValue, $this->created_at->formatPattern())) {
                $this->created_at->addErrorMessage($this->created_at->getErrorMessage(false));
            }
        }

        // Return validate result
        $validateForm = $validateForm && !$this->hasInvalidFields();

        // Call Form_CustomValidate event
        $formCustomError = "";
        $validateForm = $validateForm && $this->formCustomValidate($formCustomError);
        if ($formCustomError != "") {
            $this->setFailureMessage($formCustomError);
        }
        return $validateForm;
    }

    // Add record
    protected function addRow(?BaseEntity $oldRow = null): BaseEntity|false|null
    {
        // Get new row
        $newRow = $this->getAddRow();

        // Validate constraints
        $errors = Validate($newRow);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->fieldByPropertyName($error->getPropertyPath())?->addErrorMessage($error->getMessage());
            }
            return false;
        }

        // Update current values
        $this->Fields->setCurrentValues($newRow);

        // Get Entity Manager
        $em = $this->getEntityManager();

        // Load db values from old row
        $this->loadDbValues($oldRow);

        // Call Row Inserting event
        $insertRow = method_exists($this, "rowInserting") ? $this->rowInserting($oldRow, $newRow) : true;
        if ($insertRow) {
            try {
                $updateTableRow = null;
                if (!$this->UpdateTable || $this->UpdateTable == $this->TableName) { // Update table is the same as current table
                    $em->persist($newRow); // Persist the new row
                } else { // Update table is different from current table
                    $updateTableRow = $this->UpdateTableEntityClass::createFromArray($newRow->toArray());
                    $em->detach($newRow);
                    $em->persist($updateTableRow);
                }
                $em->flush();
                $this->assignment_id->CurrentValue = $newRow->getAssignmentId();
                $addRow = true;
            } catch (Exception $e) {
                $this->dispatcher->dispatch(new RowInsertFailedEvent($updateTableRow ?? $newRow, $e));
                $this->setFailureMessage($e->getMessage());
                $addRow = false;
            }
            if ($addRow) {
            }
        } else {
            if ($insertRow === false) { // Insert failed
                if ($this->peekSuccessMessage() || $this->peekFailureMessage()) {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($this->language->phrase("InsertCancelled"));
                }
            }
            $addRow = $insertRow;
        }
        if ($addRow) {
            // Call Row Inserted event
            if (method_exists($this, "rowInserted")) {
                $this->rowInserted($oldRow, $newRow);
            }
        }

        // Write JSON response
        if (IsJsonResponse() && $addRow) {
            $row = $this->getRowsFromEntities([$newRow], true);
            $table = $this->TableVar;
            $this->Response = new JsonResponse(["success" => true, "action" => Config("API_ADD_ACTION"), $table => $row]);
        }
        return $addRow ? $newRow : $addRow;
    }

    /**
     * Get add row
     *
     * @return object
     */
    protected function getAddRow(): object
    {
        $newRow = new $this->EntityClass();

        // course_id
        $newRow->setCourseId($this->course_id->setDbValueDef($this->course_id->CurrentValue));

        // assigned_by
        $newRow->setAssignedBy($this->assigned_by->setDbValueDef($this->assigned_by->CurrentValue));

        // title
        $newRow->setTitle($this->title->setDbValueDef($this->title->CurrentValue));

        // description
        $newRow->setDescription($this->description->setDbValueDef($this->description->CurrentValue));

        // assignment_type
        if (!IsEmpty(strval($this->assignment_type->CurrentValue))) {
            $newRow->setAssignmentType($this->assignment_type->setDbValueDef($this->assignment_type->CurrentValue));
        }

        // due_date
        $newRow->setDueDate($this->due_date->setDbValueDef(UnFormatDateTime($this->due_date->CurrentValue, $this->due_date->formatPattern())));

        // max_marks
        $newRow->setMaxMarks($this->max_marks->setDbValueDef($this->max_marks->CurrentValue));

        // attachment_path
        $newRow->setAttachmentPath($this->attachment_path->setDbValueDef($this->attachment_path->CurrentValue));

        // status
        if (!IsEmpty(strval($this->status->CurrentValue))) {
            $newRow->setStatus($this->status->setDbValueDef($this->status->CurrentValue));
        }

        // created_at
        $newRow->setCreatedAt($this->created_at->setDbValueDef(UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern())));
        return $newRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("AssignmentsList"), "", $this->TableVar, true);
        $pageId = ($this->isCopy()) ? "Copy" : "Add";
        $breadcrumb->add("add", $pageId, $url);
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
                case "x_assignment_type":
                    break;
                case "x_status":
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

    // Form Custom Validate event
    public function formCustomValidate(string &$customError): bool
    {
        // Return error message in $customError
        return true;
    }
}
