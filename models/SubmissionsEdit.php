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
#[AsAlias("SubmissionsEdit", true)]
class SubmissionsEdit extends Submissions implements PageInterface
{
    use MessagesTrait;
    use FormTrait;

    // Page result
    public ?Response $Response = null;

    // Headers
    public HeaderBag $Headers;

    // Page ID
    public string $PageID = "edit";

    // Project ID
    public string $ProjectID = PROJECT_ID;

    // View file path
    public ?string $View = null;

    // Title
    public ?string $Title = null; // Title for <title> tag

    // CSS class/style
    public string $CurrentPageName = "SubmissionsEdit"; // Route action

    // Page headings
    public string $Heading = "";
    public string $Subheading = "";
    public string $PageHeader = "";
    public string $PageFooter = "";

    // Page layout
    public bool $UseLayout = true;

    // Page terminated
    private bool $terminated = false;

    // Properties
    public string $FormClassName = "ew-form ew-edit-form overlay-wrapper";
    public bool $IsModal = false;
    public bool $IsMobileOrModal = false;
    public ?string $HashValue = null; // Hash Value
    public int $DisplayRecords = 1;
    public bool $EditPaging = false; // Allow edit paging
    public ?int $RecordOffset = null; // Record offset (for Edit paging)
    public array $PagerOptions = ["proximity" => 2, "show_dots" => true];
    public int $RecordCount = 0;
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
        $this->TableVar = 'submissions';
        $this->TableName = 'submissions';

        // Table CSS class
        $this->TableClass = "table table-striped table-bordered table-hover table-sm ew-desktop-table ew-edit-table";

        // Initialize
        $httpContext["Page"] = $this;

        // Open connection
        $httpContext["Conn"] ??= $this->getConnection();

        // Pager options
        if (IsEmpty($this->PagerOptions)) {
            $this->PagerOptions = Config("PAGER_OPTIONS");
        }
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
                        $result["view"] = SameString($pageName, "SubmissionsView"); // If View page, no primary button
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
            $this->submission_id->Visible = false;
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
        $this->setupLookupOptions($this->submission_status);

        // Check modal
        if ($this->IsModal) {
            $httpContext["SkipHeaderFooter"] = true;
        }
        $this->IsMobileOrModal = IsMobile() || $this->IsModal;
        $loaded = $this->CurrentRecord instanceof BaseEntity;
        $postBack = false;

        // Set up current action and primary key
        if (IsApi()) {
            // Load record
            if ($loaded) {
                // Load key values
                if (($keyValue = Route("submissionId") ?? Get("submission_id")) !== null) {
                    $this->submission_id->setQueryStringValue($keyValue);
                    $this->submission_id->setOldValue($this->submission_id->QueryStringValue);
                } elseif (($keyValue = Post("submission_id")) !== null) {
                    $this->submission_id->setFormValue($keyValue);
                    $this->submission_id->setOldValue($this->submission_id->FormValue);
                } elseif (($keyValue = Key(0)) !== null) {
                    $this->submission_id->setQueryStringValue($keyValue);
                    $this->submission_id->setOldValue($this->submission_id->QueryStringValue);
                }
                $loaded = $this->loadRow();
            } else {
                $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                $this->terminate();
                return;
            }
            $this->CurrentAction = "update"; // Update record directly
            $this->OldKey = $this->getKey(true); // Get from CurrentValue
            $postBack = true;
        } else {
            if ($this->CurrentAction = Post("action")) { // Get action code
                if (!$this->isShow()) { // Not reload record, handle as postback
                    $postBack = true;
                }

                // Get key from Form
                $this->setKey($this->getFormOldKey(), $this->isShow());
            } else {
                $this->CurrentAction = "show"; // Default action is display

                // Load key from query string or route value
                $loadByQuery = false;
                if (($keyValue = Route("submissionId") ?? Get("submission_id")) !== null) {
                    $this->submission_id->setQueryStringValue($keyValue);
                    $loadByQuery = true;
                } else {
                    $this->submission_id->CurrentValue = null;
                }
            }

            // Load result set
            if ($this->isShow()) {
                if (!$this->CurrentRecord) { // No record found
                    if (!$this->peekSuccessMessage() && !$this->peekFailureMessage()) {
                        $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                    }
                    $this->terminate("SubmissionsList"); // Return to list page
                    return;
                } else { // Load current row
                    $loaded = $this->loadRow();
                }
                $this->OldKey = $loaded ? $this->getKey(true) : []; // Get from CurrentValue
            }
        }

