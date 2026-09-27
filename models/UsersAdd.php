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
#[AsAlias("UsersAdd", true)]
class UsersAdd extends Users implements PageInterface
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
    public string $CurrentPageName = "UsersAdd"; // Route action

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
        $this->TableVar = 'users';
        $this->TableName = 'users';

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
        $this->userid->Visible = false;
        $this->first_name->setVisibility();
        $this->last_name->setVisibility();
        $this->email->setVisibility();
        $this->password_hash->setVisibility();
        $this->role->setVisibility();
        $this->department_id->setVisibility();
        $this->status->setVisibility();
        $this->phone->setVisibility();
        $this->last_login_at->setVisibility();
        $this->created_at->setVisibility();
        $this->updated_at->setVisibility();
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
                        $result["view"] = SameString($pageName, "UsersView"); // If View page, no primary button
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
            $this->userid->Visible = false;
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
        $this->setupLookupOptions($this->role);
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
            if (($keyValue = Route("userid") ?? Get("userid")) !== null) {
                $this->userid->setQueryStringValue($keyValue);
            } elseif (IsApi() && ($keyValue = Key(0)) !== null) {
                $this->userid->setQueryStringValue($keyValue);
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
                    $this->terminate("UsersList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "UsersList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "UsersView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "UsersList") {
                            FlashBag()->add("X-Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "UsersList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate();
                    } else {
                        if ($this->IsModal && GetPageName($returnUrl) != "UsersList") {
                            $this->IsModal = false;
                            FlashBag()->add("X-Refresh-Url", GetUrl("UsersList")); // Refresh page after add before going to return page
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

        // first_name
        if (!$this->first_name->IsDetailKey) {
            $val = $this->hasInputValue($this->first_name) ? $this->getInputValue($this->first_name) : null;
            $this->first_name->setFormValue($val);
        }

        // last_name
        if (!$this->last_name->IsDetailKey) {
            $val = $this->hasInputValue($this->last_name) ? $this->getInputValue($this->last_name) : null;
            $this->last_name->setFormValue($val);
        }

        // email
        if (!$this->email->IsDetailKey) {
            $val = $this->hasInputValue($this->email) ? $this->getInputValue($this->email) : null;
            $this->email->setFormValue($val);
        }

        // password_hash
        if (!$this->password_hash->IsDetailKey) {
            $val = $this->hasInputValue($this->password_hash) ? $this->getInputValue($this->password_hash) : null;
            $this->password_hash->setFormValue($val);
        }

        // role
        if (!$this->role->IsDetailKey) {
            $val = $this->hasInputValue($this->role) ? $this->getInputValue($this->role) : null;
            $this->role->setFormValue($val);
        }

        // department_id
        if (!$this->department_id->IsDetailKey) {
            $val = $this->hasInputValue($this->department_id) ? $this->getInputValue($this->department_id) : null;
            $this->department_id->setFormValue($val, true, $validate);
        }

        // status
        if (!$this->status->IsDetailKey) {
            $val = $this->hasInputValue($this->status) ? $this->getInputValue($this->status) : null;
            $this->status->setFormValue($val);
        }

        // phone
        if (!$this->phone->IsDetailKey) {
            $val = $this->hasInputValue($this->phone) ? $this->getInputValue($this->phone) : null;
            $this->phone->setFormValue($val);
        }

        // last_login_at
        if (!$this->last_login_at->IsDetailKey) {
            $val = $this->hasInputValue($this->last_login_at) ? $this->getInputValue($this->last_login_at) : null;
            $this->last_login_at->setFormValue($val, true, $validate);
            $this->last_login_at->CurrentValue = UnformatDateTime($this->last_login_at->CurrentValue, $this->last_login_at->formatPattern());
        }

        // created_at
        if (!$this->created_at->IsDetailKey) {
            $val = $this->hasInputValue($this->created_at) ? $this->getInputValue($this->created_at) : null;
            $this->created_at->setFormValue($val, true, $validate);
            $this->created_at->CurrentValue = UnformatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        }

        // updated_at
        if (!$this->updated_at->IsDetailKey) {
            $val = $this->hasInputValue($this->updated_at) ? $this->getInputValue($this->updated_at) : null;
            $this->updated_at->setFormValue($val, true, $validate);
            $this->updated_at->CurrentValue = UnformatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
        }

        // userid
        $val = $this->getInputValue($this->userid);
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->first_name->CurrentValue = $this->first_name->FormValue;
        $this->last_name->CurrentValue = $this->last_name->FormValue;
        $this->email->CurrentValue = $this->email->FormValue;
        $this->password_hash->CurrentValue = $this->password_hash->FormValue;
        $this->role->CurrentValue = $this->role->FormValue;
        $this->department_id->CurrentValue = $this->department_id->FormValue;
        $this->status->CurrentValue = $this->status->FormValue;
        $this->phone->CurrentValue = $this->phone->FormValue;
        $this->last_login_at->CurrentValue = $this->last_login_at->FormValue;
        $this->last_login_at->CurrentValue = UnformatDateTime($this->last_login_at->CurrentValue, $this->last_login_at->formatPattern());
        $this->created_at->CurrentValue = $this->created_at->FormValue;
        $this->created_at->CurrentValue = UnformatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        $this->updated_at->CurrentValue = $this->updated_at->FormValue;
        $this->updated_at->CurrentValue = UnformatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
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
        $this->userid->setDbValue($row['userid']);
        $this->first_name->setDbValue($row['first_name']);
        $this->last_name->setDbValue($row['last_name']);
        $this->email->setDbValue($row['email']);
        $this->password_hash->setDbValue($row['password_hash']);
        $this->role->setDbValue($row['role']);
        $this->department_id->setDbValue($row['department_id']);
        $this->status->setDbValue($row['status']);
        $this->phone->setDbValue($row['phone']);
        $this->last_login_at->setDbValue($row['last_login_at']);
        $this->created_at->setDbValue($row['created_at']);
        $this->updated_at->setDbValue($row['updated_at']);
    }

    /**
     * Return a row with default values
     *
     * @return BaseEntity
     */
    protected function newRow(): BaseEntity
    {
        $row = new $this->EntityClass();
        if (!IsEmpty($this->userid->DefaultValue)) {
            $row['userid'] = intval($this->userid->DefaultValue);
        }
        if (!IsEmpty($this->first_name->DefaultValue)) {
            $row['first_name'] = strval($this->first_name->DefaultValue);
        }
        if (!IsEmpty($this->last_name->DefaultValue)) {
            $row['last_name'] = strval($this->last_name->DefaultValue);
        }
        if (!IsEmpty($this->email->DefaultValue)) {
            $row['email'] = strval($this->email->DefaultValue);
        }
        if (!IsEmpty($this->password_hash->DefaultValue)) {
            $row['password_hash'] = strval($this->password_hash->DefaultValue);
        }
        if (!IsEmpty($this->role->DefaultValue)) {
            $row['role'] = strval($this->role->DefaultValue);
        }
        if (!IsEmpty($this->department_id->DefaultValue)) {
            $row['department_id'] = intval($this->department_id->DefaultValue);
        }
        if (!IsEmpty($this->status->DefaultValue)) {
            $row['status'] = strval($this->status->DefaultValue);
        }
        if (!IsEmpty($this->phone->DefaultValue)) {
            $row['phone'] = strval($this->phone->DefaultValue);
        }
        if (!IsEmpty($this->last_login_at->DefaultValue)) {
            $row['last_login_at'] = $this->last_login_at->DefaultValue instanceof DateTimeInterface ? $this->last_login_at->DefaultValue : new DateTimeImmutable($this->last_login_at->DefaultValue);
        }
        if (!IsEmpty($this->created_at->DefaultValue)) {
            $row['created_at'] = $this->created_at->DefaultValue instanceof DateTimeInterface ? $this->created_at->DefaultValue : new DateTimeImmutable($this->created_at->DefaultValue);
        }
        if (!IsEmpty($this->updated_at->DefaultValue)) {
            $row['updated_at'] = $this->updated_at->DefaultValue instanceof DateTimeInterface ? $this->updated_at->DefaultValue : new DateTimeImmutable($this->updated_at->DefaultValue);
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

        // userid
        $this->userid->RowCssClass = "row";

        // first_name
        $this->first_name->RowCssClass = "row";

        // last_name
        $this->last_name->RowCssClass = "row";

        // email
        $this->email->RowCssClass = "row";

        // password_hash
        $this->password_hash->RowCssClass = "row";

        // role
        $this->role->RowCssClass = "row";

        // department_id
        $this->department_id->RowCssClass = "row";

        // status
        $this->status->RowCssClass = "row";

        // phone
        $this->phone->RowCssClass = "row";

        // last_login_at
        $this->last_login_at->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // updated_at
        $this->updated_at->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // userid
            $this->userid->ViewValue = $this->userid->CurrentValue;

            // first_name
            $this->first_name->ViewValue = $this->first_name->CurrentValue;

            // last_name
            $this->last_name->ViewValue = $this->last_name->CurrentValue;

            // email
            $this->email->ViewValue = $this->email->CurrentValue;

            // password_hash
            $this->password_hash->ViewValue = $this->password_hash->CurrentValue;

            // role
            if (ConvertToString($this->role->CurrentValue) != "") {
                $this->role->ViewValue = $this->role->optionCaption(ConvertToString($this->role->CurrentValue));
            } else {
                $this->role->ViewValue = null;
            }

            // department_id
            $this->department_id->ViewValue = $this->department_id->CurrentValue;
            $this->department_id->ViewValue = FormatNumber($this->department_id->ViewValue, $this->department_id->formatPattern());

            // status
            if (ConvertToString($this->status->CurrentValue) != "") {
                $this->status->ViewValue = $this->status->optionCaption(ConvertToString($this->status->CurrentValue));
            } else {
                $this->status->ViewValue = null;
            }

            // phone
            $this->phone->ViewValue = $this->phone->CurrentValue;

            // last_login_at
            $this->last_login_at->ViewValue = $this->last_login_at->CurrentValue;
            $this->last_login_at->ViewValue = FormatDateTime($this->last_login_at->ViewValue, $this->last_login_at->formatPattern());

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // updated_at
            $this->updated_at->ViewValue = $this->updated_at->CurrentValue;
            $this->updated_at->ViewValue = FormatDateTime($this->updated_at->ViewValue, $this->updated_at->formatPattern());

            // first_name
            $this->first_name->HrefValue = "";

            // last_name
            $this->last_name->HrefValue = "";

            // email
            $this->email->HrefValue = "";

            // password_hash
            $this->password_hash->HrefValue = "";

            // role
            $this->role->HrefValue = "";

            // department_id
            $this->department_id->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // phone
            $this->phone->HrefValue = "";

            // last_login_at
            $this->last_login_at->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // first_name
            $this->first_name->setupEditAttributes();
            $this->first_name->EditValue = !$this->first_name->Raw ? HtmlDecode($this->first_name->CurrentValue) : $this->first_name->CurrentValue;
            $this->first_name->PlaceHolder = RemoveHtml($this->first_name->caption());

            // last_name
            $this->last_name->setupEditAttributes();
            $this->last_name->EditValue = !$this->last_name->Raw ? HtmlDecode($this->last_name->CurrentValue) : $this->last_name->CurrentValue;
            $this->last_name->PlaceHolder = RemoveHtml($this->last_name->caption());

            // email
            $this->email->setupEditAttributes();
            $this->email->EditValue = !$this->email->Raw ? HtmlDecode($this->email->CurrentValue) : $this->email->CurrentValue;
            $this->email->PlaceHolder = RemoveHtml($this->email->caption());

            // password_hash
            $this->password_hash->setupEditAttributes();
            $this->password_hash->EditValue = !$this->password_hash->Raw ? HtmlDecode($this->password_hash->CurrentValue) : $this->password_hash->CurrentValue;
            $this->password_hash->PlaceHolder = RemoveHtml($this->password_hash->caption());

            // role
            $this->role->EditValue = $this->role->options(false);
            $this->role->PlaceHolder = RemoveHtml($this->role->caption());

            // department_id
            $this->department_id->setupEditAttributes();
            $this->department_id->EditValue = $this->department_id->CurrentValue;
            $this->department_id->PlaceHolder = RemoveHtml($this->department_id->caption());
            if (strval($this->department_id->EditValue) != "" && is_numeric($this->department_id->EditValue)) {
                $this->department_id->EditValue = FormatNumber($this->department_id->EditValue, null);
            }

            // status
            $this->status->EditValue = $this->status->options(false);
            $this->status->PlaceHolder = RemoveHtml($this->status->caption());

            // phone
            $this->phone->setupEditAttributes();
            $this->phone->EditValue = !$this->phone->Raw ? HtmlDecode($this->phone->CurrentValue) : $this->phone->CurrentValue;
            $this->phone->PlaceHolder = RemoveHtml($this->phone->caption());

            // last_login_at
            $this->last_login_at->setupEditAttributes();
            $this->last_login_at->EditValue = FormatDateTime($this->last_login_at->CurrentValue, $this->last_login_at->formatPattern());
            $this->last_login_at->PlaceHolder = RemoveHtml($this->last_login_at->caption());

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // updated_at
            $this->updated_at->setupEditAttributes();
            $this->updated_at->EditValue = FormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern());
            $this->updated_at->PlaceHolder = RemoveHtml($this->updated_at->caption());

            // Add refer script

            // first_name
            $this->first_name->HrefValue = "";

            // last_name
            $this->last_name->HrefValue = "";

            // email
            $this->email->HrefValue = "";

            // password_hash
            $this->password_hash->HrefValue = "";

            // role
            $this->role->HrefValue = "";

            // department_id
            $this->department_id->HrefValue = "";

            // status
            $this->status->HrefValue = "";

            // phone
            $this->phone->HrefValue = "";

            // last_login_at
            $this->last_login_at->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";

            // updated_at
            $this->updated_at->HrefValue = "";
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
        if ($this->first_name->Visible) {
            if ($this->first_name->Required) {
                if (!$this->first_name->IsDetailKey && IsEmpty($this->first_name->FormValue)) {
                    $this->first_name->addErrorMessage(str_replace("%s", $this->first_name->caption(), $this->first_name->RequiredErrorMessage));
                }
            }
        }
        if ($this->last_name->Visible) {
            if ($this->last_name->Required) {
                if (!$this->last_name->IsDetailKey && IsEmpty($this->last_name->FormValue)) {
                    $this->last_name->addErrorMessage(str_replace("%s", $this->last_name->caption(), $this->last_name->RequiredErrorMessage));
                }
            }
        }
        if ($this->email->Visible) {
            if ($this->email->Required) {
                if (!$this->email->IsDetailKey && IsEmpty($this->email->FormValue)) {
                    $this->email->addErrorMessage(str_replace("%s", $this->email->caption(), $this->email->RequiredErrorMessage));
                }
            }
        }
        if ($this->password_hash->Visible) {
            if ($this->password_hash->Required) {
                if (!$this->password_hash->IsDetailKey && IsEmpty($this->password_hash->FormValue)) {
                    $this->password_hash->addErrorMessage(str_replace("%s", $this->password_hash->caption(), $this->password_hash->RequiredErrorMessage));
                }
            }
        }
        if ($this->role->Visible) {
            if ($this->role->Required) {
                if ($this->role->FormValue == "") {
                    $this->role->addErrorMessage(str_replace("%s", $this->role->caption(), $this->role->RequiredErrorMessage));
                }
            }
        }
        if ($this->department_id->Visible) {
            if ($this->department_id->Required) {
                if (!$this->department_id->IsDetailKey && IsEmpty($this->department_id->FormValue)) {
                    $this->department_id->addErrorMessage(str_replace("%s", $this->department_id->caption(), $this->department_id->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->department_id->FormValue)) {
                $this->department_id->addErrorMessage($this->department_id->getErrorMessage(false));
            }
        }
        if ($this->status->Visible) {
            if ($this->status->Required) {
                if ($this->status->FormValue == "") {
                    $this->status->addErrorMessage(str_replace("%s", $this->status->caption(), $this->status->RequiredErrorMessage));
                }
            }
        }
        if ($this->phone->Visible) {
            if ($this->phone->Required) {
                if (!$this->phone->IsDetailKey && IsEmpty($this->phone->FormValue)) {
                    $this->phone->addErrorMessage(str_replace("%s", $this->phone->caption(), $this->phone->RequiredErrorMessage));
                }
            }
        }
        if ($this->last_login_at->Visible) {
            if ($this->last_login_at->Required) {
                if (!$this->last_login_at->IsDetailKey && IsEmpty($this->last_login_at->FormValue)) {
                    $this->last_login_at->addErrorMessage(str_replace("%s", $this->last_login_at->caption(), $this->last_login_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->last_login_at->FormValue, $this->last_login_at->formatPattern())) {
                $this->last_login_at->addErrorMessage($this->last_login_at->getErrorMessage(false));
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
        if ($this->updated_at->Visible) {
            if ($this->updated_at->Required) {
                if (!$this->updated_at->IsDetailKey && IsEmpty($this->updated_at->FormValue)) {
                    $this->updated_at->addErrorMessage(str_replace("%s", $this->updated_at->caption(), $this->updated_at->RequiredErrorMessage));
                }
            }
            if (!CheckDate($this->updated_at->FormValue, $this->updated_at->formatPattern())) {
                $this->updated_at->addErrorMessage($this->updated_at->getErrorMessage(false));
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
                $this->userid->CurrentValue = $newRow->getUserid();
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

        // first_name
        $newRow->setFirstName($this->first_name->setDbValueDef($this->first_name->CurrentValue));

        // last_name
        $newRow->setLastName($this->last_name->setDbValueDef($this->last_name->CurrentValue));

        // email
        $newRow->setEmail($this->email->setDbValueDef($this->email->CurrentValue));

        // password_hash
        $newRow->setPasswordHash($this->password_hash->setDbValueDef($this->password_hash->CurrentValue));

        // role
        $newRow->setRole($this->role->setDbValueDef($this->role->CurrentValue));

        // department_id
        $newRow->setDepartmentId($this->department_id->setDbValueDef($this->department_id->CurrentValue));

        // status
        if (!IsEmpty(strval($this->status->CurrentValue))) {
            $newRow->setStatus($this->status->setDbValueDef($this->status->CurrentValue));
        }

        // phone
        $newRow->setPhone($this->phone->setDbValueDef($this->phone->CurrentValue));

        // last_login_at
        $newRow->setLastLoginAt($this->last_login_at->setDbValueDef(UnFormatDateTime($this->last_login_at->CurrentValue, $this->last_login_at->formatPattern())));

        // created_at
        $newRow->setCreatedAt($this->created_at->setDbValueDef(UnFormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern())));

        // updated_at
        $newRow->setUpdatedAt($this->updated_at->setDbValueDef(UnFormatDateTime($this->updated_at->CurrentValue, $this->updated_at->formatPattern())));
        return $newRow;
    }

    // Set up Breadcrumb
    protected function setupBreadcrumb(): void
    {
        $breadcrumb = Breadcrumb();
        $url = CurrentUrl();
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("UsersList"), "", $this->TableVar, true);
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
                case "x_role":
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
