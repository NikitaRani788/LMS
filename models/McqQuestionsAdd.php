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
#[AsAlias("McqQuestionsAdd", true)]
class McqQuestionsAdd extends McqQuestions implements PageInterface
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
    public string $CurrentPageName = "McqQuestionsAdd"; // Route action

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
        $this->TableVar = 'mcq_questions';
        $this->TableName = 'mcq_questions';

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
        $this->question_id->Visible = false;
        $this->assignment_id->setVisibility();
        $this->question_text->setVisibility();
        $this->option_a->setVisibility();
        $this->option_b->setVisibility();
        $this->option_c->setVisibility();
        $this->option_d->setVisibility();
        $this->correct_option->setVisibility();
        $this->marks->setVisibility();
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
                        $result["view"] = SameString($pageName, "McqQuestionsView"); // If View page, no primary button
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
            $this->question_id->Visible = false;
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
            if (($keyValue = Route("questionId") ?? Get("question_id")) !== null) {
                $this->question_id->setQueryStringValue($keyValue);
            } elseif (IsApi() && ($keyValue = Key(0)) !== null) {
                $this->question_id->setQueryStringValue($keyValue);
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
                    $this->terminate("McqQuestionsList"); // No matching record, return to list
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
                    if (GetPageName($returnUrl) == "McqQuestionsList") {
                        $returnUrl = $this->addMasterUrl($returnUrl); // List page, return to List page with correct master key if necessary
                    } elseif (GetPageName($returnUrl) == "McqQuestionsView") {
                        $returnUrl = $this->getViewUrl(); // View page, return to View page with keyurl directly
                    }

                    // Handle UseAjaxActions
                    if ($this->IsModal && $this->UseAjaxActions) {
                        $this->IsModal = false;
                        if (GetPageName($returnUrl) != "McqQuestionsList") {
                            FlashBag()->add("X-Return-Url", $returnUrl); // Save return URL
                            $returnUrl = "McqQuestionsList"; // Return list page content
                        }
                    }
                    if (IsJsonResponse()) {
                        $this->terminate();
                    } else {
                        if ($this->IsModal && GetPageName($returnUrl) != "McqQuestionsList") {
                            $this->IsModal = false;
                            FlashBag()->add("X-Refresh-Url", GetUrl("McqQuestionsList")); // Refresh page after add before going to return page
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

        // assignment_id
        if (!$this->assignment_id->IsDetailKey) {
            $val = $this->hasInputValue($this->assignment_id) ? $this->getInputValue($this->assignment_id) : null;
            $this->assignment_id->setFormValue($val, true, $validate);
        }

        // question_text
        if (!$this->question_text->IsDetailKey) {
            $val = $this->hasInputValue($this->question_text) ? $this->getInputValue($this->question_text) : null;
            $this->question_text->setFormValue($val);
        }

        // option_a
        if (!$this->option_a->IsDetailKey) {
            $val = $this->hasInputValue($this->option_a) ? $this->getInputValue($this->option_a) : null;
            $this->option_a->setFormValue($val);
        }

        // option_b
        if (!$this->option_b->IsDetailKey) {
            $val = $this->hasInputValue($this->option_b) ? $this->getInputValue($this->option_b) : null;
            $this->option_b->setFormValue($val);
        }

        // option_c
        if (!$this->option_c->IsDetailKey) {
            $val = $this->hasInputValue($this->option_c) ? $this->getInputValue($this->option_c) : null;
            $this->option_c->setFormValue($val);
        }

        // option_d
        if (!$this->option_d->IsDetailKey) {
            $val = $this->hasInputValue($this->option_d) ? $this->getInputValue($this->option_d) : null;
            $this->option_d->setFormValue($val);
        }

        // correct_option
        if (!$this->correct_option->IsDetailKey) {
            $val = $this->hasInputValue($this->correct_option) ? $this->getInputValue($this->correct_option) : null;
            $this->correct_option->setFormValue($val);
        }

        // marks
        if (!$this->marks->IsDetailKey) {
            $val = $this->hasInputValue($this->marks) ? $this->getInputValue($this->marks) : null;
            $this->marks->setFormValue($val, true, $validate);
        }

        // created_at
        if (!$this->created_at->IsDetailKey) {
            $val = $this->hasInputValue($this->created_at) ? $this->getInputValue($this->created_at) : null;
            $this->created_at->setFormValue($val, true, $validate);
            $this->created_at->CurrentValue = UnformatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
        }

        // question_id
        $val = $this->getInputValue($this->question_id);
    }

    // Restore form values
    public function restoreFormValues(): void
    {
        $this->assignment_id->CurrentValue = $this->assignment_id->FormValue;
        $this->question_text->CurrentValue = $this->question_text->FormValue;
        $this->option_a->CurrentValue = $this->option_a->FormValue;
        $this->option_b->CurrentValue = $this->option_b->FormValue;
        $this->option_c->CurrentValue = $this->option_c->FormValue;
        $this->option_d->CurrentValue = $this->option_d->FormValue;
        $this->correct_option->CurrentValue = $this->correct_option->FormValue;
        $this->marks->CurrentValue = $this->marks->FormValue;
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
        $this->question_id->setDbValue($row['question_id']);
        $this->assignment_id->setDbValue($row['assignment_id']);
        $this->question_text->setDbValue($row['question_text']);
        $this->option_a->setDbValue($row['option_a']);
        $this->option_b->setDbValue($row['option_b']);
        $this->option_c->setDbValue($row['option_c']);
        $this->option_d->setDbValue($row['option_d']);
        $this->correct_option->setDbValue($row['correct_option']);
        $this->marks->setDbValue($row['marks']);
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
        if (!IsEmpty($this->question_id->DefaultValue)) {
            $row['question_id'] = intval($this->question_id->DefaultValue);
        }
        if (!IsEmpty($this->assignment_id->DefaultValue)) {
            $row['assignment_id'] = intval($this->assignment_id->DefaultValue);
        }
        if (!IsEmpty($this->question_text->DefaultValue)) {
            $row['question_text'] = strval($this->question_text->DefaultValue);
        }
        if (!IsEmpty($this->option_a->DefaultValue)) {
            $row['option_a'] = strval($this->option_a->DefaultValue);
        }
        if (!IsEmpty($this->option_b->DefaultValue)) {
            $row['option_b'] = strval($this->option_b->DefaultValue);
        }
        if (!IsEmpty($this->option_c->DefaultValue)) {
            $row['option_c'] = strval($this->option_c->DefaultValue);
        }
        if (!IsEmpty($this->option_d->DefaultValue)) {
            $row['option_d'] = strval($this->option_d->DefaultValue);
        }
        if (!IsEmpty($this->correct_option->DefaultValue)) {
            $row['correct_option'] = strval($this->correct_option->DefaultValue);
        }
        if (!IsEmpty($this->marks->DefaultValue)) {
            $row['marks'] = intval($this->marks->DefaultValue);
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

        // question_id
        $this->question_id->RowCssClass = "row";

        // assignment_id
        $this->assignment_id->RowCssClass = "row";

        // question_text
        $this->question_text->RowCssClass = "row";

        // option_a
        $this->option_a->RowCssClass = "row";

        // option_b
        $this->option_b->RowCssClass = "row";

        // option_c
        $this->option_c->RowCssClass = "row";

        // option_d
        $this->option_d->RowCssClass = "row";

        // correct_option
        $this->correct_option->RowCssClass = "row";

        // marks
        $this->marks->RowCssClass = "row";

        // created_at
        $this->created_at->RowCssClass = "row";

        // View row
        if ($this->RowType == RowType::VIEW) {
            // question_id
            $this->question_id->ViewValue = $this->question_id->CurrentValue;

            // assignment_id
            $this->assignment_id->ViewValue = $this->assignment_id->CurrentValue;
            $this->assignment_id->ViewValue = FormatNumber($this->assignment_id->ViewValue, $this->assignment_id->formatPattern());

            // question_text
            $this->question_text->ViewValue = $this->question_text->CurrentValue;

            // option_a
            $this->option_a->ViewValue = $this->option_a->CurrentValue;

            // option_b
            $this->option_b->ViewValue = $this->option_b->CurrentValue;

            // option_c
            $this->option_c->ViewValue = $this->option_c->CurrentValue;

            // option_d
            $this->option_d->ViewValue = $this->option_d->CurrentValue;

            // correct_option
            $this->correct_option->ViewValue = $this->correct_option->CurrentValue;

            // marks
            $this->marks->ViewValue = $this->marks->CurrentValue;
            $this->marks->ViewValue = FormatNumber($this->marks->ViewValue, $this->marks->formatPattern());

            // created_at
            $this->created_at->ViewValue = $this->created_at->CurrentValue;
            $this->created_at->ViewValue = FormatDateTime($this->created_at->ViewValue, $this->created_at->formatPattern());

            // assignment_id
            $this->assignment_id->HrefValue = "";

            // question_text
            $this->question_text->HrefValue = "";

            // option_a
            $this->option_a->HrefValue = "";

            // option_b
            $this->option_b->HrefValue = "";

            // option_c
            $this->option_c->HrefValue = "";

            // option_d
            $this->option_d->HrefValue = "";

            // correct_option
            $this->correct_option->HrefValue = "";

            // marks
            $this->marks->HrefValue = "";

            // created_at
            $this->created_at->HrefValue = "";
        } elseif ($this->RowType == RowType::ADD) {
            // assignment_id
            $this->assignment_id->setupEditAttributes();
            $this->assignment_id->EditValue = $this->assignment_id->CurrentValue;
            $this->assignment_id->PlaceHolder = RemoveHtml($this->assignment_id->caption());
            if (strval($this->assignment_id->EditValue) != "" && is_numeric($this->assignment_id->EditValue)) {
                $this->assignment_id->EditValue = FormatNumber($this->assignment_id->EditValue, null);
            }

            // question_text
            $this->question_text->setupEditAttributes();
            $this->question_text->EditValue = !$this->question_text->Raw ? HtmlDecode($this->question_text->CurrentValue) : $this->question_text->CurrentValue;
            $this->question_text->PlaceHolder = RemoveHtml($this->question_text->caption());

            // option_a
            $this->option_a->setupEditAttributes();
            $this->option_a->EditValue = !$this->option_a->Raw ? HtmlDecode($this->option_a->CurrentValue) : $this->option_a->CurrentValue;
            $this->option_a->PlaceHolder = RemoveHtml($this->option_a->caption());

            // option_b
            $this->option_b->setupEditAttributes();
            $this->option_b->EditValue = !$this->option_b->Raw ? HtmlDecode($this->option_b->CurrentValue) : $this->option_b->CurrentValue;
            $this->option_b->PlaceHolder = RemoveHtml($this->option_b->caption());

            // option_c
            $this->option_c->setupEditAttributes();
            $this->option_c->EditValue = !$this->option_c->Raw ? HtmlDecode($this->option_c->CurrentValue) : $this->option_c->CurrentValue;
            $this->option_c->PlaceHolder = RemoveHtml($this->option_c->caption());

            // option_d
            $this->option_d->setupEditAttributes();
            $this->option_d->EditValue = !$this->option_d->Raw ? HtmlDecode($this->option_d->CurrentValue) : $this->option_d->CurrentValue;
            $this->option_d->PlaceHolder = RemoveHtml($this->option_d->caption());

            // correct_option
            $this->correct_option->setupEditAttributes();
            $this->correct_option->EditValue = !$this->correct_option->Raw ? HtmlDecode($this->correct_option->CurrentValue) : $this->correct_option->CurrentValue;
            $this->correct_option->PlaceHolder = RemoveHtml($this->correct_option->caption());

            // marks
            $this->marks->setupEditAttributes();
            $this->marks->EditValue = $this->marks->CurrentValue;
            $this->marks->PlaceHolder = RemoveHtml($this->marks->caption());
            if (strval($this->marks->EditValue) != "" && is_numeric($this->marks->EditValue)) {
                $this->marks->EditValue = FormatNumber($this->marks->EditValue, null);
            }

            // created_at
            $this->created_at->setupEditAttributes();
            $this->created_at->EditValue = FormatDateTime($this->created_at->CurrentValue, $this->created_at->formatPattern());
            $this->created_at->PlaceHolder = RemoveHtml($this->created_at->caption());

            // Add refer script

            // assignment_id
            $this->assignment_id->HrefValue = "";

            // question_text
            $this->question_text->HrefValue = "";

            // option_a
            $this->option_a->HrefValue = "";

            // option_b
            $this->option_b->HrefValue = "";

            // option_c
            $this->option_c->HrefValue = "";

            // option_d
            $this->option_d->HrefValue = "";

            // correct_option
            $this->correct_option->HrefValue = "";

            // marks
            $this->marks->HrefValue = "";

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
        if ($this->question_text->Visible) {
            if ($this->question_text->Required) {
                if (!$this->question_text->IsDetailKey && IsEmpty($this->question_text->FormValue)) {
                    $this->question_text->addErrorMessage(str_replace("%s", $this->question_text->caption(), $this->question_text->RequiredErrorMessage));
                }
            }
        }
        if ($this->option_a->Visible) {
            if ($this->option_a->Required) {
                if (!$this->option_a->IsDetailKey && IsEmpty($this->option_a->FormValue)) {
                    $this->option_a->addErrorMessage(str_replace("%s", $this->option_a->caption(), $this->option_a->RequiredErrorMessage));
                }
            }
        }
        if ($this->option_b->Visible) {
            if ($this->option_b->Required) {
                if (!$this->option_b->IsDetailKey && IsEmpty($this->option_b->FormValue)) {
                    $this->option_b->addErrorMessage(str_replace("%s", $this->option_b->caption(), $this->option_b->RequiredErrorMessage));
                }
            }
        }
        if ($this->option_c->Visible) {
            if ($this->option_c->Required) {
                if (!$this->option_c->IsDetailKey && IsEmpty($this->option_c->FormValue)) {
                    $this->option_c->addErrorMessage(str_replace("%s", $this->option_c->caption(), $this->option_c->RequiredErrorMessage));
                }
            }
        }
        if ($this->option_d->Visible) {
            if ($this->option_d->Required) {
                if (!$this->option_d->IsDetailKey && IsEmpty($this->option_d->FormValue)) {
                    $this->option_d->addErrorMessage(str_replace("%s", $this->option_d->caption(), $this->option_d->RequiredErrorMessage));
                }
            }
        }
        if ($this->correct_option->Visible) {
            if ($this->correct_option->Required) {
                if (!$this->correct_option->IsDetailKey && IsEmpty($this->correct_option->FormValue)) {
                    $this->correct_option->addErrorMessage(str_replace("%s", $this->correct_option->caption(), $this->correct_option->RequiredErrorMessage));
                }
            }
        }
        if ($this->marks->Visible) {
            if ($this->marks->Required) {
                if (!$this->marks->IsDetailKey && IsEmpty($this->marks->FormValue)) {
                    $this->marks->addErrorMessage(str_replace("%s", $this->marks->caption(), $this->marks->RequiredErrorMessage));
                }
            }
            if (!CheckInteger($this->marks->FormValue)) {
                $this->marks->addErrorMessage($this->marks->getErrorMessage(false));
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
                $this->question_id->CurrentValue = $newRow->getQuestionId();
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

        // assignment_id
        $newRow->setAssignmentId($this->assignment_id->setDbValueDef($this->assignment_id->CurrentValue));

        // question_text
        $newRow->setQuestionText($this->question_text->setDbValueDef($this->question_text->CurrentValue));

        // option_a
        $newRow->setOptionA($this->option_a->setDbValueDef($this->option_a->CurrentValue));

        // option_b
        $newRow->setOptionB($this->option_b->setDbValueDef($this->option_b->CurrentValue));

        // option_c
        $newRow->setOptionC($this->option_c->setDbValueDef($this->option_c->CurrentValue));

        // option_d
        $newRow->setOptionD($this->option_d->setDbValueDef($this->option_d->CurrentValue));

        // correct_option
        $newRow->setCorrectOption($this->correct_option->setDbValueDef($this->correct_option->CurrentValue));

        // marks
        if (!IsEmpty(strval($this->marks->CurrentValue))) {
            $newRow->setMarks($this->marks->setDbValueDef($this->marks->CurrentValue));
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
        $breadcrumb->add("list", $this->TableVar, $this->addMasterUrl("McqQuestionsList"), "", $this->TableVar, true);
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