        // Process form if post back
        if ($postBack) {
            $this->loadFormValues(); // Get form values
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
                    $this->restoreFormValues();
                    $this->CurrentAction = ""; // Form error, reset action
                }
            }
        }

        // Perform current action
        switch ($this->CurrentAction) {
            case "show": // Get a record to display
                if (!$this->IsModal) { // Normal edit page
                    if (!$loaded) {
                        if (!$this->peekSuccessMessage() && !$this->peekFailureMessage()) {
                            $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
                        }
                        $this->terminate("SubmissionsList"); // Return to list page
                        return;
                    }
                } else { // Modal edit page
                    if (!$loaded) { // Load record based on key
                        if (!$this->peekFailureMessage()) {
                            $this->setFailureMessage($this->language->phrase("NoRecord")); // No record found
                        }
                        $this->terminate("SubmissionsList"); // No matching record, return to list
                        return;
                    }
                } // End modal checking
                break;
            case "update": // Update
                $returnUrl = $this->getReturnUrl();
                if (GetPageName($returnUrl) == "SubmissionsList") {
                    $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                }
                if ($this->editRow()) { // Update record based on key
                    CleanUploadTempPaths(SessionId());
                    if (!$this->peekSuccessMessage()) {
                        $this->setSuccessMessage($this->language->phrase("UpdateSuccess")); // Update success
                    }

                    // Handle UseAjaxActions with return page
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "SubmissionsList") {
                            FlashBag()->add("X-Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "SubmissionsList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate();
                    } else {
                        if ($this->IsModal && GetPageName($returnUrl) != "SubmissionsList") {
                            $this->IsModal = false;
                            FlashBag()->add("X-Refresh-Url", GetUrl("SubmissionsList")); // Refresh page after edit before going to return page
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
                } elseif (($this->peekFailureMessage()[0] ?? "") == $this->language->phrase("NoRecord")) {
                    $this->terminate($returnUrl); // Return to caller
                    return;
                } else {
                    $this->EventCancelled = true; // Event cancelled
                    $this->restoreFormValues(); // Restore form values if update failed
                }
        }

        // Set up Breadcrumb
        $this->setupBreadcrumb();

        // Render row
        $this->renderRow(
            RowType::EDIT
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

    // Load form values
    protected function loadFormValues(): void
    {
        $validate = !Config("SERVER_VALIDATE");

        // submission_id
        if (!$this->submission_id->IsDetailKey) {
            $val = $this->hasInputValue($this->submission_id) ? $this->getInputValue($this->submission_id) : null;
            $this->submission_id->setFormValue($val);
        }

        // assignment_id
        if (!$this->assignment_id->IsDetailKey) {
            $val = $this->hasInputValue($this->assignment_id) ? $this->getInputValue($this->assignment_id) : null;
            $this->assignment_id->setFormValue($val, true, $validate);
        }

        // user_id
        if (!$this->user_id->IsDetailKey) {
            $val = $this->hasInputValue($this->user_id) ? $this->getInputValue($this->user_id) : null;
            $this->user_id->setFormValue($val, true, $validate);
        }

        // submission_path
        if (!$this->submission_path->IsDetailKey) {
            $val = $this->hasInputValue($this->submission_path) ? $this->getInputValue($this->submission_path) : null;
            $this->submission_path->setFormValue($val);
        }

        // submitted_at
        if (!$this->submitted_at->IsDetailKey) {
            $val = $this->hasInputValue($this->submitted_at) ? $this->getInputValue($this->submitted_at) : null;
            $this->submitted_at->setFormValue($val, true, $validate);
            $this->submitted_at->CurrentValue = UnformatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
        }

        // attempt_no
        if (!$this->attempt_no->IsDetailKey) {
            $val = $this->hasInputValue($this->attempt_no) ? $this->getInputValue($this->attempt_no) : null;
            $this->attempt_no->setFormValue($val, true, $validate);
        }

        // submission_status
        if (!$this->submission_status->IsDetailKey) {
            $val = $this->hasInputValue($this->submission_status) ? $this->getInputValue($this->submission_status) : null;
            $this->submission_status->setFormValue($val);
        }

        // remarks
        if (!$this->remarks->IsDetailKey) {
            $val = $this->hasInputValue($this->remarks) ? $this->getInputValue($this->remarks) : null;
            $this->remarks->setFormValue($val);
        }

        // checked_by
        if (!$this->checked_by->IsDetailKey) {
            $val = $this->hasInputValue($this->checked_by) ? $this->getInputValue($this->checked_by) : null;
            $this->checked_by->setFormValue($val, true, $validate);
        }

        // marks_obtained
        if (!$this->marks_obtained->IsDetailKey) {
            $val = $this->hasInputValue($this->marks_obtained) ? $this->getInputValue($this->marks_obtained) : null;
            $this->marks_obtained->setFormValue($val, true, $validate);
        }

        // checked_at
        if (!$this->checked_at->IsDetailKey) {
            $val = $this->hasInputValue($this->checked_at) ? $this->getInputValue($this->checked_at) : null;
            $this->checked_at->setFormValue($val, true, $validate);
            $this->checked_at->CurrentValue = UnformatDateTime($this->checked_at->CurrentValue, $this->checked_at->formatPattern());
        }
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->submission_id->CurrentValue = $this->submission_id->FormValue;
        $this->assignment_id->CurrentValue = $this->assignment_id->FormValue;
        $this->user_id->CurrentValue = $this->user_id->FormValue;
        $this->submission_path->CurrentValue = $this->submission_path->FormValue;
        $this->submitted_at->CurrentValue = $this->submitted_at->FormValue;
        $this->submitted_at->CurrentValue = UnformatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
        $this->attempt_no->CurrentValue = $this->attempt_no->FormValue;
        $this->submission_status->CurrentValue = $this->submission_status->FormValue;
        $this->remarks->CurrentValue = $this->remarks->FormValue;
        $this->checked_by->CurrentValue = $this->checked_by->FormValue;
        $this->marks_obtained->CurrentValue = $this->marks_obtained->FormValue;
        $this->checked_at->CurrentValue = $this->checked_at->FormValue;
        $this->checked_at->CurrentValue = UnformatDateTime($this->checked_at->CurrentValue, $this->checked_at->formatPattern());
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

        // submission_id
        $this->submission_id->RowCssClass = "row";

        // assignment_id
        $this->assignment_id->RowCssClass = "row";

        // user_id
        $this->user_id->RowCssClass = "row";

        // submission_path
        $this->submission_path->RowCssClass = "row";

        // submitted_at
        $this->submitted_at->RowCssClass = "row";

        // attempt_no
        $this->attempt_no->RowCssClass = "row";

        // submission_status
        $this->submission_status->RowCssClass = "row";

        // remarks
        $this->remarks->RowCssClass = "row";

        // checked_by
        $this->checked_by->RowCssClass = "row";

        // marks_obtained
        $this->marks_obtained->RowCssClass = "row";

        // checked_at
        $this->checked_at->RowCssClass = "row";

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

            // assignment_id
            $this->assignment_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // submission_path
            $this->submission_path->HrefValue = "";

            // submitted_at
            $this->submitted_at->HrefValue = "";

            // attempt_no
            $this->attempt_no->HrefValue = "";

            // submission_status
            $this->submission_status->HrefValue = "";

            // remarks
            $this->remarks->HrefValue = "";

            // checked_by
            $this->checked_by->HrefValue = "";

            // marks_obtained
            $this->marks_obtained->HrefValue = "";

            // checked_at
            $this->checked_at->HrefValue = "";
        } elseif ($this->RowType == RowType::EDIT) {
            // submission_id
            $this->submission_id->setupEditAttributes();
            $this->submission_id->EditValue = $this->submission_id->CurrentValue;

            // assignment_id
            $this->assignment_id->setupEditAttributes();
            $this->assignment_id->EditValue = $this->assignment_id->CurrentValue;
            $this->assignment_id->PlaceHolder = RemoveHtml($this->assignment_id->caption());
            if (strval($this->assignment_id->EditValue) != "" && is_numeric($this->assignment_id->EditValue)) {
                $this->assignment_id->EditValue = FormatNumber($this->assignment_id->EditValue, null);
            }

            // user_id
            $this->user_id->setupEditAttributes();
            $this->user_id->EditValue = $this->user_id->CurrentValue;
            $this->user_id->PlaceHolder = RemoveHtml($this->user_id->caption());
            if (strval($this->user_id->EditValue) != "" && is_numeric($this->user_id->EditValue)) {
                $this->user_id->EditValue = FormatNumber($this->user_id->EditValue, null);
            }

            // submission_path
            $this->submission_path->setupEditAttributes();
            $this->submission_path->EditValue = !$this->submission_path->Raw ? HtmlDecode($this->submission_path->CurrentValue) : $this->submission_path->CurrentValue;
            $this->submission_path->PlaceHolder = RemoveHtml($this->submission_path->caption());

            // submitted_at
            $this->submitted_at->setupEditAttributes();
            $this->submitted_at->EditValue = FormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern());
            $this->submitted_at->PlaceHolder = RemoveHtml($this->submitted_at->caption());

            // attempt_no
            $this->attempt_no->setupEditAttributes();
            $this->attempt_no->EditValue = $this->attempt_no->CurrentValue;
            $this->attempt_no->PlaceHolder = RemoveHtml($this->attempt_no->caption());
            if (strval($this->attempt_no->EditValue) != "" && is_numeric($this->attempt_no->EditValue)) {
                $this->attempt_no->EditValue = FormatNumber($this->attempt_no->EditValue, null);
            }

            // submission_status
            $this->submission_status->EditValue = $this->submission_status->options(false);
            $this->submission_status->PlaceHolder = RemoveHtml($this->submission_status->caption());

            // remarks
            $this->remarks->setupEditAttributes();
            $this->remarks->EditValue = !$this->remarks->Raw ? HtmlDecode($this->remarks->CurrentValue) : $this->remarks->CurrentValue;
            $this->remarks->PlaceHolder = RemoveHtml($this->remarks->caption());

            // checked_by
            $this->checked_by->setupEditAttributes();
            $this->checked_by->EditValue = $this->checked_by->CurrentValue;
            $this->checked_by->PlaceHolder = RemoveHtml($this->checked_by->caption());
            if (strval($this->checked_by->EditValue) != "" && is_numeric($this->checked_by->EditValue)) {
                $this->checked_by->EditValue = FormatNumber($this->checked_by->EditValue, null);
            }

            // marks_obtained
            $this->marks_obtained->setupEditAttributes();
            $this->marks_obtained->EditValue = $this->marks_obtained->CurrentValue;
            $this->marks_obtained->PlaceHolder = RemoveHtml($this->marks_obtained->caption());
            if (strval($this->marks_obtained->EditValue) != "" && is_numeric($this->marks_obtained->EditValue)) {
                $this->marks_obtained->EditValue = FormatNumber($this->marks_obtained->EditValue, null);
            }

            // checked_at
            $this->checked_at->setupEditAttributes();
            $this->checked_at->EditValue = FormatDateTime($this->checked_at->CurrentValue, $this->checked_at->formatPattern());
            $this->checked_at->PlaceHolder = RemoveHtml($this->checked_at->caption());

            // Edit refer script

            // submission_id
            $this->submission_id->HrefValue = "";

            // assignment_id
            $this->assignment_id->HrefValue = "";

            // user_id
            $this->user_id->HrefValue = "";

            // submission_path
            $this->submission_path->HrefValue = "";

            // submitted_at
            $this->submitted_at->HrefValue = "";

            // attempt_no
            $this->attempt_no->HrefValue = "";

            // submission_status
            $this->submission_status->HrefValue = "";

            // remarks
            $this->remarks->HrefValue = "";

            // checked_by
            $this->checked_by->HrefValue = "";

            // marks_obtained
            $this->marks_obtained->HrefValue = "";

            // checked_at
            $this->checked_at->HrefValue = "";
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
        if ($this->submission_id->Visible) {
            if ($this->submission_id->Required) {
                if (!$this->submission_id->IsDetailKey && IsEmpty($this->submission_id->FormValue)) {
                    $this->submission_id->addErrorMessage(str_replace("%s", $this->submission_id->caption(), $this->submission_id->RequiredErrorMessage));
                }
            }
        }
        if ($this->assignment_id->Visible) {
            if ($this->assignment_id->Required) {
                if (!$this->assignment_id->IsDetailKey && IsEmpty($this->assignment_id->FormValue)) {
                    $this->assignment_id->addErrorMessage(str_replace("%s", $this->assignment_id->caption(), $this->assignment_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->assignment_id->FormValue)) {
                $this->assignment_id->addErrorMessage($this->assignment_id->getErrorMessage(false));
            }
        }
        if ($this->user_id->Visible) {
            if ($this->user_id->Required) {
                if (!$this->user_id->IsDetailKey && IsEmpty($this->user_id->FormValue)) {
                    $this->user_id->addErrorMessage(str_replace("%s", $this->user_id->caption(), $this->user_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->user_id->FormValue)) {
                $this->user_id->addErrorMessage($this->user_id->getErrorMessage(false));
            }
        }
        if ($this->submission_path->Visible) {
            if ($this->submission_path->Required) {
                if (!$this->submission_path->IsDetailKey && IsEmpty($this->submission_path->FormValue)) {
                    $this->submission_path->addErrorMessage(str_replace("%s", $this->submission_path->caption(), $this->submission_path->RequiredErrorMessage));
                }
            }
        }
        if ($this->submitted_at->Visible) {
            if ($this->submitted_at->Required) {
                if (!$this->submitted_at->IsDetailKey && IsEmpty($this->submitted_at->FormValue)) {
                    $this->submitted_at->addErrorMessage(str_replace("%s", $this->submitted_at->caption(), $this->submitted_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->submitted_at->FormValue, $this->submitted_at->formatPattern())) {
                $this->submitted_at->addErrorMessage($this->submitted_at->getErrorMessage(false));
            }
        }
        if ($this->attempt_no->Visible) {
            if ($this->attempt_no->Required) {
                if (!$this->attempt_no->IsDetailKey && IsEmpty($this->attempt_no->FormValue)) {
                    $this->attempt_no->addErrorMessage(str_replace("%s", $this->attempt_no->caption(), $this->attempt_no->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->attempt_no->FormValue)) {
                $this->attempt_no->addErrorMessage($this->attempt_no->getErrorMessage(false));
            }
        }
        if ($this->submission_status->Visible) {
            if ($this->submission_status->Required) {
                if ($this->submission_status->FormValue == "") {
                    $this->submission_status->addErrorMessage(str_replace("%s", $this->submission_status->caption(), $this->submission_status->RequiredErrorMessage));
                }
            }
        }
        if ($this->remarks->Visible) {
            if ($this->remarks->Required) {
                if (!$this->remarks->IsDetailKey && IsEmpty($this->remarks->FormValue)) {
                    $this->remarks->addErrorMessage(str_replace("%s", $this->remarks->caption(), $this->remarks->RequiredErrorMessage));
                }
            }
        }
        if ($this->checked_by->Visible) {
            if ($this->checked_by->Required) {
                if (!$this->checked_by->IsDetailKey && IsEmpty($this->checked_by->FormValue)) {
                    $this->checked_by->addErrorMessage(str_replace("%s", $this->checked_by->caption(), $this->checked_by->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->checked_by->FormValue)) {
                $this->checked_by->addErrorMessage($this->checked_by->getErrorMessage(false));
            }
        }
        if ($this->marks_obtained->Visible) {
            if ($this->marks_obtained->Required) {
                if (!$this->marks_obtained->IsDetailKey && IsEmpty($this->marks_obtained->FormValue)) {
                    $this->marks_obtained->addErrorMessage(str_replace("%s", $this->marks_obtained->caption(), $this->marks_obtained->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->marks_obtained->FormValue)) {
                $this->marks_obtained->addErrorMessage($this->marks_obtained->getErrorMessage(false));
            }
        }
        if ($this->checked_at->Visible) {
            if ($this->checked_at->Required) {
                if (!$this->checked_at->IsDetailKey && IsEmpty($this->checked_at->FormValue)) {
                    $this->checked_at->addErrorMessage(str_replace("%s", $this->checked_at->caption(), $this->checked_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->checked_at->FormValue, $this->checked_at->formatPattern())) {
                $this->checked_at->addErrorMessage($this->checked_at->getErrorMessage(false));
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

    // Update record based on key values
    protected function editRow(): ?bool
    {
        $row = $this->CurrentRecord; // Use current record for update
        if ($row === null) {
            $this->setFailureMessage($this->language->phrase("NoRecord")); // Set no record message
            return false; // Update Failed
        }

        // Clone entity (as old row)
        $oldRow = clone $row;

        // Update entity
        $newRow = $this->getEditRow($row);

        // Validate constraints
        $errors = Validate($newRow);
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $this->fieldByPropertyName($error->getPropertyPath())?->addErrorMessage($error->getMessage());
            }
            return false;
        }

        // Get Entity Manager
        $em = $this->getEntityManager();

        // Call Row Updating event
        $update = method_exists($this, "rowUpdating") ? $this->rowUpdating($oldRow, $newRow) : true;
        $oldPk = $oldRow->identifierValues();
        $newPk = $newRow->identifierValues();
        if ($update) {
            try {
                $updateTableRow = null;
                if ($oldPk === $newPk) { // PK unchanged
                    if ($this->UpdateTable && $this->UpdateTable != $this->TableName) { // Use Update Table if set
                        $id = $oldRow->identifierValues();
                        $updateTableRow = $em->find($this->UpdateTableEntityClass, $id);
                        if (!$updateTableRow) {
                            throw new \RuntimeException("Cannot update: related entity not found.");
                        }
                        $updateTableRow->fromArray($newRow->toArray());
                        $em->detach($newRow);
                    }
                } else {
                    $this->handlePrimaryKeyChange($oldRow, $newRow);
                }
                $em->flush();
                $updated = true;
            } catch (Exception $e) {
                $this->dispatcher->dispatch(new RowUpdateFailedEvent($updateTableRow ?? $newRow, $e));
                $this->setFailureMessage($e->getMessage());
                $updated = false;
            }
            if ($updated) {
            }
        } else {
            if ($update === false) {
                if ($this->peekSuccessMessage() || $this->peekFailureMessage()) {
                    // Use the message, do nothing
                } elseif ($this->CancelMessage != "") {
                    $this->setFailureMessage($this->CancelMessage);
                    $this->CancelMessage = "";
                } else {
                    $this->setFailureMessage($this->language->phrase("UpdateCancelled"));
                }
            } elseif ($update === null) { // Skip update record
                $em->detach($newRow);
            }
            $updated = $update;
        }

        // Call Row_Updated event
        if ($updated && method_exists($this, "rowUpdated")) {
            $this->rowUpdated($oldRow, $newRow);
        }

        // Write JSON response
        if (IsJsonResponse() && $updated) {
            $row = $this->getRowsFromEntities([$newRow], true);
            $table = $this->TableVar;
            $this->Response = new JsonResponse(["success" => true, "action" => Config("API_EDIT_ACTION"), $table => $row]);
        }
        return $updated;
    }

    /**
     * Handle primary key change
     *
     * @param BaseEntity $oldRow
     * @param BaseEntity $newRow
     *
     * @return void
     */
    protected function handlePrimaryKeyChange(BaseEntity $oldRow, BaseEntity $newRow): void
    {
        $em = $this->getEntityManager();
        $meta = $newRow->metadata();
        $uow = $em->getUnitOfWork();

        // Recompute changes for lifecycle events
        $uow->recomputeSingleEntityChangeSet($meta, $newRow);
        $changeSet = $uow->getEntityChangeSet($newRow);

        // Replace entity and meta data if UpdateTable is different from current table
        if ($this->UpdateTable && $this->UpdateTable != $this->TableName) {
            $data = $newRow->toArray();
            $em->detach($newRow);
            $newRow = $this->UpdateTableEntityClass::createFromArray($data);
            $meta = $newRow->metadata();
        }

        // Trigger preUpdate if there are changes
        if ($changeSet) {
            $em->getEventManager()->dispatchEvent(
                \Doctrine\ORM\Events::preUpdate,
                new \Doctrine\ORM\Event\PreUpdateEventArgs($newRow, $em, $changeSet)
            );
            //$em->flush(); // Don't flush here
        }

        // Detach entity to avoid affecting UnitOfWork
        $em->detach($newRow);

        // Prepare data for DBAL update
        $data = [];
        foreach ($changeSet as $field => [, $newValue]) {
            if ($meta->hasField($field)) {
                $data[$newRow->columnName($field)] = $newValue;
            }
        }

        // Build criteria using old identifier values (safe for any entity)
        $criteria = [];
        foreach ($meta->getIdentifierValues($oldRow) as $field => $value) {
            $criteria[$oldRow->columnName($field)] = $value;
        }

        // Execute DBAL update
        if ($data) {
            $tableName = $meta->getTableName();
            $table = ServiceLocator($tableName) ?? $this;
            $affected = $table->update($data, $criteria);

            // Trigger postUpdate only if something was actually updated
            if ($affected > 0) {
                $em->getEventManager()->dispatchEvent(
                    \Doctrine\ORM\Events::postUpdate,
                    new \Doctrine\ORM\Event\PostUpdateEventArgs($newRow, $em, $changeSet)
                );
                //$em->flush(); // Don't flush here
            }
        }
    }

    /**
     * Get edit row
     *
     * @return BaseEntity
     */
    protected function getEditRow(BaseEntity $newRow): BaseEntity
    {
        // Load DbValue
        $this->loadDbValues($newRow);

        // assignment_id
        if (!$this->assignment_id->ReadOnly) {
            $newRow->setAssignmentId($this->assignment_id->setDbValueDef($this->assignment_id->CurrentValue));
        }

        // user_id
        if (!$this->user_id->ReadOnly) {
            $newRow->setUserId($this->user_id->setDbValueDef($this->user_id->CurrentValue));
        }

        // submission_path
        if (!$this->submission_path->ReadOnly) {
            $newRow->setSubmissionPath($this->submission_path->setDbValueDef($this->submission_path->CurrentValue));
        }

        // submitted_at
        if (!$this->submitted_at->ReadOnly) {
            $newRow->setSubmittedAt($this->submitted_at->setDbValueDef(UnFormatDateTime($this->submitted_at->CurrentValue, $this->submitted_at->formatPattern())));
        }

        // attempt_no
        if (!$this->attempt_no->ReadOnly) {
            $newRow->setAttemptNo($this->attempt_no->setDbValueDef($this->attempt_no->CurrentValue));
        }

        // submission_status
        if (!$this->submission_status->ReadOnly) {
            $newRow->setSubmissionStatus($this->submission_status->setDbValueDef($this->submission_status->CurrentValue));
        }

        // remarks
        if (!$this->remarks->ReadOnly) {
            $newRow->setRemarks($this->remarks->setDbValueDef($this->remarks->CurrentValue));
        }

        // checked_by
        if (!$this->checked_by->ReadOnly) {
            $newRow->setCheckedBy($this->checked_by->setDbValueDef($this->checked_by->CurrentValue));
        }

        // marks_obtained
        if (!$this->marks_obtained->ReadOnly) {
            $newRow->setMarksObtained($this->marks_obtained->setDbValueDef($this->marks_obtained->CurrentValue));
        }

        // checked_at
        if (!$this->checked_at->ReadOnly) {
            $newRow->setCheckedAt($this->checked_at->setDbValueDef(UnFormatDateTime($this->checked_at->CurrentValue, $this->checked_at->formatPattern())));
        }
        $this->Fields->setCurrentValues($newRow);
        return $newRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("SubmissionsList"), "", $this->TableVar, true);
        $pageId = "edit";
        $breadcrumb->add("edit", $pageId, $url);
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

    // Set up starting record parameters
    public function setupStartRecord(): void
    {
        $infiniteScroll = false;

        // Set up StartRecord
        $pagerTable = Get(Config("TABLE_PAGER_TABLE_NAME"));
        if ($pagerTable && $pagerTable != $this->TableVar) { // Skip if not paging for this table
            $this->StartRecord = $this->getStartRecordNumber();
        } else { // Set up from query string parameter
            $pageNumber = GetInt(Config("TABLE_PAGE_NUMBER"));
            $startRec = GetInt(Config("TABLE_START_REC"));
            $this->PageNumber = $pageNumber ?? $startRec ?? 0; // Record number = page number or start record
            if ($this->PageNumber > 0) {
                $this->StartRecord = $this->PageNumber;
            } else {
                $this->StartRecord = $this->getStartRecordNumber();
            }
        }

        // Check if correct start record counter
        if (!is_numeric($this->StartRecord) || intval($this->StartRecord) <= 0) { // Avoid invalid start record counter
            $this->StartRecord = 1; // Reset start record counter
        } elseif (($this->StartRecord - 1) % $this->DisplayRecords != 0) {
            $this->StartRecord = (int)(($this->StartRecord - 1) / $this->DisplayRecords) * $this->DisplayRecords + 1; // Point to page boundary
        }
        if (!$infiniteScroll) {
            $this->setStartRecordNumber($this->StartRecord);
        }
    }

    // Get page count
    public function pageCount(): int
    {
        return ceil($this->TotalRecords / $this->DisplayRecords);
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
