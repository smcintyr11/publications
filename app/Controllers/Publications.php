<?php namespace App\Controllers;

use App\Models\PublicationModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;
use App\Controllers\SystemVariables;

// Load the authentication helper
helper(['url', 'auth']);

class Publications extends Controller {
  /**
	 * Name: generateIndexQB
	 * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the PublicationID
   *          which is useful for row counts.  Otherwise is will return all columns
	 *
	 * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  string $reportTypeID - Filter based on a specific reportTypeID
   *  string $statusID - Filter based on a specific statusID
   *  string $costCentreID - Filter based on a specific costCentreID
   *  string $createdBy - Filter based on a specific createdBy value
   *  string $statusPersonID - Filter based on  a specific statusPersonID
   *  bool $detailed - Should only the PublicationID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
	 *
	 * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, ?string $reportTypeID, ?string $statusID, ?string $costCentreID,
  ?string $createdBy, ?string $statusPersonID, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Publications');

    // Generate the builder object
    if ($detailed) {
      $builder->select("publications.Publications.PublicationID, publications.CostCentres.CostCentre, publications.Publications.ProjectCode,
        publications.Publications.ReportNumber, publications.ReportTypes.Abbreviation, publications.ReportTypes.ReportType,
        publications.Publications.PrimaryTitle, publications.Statuses.Status, publications.vPublicationAuthors.PublicationAuthors,
        publications.Publications.StatusDueDate, IFNULL((DATEDIFF(publications.Publications.StatusDueDate, CURDATE())), 10000) AS DueDateDelta,
        publications.Publications.RushPublication, users.users.DisplayName AS StatusPerson");
    } else {
      $builder->select('publications.Publications.PublicationID');
    }
    $builder->join('publications.CostCentres', 'publications.Publications.CostCentreID = publications.CostCentres.CostCentreID', 'left');
    $builder->join('publications.ReportTypes', 'publications.Publications.ReportTypeID = publications.ReportTypes.ReportTypeID', 'left');
    $builder->join('publications.Statuses', 'publications.Publications.StatusID = publications.Statuses.StatusID', 'left');
    $builder->join('users.users', 'publications.Publications.StatusPersonID = users.users.ID', 'left');
    $builder->join('publications.vPublicationAuthors', 'publications.Publications.PublicationID = publications.vPublicationAuthors.PublicationID', 'left');

    // Are we filtering
    $builder->where('publications.Publications.deleted_at', null);
    if ($filter != '') {
      $builder->groupStart();
      if (empty($costCentreID)) {
        $builder->like('publications.CostCentres.CostCentre', $filter);
      }
      $builder->orLike('publications.Publications.ProjectCode', $filter);
      $builder->orLike('publications.Publications.ReportNumber', $filter);
      if (empty($reportTypeID)) {
        $builder->orLike('publications.ReportTypes.Abbreviation', $filter);
        $builder->orLike('publications.ReportTypes.ReportType', $filter);
      }
      $builder->orLike('publications.Publications.PrimaryTitle', $filter);
      if (empty($statusID)) {
        $builder->orLike('publications.Statuses.Status', $filter);
      }
      $builder->orLike('publications.vPublicationAuthors.PublicationAuthors', $filter);
      $builder->orLike('users.users.DisplayName', $filter);
      $builder->groupEnd();
    }

    if (empty($reportTypeID) == false) {
      $builder->where('publications.Publications.ReportTypeID', $reportTypeID);
    }
    if (empty($statusID) == false) {
      $builder->where('publications.Publications.StatusID', $statusID);
    }
    if (empty($costCentreID) == false) {
      $builder->where('publications.Publications.CostCentreID', $costCentreID);
    }
    if (empty($createdBy) == false) {
      $builder->where('publications.Publications.CreatedBy', $createdBy);
    }
    if (empty($statusPersonID) == false) {
      $builder->where('publications.Publications.StatusPersonID', $statusPersonID);
    }
    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "cc_asc") {
        $builder->orderBy("publications.CostCentres.CostCentre", "ASC");
      } elseif ($sorting == "cc_desc") {
        $builder->orderBy("publications.CostCentres.CostCentre", "DESC");
      } elseif ($sorting == "pc_asc") {
        $builder->orderBy("publications.Publications.ProjectCode", "ASC");
      } elseif ($sorting == "pc_desc") {
        $builder->orderBy("publications.Publications.ProjectCode", "DESC");
      } elseif ($sorting == "rn_asc") {
        $builder->orderBy("publications.Publications.ReportNumber", "ASC");
      } elseif ($sorting == "rn_desc") {
        $builder->orderBy("publications.Publications.ReportNumber", "DESC");
      } elseif ($sorting == "rt_asc") {
        $builder->orderBy("publications.ReportTypes.ReportType", "ASC");
      } elseif ($sorting == "rt_desc") {
        $builder->orderBy("publications.ReportTypes.ReportType", "DESC");
      } elseif ($sorting == "pt_asc") {
        $builder->orderBy("publications.Publications.PrimaryTitle", "ASC");
      } elseif ($sorting == "pt_desc") {
        $builder->orderBy("publications.Publications.PrimaryTitle", "DESC");
      } elseif ($sorting == "status_asc") {
        $builder->orderBy("publications.Statuses.Status", "ASC");
      } elseif ($sorting == "status_desc") {
        $builder->orderBy("publications.Statuses.Status", "DESC");
      } elseif ($sorting == "at_asc") {
        $builder->orderBy("users.users.DisplayName", "ASC");
      } elseif ($sorting == "at_desc") {
        $builder->orderBy("users.users.DisplayName", "DESC");
      } elseif ($sorting == "pa_asc") {
        $builder->orderBy("publications.vPublicationAuthors.PublicationAuthors", "ASC");
      } elseif ($sorting == "pa_desc") {
        $builder->orderBy("publications.vPublicationAuthors.PublicationAuthors", "DESC");
      } elseif ($sorting == "dd_asc") {
        $builder->orderBy("DueDateDelta", "DESC");
      } else {
        $builder->orderBy("DueDateDelta", "ASC");
      }
    }

    // return the object
    return $builder;
  }

  /**
   * Name: getMaxRows
   * Purpose: Gets the maximum number of rows in the table or the maximum number
   *  of filtered rows in the table.
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  string $reportTypeID - Filter based on a specific reportTypeID
   *  string $statusID - Filter based on a specific statusID
   *  string $costCentreID - Filter based on a specific costCentreID
   *  string $createdBy - Filter based on a specific createdBy value
   *  string $statusPersonID - Filter based on  a specific statusPersonID
   *
   * Returns: int - The number of rows
   */
  public function getMaxRows(string $filter = '', ?string $reportTypeID = null, ?string $statusID = null,
  ?string $costCentreID = null, ?string $createdBy = null, ?string $statusPersonID = null) {
    // Get the maximum number of rows
    return $this->generateIndexQB($filter, $reportTypeID, $statusID, $costCentreID, $createdBy, $statusPersonID)->get()->getNumRows();
  }

  /**
	 * Name: processIndexSession
	 * Purpose: Processes the session data populating any mission session settings.
	 *
	 * Parameters:
   *  $session - Session object
   *  $detailed - boolean indicating whether it's the detailed index
	 *
	 * Returns: None
	 */
  public function processIndexSession($session, $indexType) {
    // Setup rows per page if it doesn't exist
    if ($session->has('rowsPerPage') == false) {
      $session->set('rowsPerPage', 25);
    }

    // Calculate the max rows
    $filter = is_null($session->get('filter')) ? '' : $session->get('filter');
    $session->set('maxRows', $this->getMaxRows($filter, $this->request->getPost('reportTypeID'),
      $this->request->getPost('statusID'), $this->request->getPost('costCentreID'),
      ($indexType == "myPublications") ? user_id() : null, ($indexType == "assignedToMe") ? user_id() : null));

    // Are we coming from a publications page
    if (substr($session->get('lastPage'), 0, 12) == 'Publications') {
      // Get the previous indexType
      $previousIndex = substr($session->get('lastPage'),14);

      // Check to see if we are coming from the same indexType
      if ($previousIndex == $indexType) {
        // Current sort
        if ($session->has('currentSort') == false) {
          $session->set('currentSort', 'id_asc');
        }
        // Filter
        if ($session->has('filter') == false) {
          $session->set('filter', '');
        }
      } else {
        $session->set('currentSort', 'id_asc');
        $session->set('filter', '');
      }

    } else {    // Not from index - setup variables
      // Setup the filter and max rows
      $session->set('filter', '');
      $session->set('currentSort', 'dd_desc');
    }

    // Last Page
    if ($indexType == "indexDetailed") {
      $session->set('lastPage', 'Publications::indexDetailed');
      $session->set('publicationIndex', 'indexDetailed');
    } elseif ($indexType == "myPublications") {
      $session->set('lastPage', 'Publications::myPublications');
      $session->set('publicationIndex', 'myPublications');
    } elseif ($indexType == "assignedToMe") {
      $session->set('lastPage', 'Publications::assignedToMe');
      $session->set('publicationIndex', 'assignedToMe');
    } else {
      $session->set('lastPage', 'Publications::index');
      $session->set('publicationIndex', 'index');
    }
  }

  /**
   * Name: index
   * Purpose: Generates the index page
   *
   * Parameters: None
   *
   * Returns: None
   */
  public function index() {
     // Check to see if the user is logged in
     if (logged_in() == false) {
       $_SESSION['redirect_url'] = base_url() . '/publications/index';
       return redirect()->to(base_url() . '/login');

       if (in_groups(['pubsAdmin', 'pubsRC', 'pubsAuth', 'pubsRCMan']) == false) {
         $data = [
           'title' => 'Not Authorized',
         ];
         echo view('templates/header.php', $data);
         echo view('templates/menu.php', $data);
         echo view('errors/notAuthorized.php', $data);
         echo view('templates/footer.php', $data);
         return;
       }
     }

     // Load helpers
     helper(['form']);

     // Get the services
     $uri = service('uri');
     $session = session();

     // Process the session data
     $this->processIndexSession($session, "index");

     // Parse the URI
     $page = $uri->setSilent()->getSegment(3, 1);

     // Get the sort parameter
     $sort = $uri->getQuery(['only' => ['sort']]);
     if ($sort != '') {
       $sort = substr($sort, 5);
       $session->set('currentSort', $sort);
       $page = 1;
     }

     // Get the filter parameter
     $filter = $uri->getQuery(['only' => ['filter']]);
     if ($filter != '') {
       $filter = substr($filter, 7);
       $session->set('filter', $filter);
     }

     // Check for a post
     if ($this->request->getMethod() === "post") {
       $session->set('filter', $this->request->getPost('filter'));
       if ($this->request->getPost('rowsPerPage') != $session->get('rowsPerPage')) {
         $session->set('rowsPerPage', $this->request->getPost('rowsPerPage'));
       }
     }

     // Generate the pager object
     $builder = $this-> generateIndexQB($session->get('filter'), $this->request->getPost('reportTypeID'), $this->request->getPost('statusID'), $this->request->getPost('costCentreID'), null, null, true, $session->get('currentSort'));
     $this->pager = new \App\Libraries\MyPager(current_url(true), $builder->getCompiledSelect(), $session->get('rowsPerPage'), $session->get('maxRows'), $page);

     // Get the publication model
     $model = new PublicationModel();

     // Populate the data going to the view
     $data = [
       'publications' => $this->pager->getCurrentRows(),
       'links' => $this->pager->createLinks(),
       'title' => 'Publications',
       'reportTypes' => $this->getReportTypes(),
       'statuses' => $this->getStatuses(),
       'costCentres' => $this->getCostCentres(),
       'page' => $page,
     ];

     // Generate the view
     echo view('templates/header.php', $data);
 		 echo view('templates/menu.php', $data);
 		 echo view('publications/index.php', $data);
 		 echo view('templates/footer.php', $data);
   }

  /**
  * Name: indexDetailed
  * Purpose: Generates the index page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function indexDetailed() {
    // Check to see if the user is logged in
    if (logged_in() == false) {
      $_SESSION['redirect_url'] = base_url() . '/publications/indexDetailed';
      return redirect()->to(base_url() . '/login');

      if (in_groups(['pubsAdmin', 'pubsRC', 'pubsAuth', 'pubsRCMan']) == false) {
        $data = [
          'title' => 'Not Authorized',
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('errors/notAuthorized.php', $data);
        echo view('templates/footer.php', $data);
        return;
      }
    }

    // Load helpers
    helper(['form']);

    // Get the services
    $uri = service('uri');
    $session = session();

    // Process the session data
    $this->processIndexSession($session, "indexDetailed");

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);

    // Get the sort parameter
    $sort = $uri->getQuery(['only' => ['sort']]);
    if ($sort != '') {
      $sort = substr($sort, 5);
      $session->set('currentSort', $sort);
      $page = 1;
    }

    // Get the filter parameter
    $filter = $uri->getQuery(['only' => ['filter']]);
    if ($filter != '') {
      $filter = substr($filter, 7);
      $session->set('filter', $filter);
    }

    // Check for a post
    if ($this->request->getMethod() === "post") {
      $session->set('filter', $this->request->getPost('filter'));
      if ($this->request->getPost('rowsPerPage') != $session->get('rowsPerPage')) {
        $session->set('rowsPerPage', $this->request->getPost('rowsPerPage'));
      }
    }

    // Generate the pager object
    $builder = $this-> generateIndexQB($session->get('filter'), $this->request->getPost('reportTypeID'), $this->request->getPost('statusID'), $this->request->getPost('costCentreID'), null, null, true, $session->get('currentSort'));
    $this->pager = new \App\Libraries\MyPager(current_url(true), $builder->getCompiledSelect(), $session->get('rowsPerPage'), $session->get('maxRows'), $page);

    // Get the publication model
    $model = new PublicationModel();

    // Populate the data going to the view
    $data = [
      'publications' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Publications',
      'reportTypes' => $this->getReportTypes(),
      'statuses' => $this->getStatuses(),
      'costCentres' => $this->getCostCentres(),
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('publications/indexDetailed.php', $data);
		echo view('templates/footer.php', $data);
  }

  /**
  * Name: new
  * Purpose: Generates the new page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function new() {
    // Check to see if the user is logged in
    if (logged_in() == false) {
      $_SESSION['redirect_url'] = base_url() . '/publications/new/1';
      return redirect()->to(base_url() . '/login');

      if (in_groups(['pubsAdmin', 'pubsRC', 'pubsAuth', 'pubsRCMan']) == false) {
        $data = [
          'title' => 'Not Authorized',
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('errors/notAuthorized.php', $data);
        echo view('templates/footer.php', $data);
        return;
      }
    }

    // Create a new Model
    $model = new PublicationModel();
    $variables = new SystemVariables();

    // Load the lookup tables
    $statuses = $this->getStatuses();
    $reportTypes = $this->getReportTypes();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Publications::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('primaryTitle', 'Primary Title', 'required');
      $validation->setRule('reportTypeNID', 'Report Type', 'required');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Get the default statusID
        $statusID = $this->getDefaultStatus();
        if (empty($statusID) == false) {
          // Try to guess the next report number
          $reportNumber = $this->generateReportNumber($this->request->getPost('reportTypeNID'));

          // Save
          $model->save([
            'CreatedBy' => user_id(),
            'PrimaryTitle' => $this->request->getPost('primaryTitle'),
            'ReportTypeID' => $this->request->getPost('reportTypeNID'),
            'ReportNumber' => $reportNumber,
            'StatusID' => $statusID,
          ]);

          // Get the publication id
          $publicationID = $this->getLastPublicationID($this->request->getPost('primaryTitle'), $this->request->getPost('reportTypeNID'));

          // Add the new publications statuses entry
          $this->newStatus($publicationID, $statusID, null, null);

          // Open the newly added publication
          $data = [
            'title' => 'Edit Publication',
            'publication' => $model->getPublication($publicationID),
            'publication' => $model->getPublication($publicationID),
            'page' => $page,
            'statuses' => $this->getStatuses(),
            'reportTypes' => $this->getReportTypes(),
            'costCentres' => $this->getCostCentres(),
            'statusLog' => $this->getStatusLog($publicationID),
            'authorsList' => $this->getAuthors($publicationID),
            'reviewersList' => $this->getReviewers($publicationID),
            'keywordsList' => $this->getKeywords($publicationID),
            'relatedPublications' => $this->getRelatedPublications($publicationID),
            'linkTypes' => $this->getLinkTypes(),
            'linksList' => $this->getLinks($publicationID),
            'commentsList'=> $this->getComments($publicationID),
            'sensitivityOptions' => $this->getSensitivityOptions(),
            'VHideDetailedFields' => ($variables->getVariable("HideDetailedFields") == 'True' ? True : False),
            'VDisableField' => ($variables->getVariable("DisableField") ? True : False),
          ];
          echo view('templates/header.php', $data);
          echo view('templates/menu.php', $data);
          echo view('publications/edit.php', $data);
          echo view('templates/footer.php', $data);
        } else { // Tell the user no default status exists
          $data = [
            'title' => 'Error',
            'message' => '<p>There is no default status defined in the system.  Please visit the
            <a href="/statuses/index">Statuses</a> lookup table and either modify an existing
            status to be the default, or create a new status that is assigned the default status flag.</p>',
          ];
          echo view('templates/header.php', $data);
          echo view('templates/menu.php', $data);
          echo view('errors/customError.php', $data);
          echo view('templates/footer.php', $data);

        }
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Publication',
          'page' => $page,
          'statuses' => $statuses,
          'reportTypes' => $reportTypes,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('publications/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Publication',
        'page' => $page,
        'statuses' => $statuses,
        'reportTypes' => $reportTypes,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('publications/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  /**
  * Name: edit
  * Purpose: Generates the edit page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function edit() {
   // Get the URI service
   $uri = service('uri');

   // Check to see if the user is logged in
   if (logged_in() == false) {
     $page = $uri->setSilent()->getSegment(3, 1);
     $publicationID = $uri->getSegment(4);
     $_SESSION['redirect_url'] = base_url() . '/publications/edit/' . $page . '/' . $publicationID;
     return redirect()->to(base_url() . '/login');
   }

   // Create a new Model
   $model = new PublicationModel();
   $variables = new SystemVariables();

   // Load helpers
   helper(['url', 'form', 'auth']);
   $validation = \Config\Services::validation();

   // Load the lookup tables
   $statuses = $this->getStatuses();
   $reportTypes = $this->getReportTypes();
   $costCentres = $this->getCostCentres();
   $linkTypes = $this->getLinkTypes();
   $sensitivityOptions = $this->getSensitivityOptions();

   // Set the session last page
   $session = session();
   $session->set('lastPage', 'Publications::edit');

   // Is this a post (saving)
   if ($this->request->getMethod() === 'post') {
     // Get the view data from the form
     $page = $this->request->getPost('page');

     // Check the status ID
     if (empty($this->request->getPost('statusID'))) {
       $statusID = $this->request->getPost('originalStatusID');
     } else {
       $statusID = $this->request->getPost('statusID');
     }

     // Validate the data
     $validation->setRule('primaryTitle', 'Primary Title', 'required');
     $validation->setRule('reportTypeID', 'Report Type', 'required');
     if (!empty($this->request->getPost('publicationDate'))) {
        $validation->setRule('publicationDate', 'Publication Date', 'valid_date');
     }
     $validation->setRule('volume', 'Volume', 'max_length[16]');
     $validation->setRule('isbn', 'ISBN', 'max_length[64]');
     $validation->setRule('agreementNumber', 'Agreement Number', 'max_length[64]');
     $validation->setRule('ipdNumber', 'IPD Number', 'max_length[64]');
     $validation->setRule('crossReferenceNumber', 'Cross Reference Number', 'max_length[64]');
     $validation->setRule('projectCode', 'Project Code', 'max_length[64]');
     $validation->setRule('reportNumber', 'Report Number', 'max_length[64]');
     $validation->setRule('manuscriptNumber', 'Manuscript Number', 'max_length[64]');
     $validation->setRule('isbn', 'ISBN', 'max_length[64]');

     if (!empty($this->request->getPost('statusDueDate'))) {
       $validation->setRule('statusDueDate ', 'Status Due Date ', 'valid_date');
     }
     $validation->setRule('doi', 'DOI', 'max_length[64]');
     if (!empty($this->request->getPost('journalSubmissionDate'))) {
       $validation->setRule('journalSubmissionDate ', 'Journal Submission Date ', 'valid_date');
     }
     if (!empty($this->request->getPost('journalAcceptanceDate'))) {
       $validation->setRule('journalAcceptanceDate ', 'Journal Acceptance Date ', 'valid_date');
     }
     if (!empty($this->request->getPost('conferenceSubmissionDate'))) {
       $validation->setRule('conferenceSubmissionDate', 'Conference Submission Date', 'valid_date');
     }
     if (!empty($this->request->getPost('conferenceAcceptanceDate'))) {
       $validation->setRule('conferenceAcceptanceDate', 'Conference Acceptance Date', 'valid_date');
     }
     if (!empty($this->request->getPost('embargoPeriod'))) {
       $validation->setRule('embargoPeriod', 'Embargo Period', 'integer|greater_than[0]');
     }
     if (!empty($this->request->getPost('embargoEndDate'))) {
       $validation->setRule('embargoEndDate ', 'Embargo End Date ', 'valid_date');
     }
     if (!empty($this->request->getPost('webPublicationDate'))) {
       $validation->setRule('webPublicationDate', 'Web Publication Date', 'valid_date');
     }
     if (!empty($this->request->getPost('sentToClientDate'))) {
       $validation->setRule('sentToClientDate', 'Sent To Client Date', 'valid_date');
     }
     $validation->setRule('recordNumber', 'Record Number', 'max_length[64]');
     if ($this->request->getPost('sensitivePublication') == "on") {
       $validation->setRule('sensitivityOptionID', 'Send Sensitive Publication To', 'required');
       $validation->setRule('sensitivityDetails', 'Sensitivity Details', 'required');
     }
     if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
       // Save
       $model->save([
         'ModifiedBy' => user_id(),
         'Modified' => date("Y-m-d H:i:s"),
         'PublicationID' => $this->request->getPost('publicationID'), // **
         'PrimaryTitle' => $this->request->getPost('primaryTitle'), // **
         'SecondaryTitle' => $this->request->getPost('secondaryTitle') == "" ? null : $this->request->getPost('secondaryTitle'),
         'PublicationDate' => $this->request->getPost('publicationDate') == "" ? null : $this->request->getPost('publicationDate'),
         'FiscalYearID' => $this->request->getPost('fiscalYearID') == "" ? null : $this->request->getPost('fiscalYearID'),
         'Volume' => $this->request->getPost('volume') == "" ? null : $this->request->getPost('volume'),
         'StartPage' => $this->request->getPost('startPage') == "" ? null : $this->request->getPost('startPage'),
         'EndPage' => $this->request->getPost('endPage') == "" ? null : $this->request->getPost('endPage'),
         'ClientID' => $this->request->getPost('clientID') == "" ? null : $this->request->getPost('clientID'),
         'OrganizationID' => $this->request->getPost('organizationID') == "" ? null : $this->request->getPost('organizationID'),
         'AbstractEnglish' => $this->request->getPost('abstractEnglish') == "" ? null : $this->request->getPost('abstractEnglish'),
         'AbstractFrench' => $this->request->getPost('abstractFrench') == "" ? null : $this->request->getPost('abstractFrench'),
         'PLSEnglish' => $this->request->getPost('plsEnglish') == "" ? null : $this->request->getPost('plsEnglish'),
         'PLSFrench' => $this->request->getPost('plsFrench') == "" ? null : $this->request->getPost('plsFrench'),
         'PRSEnglish' => $this->request->getPost('prsEnglish') == "" ? null : $this->request->getPost('prsEnglish'),
         'PRSFrench' => $this->request->getPost('prsFrench') == "" ? null : $this->request->getPost('prsFrench'),
         'ISBN' => $this->request->getPost('isbn') == "" ? null : $this->request->getPost('isbn'),
         'AgreementNumber' => $this->request->getPost('agreementNumber') == "" ? null : $this->request->getPost('agreementNumber'),
         'IPDNumber' => $this->request->getPost('ipdNumber') == "" ? null : $this->request->getPost('ipdNumber'),
         'CrossReferenceNumber' => $this->request->getPost('crossReferenceNumber') == "" ? null : $this->request->getPost('crossReferenceNumber'),
         'ProjectCode' => $this->request->getPost('projectCode') == "" ? null : $this->request->getPost('projectCode'),
         'ReportNumber' => $this->request->getPost('reportNumber') == "" ? null : $this->request->getPost('reportNumber'),
         'ManuscriptNumber' => $this->request->getPost('manuscriptNumber') == "" ? null : $this->request->getPost('manuscriptNumber'),
         'CostCentreID' => $this->request->getPost('costCentreID') == "" ? null : $this->request->getPost('costCentreID'),
         'JournalID' => $this->request->getPost('journalID') == "" ? null : $this->request->getPost('journalID'),
         'ReportTypeID' => $this->request->getPost('reportTypeID'), // **
         'StatusID' => $statusID, // **
         'StatusPersonID' => $this->request->getPost('statusPersonID') == "" ? null : $this->request->getPost('statusPersonID'),
         'StatusDueDate' => $this->request->getPost('statusDueDate') == "" ? null : $this->request->getPost('statusDueDate'),
         'DOI' => $this->request->getPost('doi') == "" ? null : $this->request->getPost('doi'),
         'JournalSubmissionDate' => $this->request->getPost('journalSubmissionDate') == "" ? null : $this->request->getPost('journalSubmissionDate'),
         'JournalAcceptanceDate' => $this->request->getPost('journalAcceptanceDate') == "" ? null : $this->request->getPost('journalAcceptanceDate'),
         'ConferenceSubmissionDate' => $this->request->getPost('conferenceSubmissionDate') == "" ? null : $this->request->getPost('conferenceSubmissionDate'),
         'ConferenceAcceptanceDate' => $this->request->getPost('conferenceAcceptanceDate') == "" ? null : $this->request->getPost('conferenceAcceptanceDate'),
         'EmbargoPeriod' => $this->request->getPost('embargoPeriod') == "" ? null : $this->request->getPost('embargoPeriod'),
         'EmbargoEndDate' => $this->request->getPost('embargoEndDate') == "" ? null : $this->request->getPost('embargoEndDate'),
         'WebPublicationDate' => $this->request->getPost('webPublicationDate') == "" ? null : $this->request->getPost('webPublicationDate'),
         'SentToClient' => $this->request->getPost("sentToClient") == "on" ? 1 : 0,
         'SentToClientDate' => $this->request->getPost('sentToClientDate') == "" ? null : $this->request->getPost('sentToClientDate'),
         'ReportFormatted' => $this->request->getPost('reportFormatted') == "on" ? 1 : 0,
         'RecordNumber' => $this->request->getPost('recordNumber') == "" ? null : $this->request->getPost('recordNumber'),
         'RushPublication' => $this->request->getPost('rushPublication') == "on" ? 1 : 0,
         'SubmissionDeadline' => $this->request->getPost('submissionDeadline') == "" ? null : $this->request->getPost('submissionDeadline'),
         'ConferenceName' => $this->request->getPost('conferenceName') == "" ? null : $this->request->getPost('conferenceName'),
         'ConferenceDate' => $this->request->getPost('conferenceDate') == "" ? null : $this->request->getPost('conferenceDate'),
         'ConferenceLocation' => $this->request->getPost('conferenceLocation') == "" ? null : $this->request->getPost('conferenceLocation'),
         'SensitivePublication' => $this->request->getPost('sensitivePublication') == "on" ? 1 : 0,
         'SensitivityOptionID' => $this->request->getPost('sensitivityOptionID') == "" ? null : $this->request->getPost('sensitivityOptionID'),
         'SensitivityDetails' => $this->request->getPost('sensitivityDetails') == "" ? null : $this->request->getPost('sensitivityDetails'),
         'ArisingIP' => $this->request->getPost('arisingIP') == "on" ? 1 : 0,
         'IPDisclosureKitComplete' => $this->request->getPost('ipDisclosureKitComplete') == "on" ? 1 : 0,
       ]);

       // Did the status change?  If so update the publications statuses table
       if ($this->request->getPost('originalStatusID') != $statusID) {
        $this->updateCompleteStatus($this->request->getPost('publicationID'), $this->request->getPost('originalStatusID'));
        $this->newStatus($this->request->getPost('publicationID'), $statusID, $this->request->getPost('statusPersonID'), $this->request->getPost('statusDueDate'));
      } elseif (($this->request->getPost('originalStatusPersonID') != $this->request->getPost('statusPersonID')) || ($this->request->getPost('originalStatusDueDate') != $this->request->getPost('statusDueDate'))) {
        $this->newStatus($this->request->getPost('publicationID'), $statusID, $this->request->getPost('statusPersonID'), $this->request->getPost('statusDueDate'));
      }

       // Go back to index
       $idx = session('publicationIndex') ?? 'index';
       return redirect()->to(base_url() . "/publications/" . $idx . "/".$page);
     } else  {  // Invalid - Redisplay the form
       // Generate the view
       $publicationID = $this->request->getPost('publicationID');
       $data = [
         'title' => 'Edit Publication',
         'publication' => $model->getPublication($publicationID),
         'page' => $page,
         'statuses' => $statuses,
         'reportTypes' => $reportTypes,
         'costCentres' => $costCentres,
         'statusLog' => $this->getStatusLog($publicationID),
         'authorsList' => $this->getAuthors($publicationID),
         'reviewersList' => $this->getReviewers($publicationID),
         'keywordsList' => $this->getKeywords($publicationID),
         'linkTypes' => $linkTypes,
         'linksList' => $this->getLinks($publicationID),
         'commentsList' => $this->getComments($publicationID),
         'sensitivityOptions' => $sensitivityOptions,
         'VHideDetailedFields' => ($variables->getVariable("HideDetailedFields") == 'True' ? True : False),
         'VDisableField' => ($variables->getVariable("DisableField") ? True : False),
       ];
       echo view('templates/header.php', $data);
       echo view('templates/menu.php', $data);
       echo view('publications/edit.php', $data);
       echo view('templates/footer.php', $data);
     }
   } else {  // Load edit page
     // Parse the URI
     $page = $uri->setSilent()->getSegment(3, 1);
     $publicationID = $uri->getSegment(4);
     $publication = $model->getPublication($publicationID);

     // Generate the edit view
     $data = [
       'title' => 'Edit Publication',
       'publication' => $publication,
       'page' => $page,
       'statuses' => $statuses,
       'reportTypes' => $reportTypes,
       'costCentres' => $costCentres,
       'statusLog' => $this->getStatusLog($publicationID),
       'authorsList' => $this->getAuthors($publicationID),
       'reviewersList' => $this->getReviewers($publicationID),
       'keywordsList' => $this->getKeywords($publicationID),
       'linkTypes' => $linkTypes,
       'linksList' => $this->getLinks($publicationID),
       'relatedPublications' => $this->getRelatedPublications($publicationID),
       'commentsList'=> $this->getComments($publicationID),
       'sensitivityOptions' => $sensitivityOptions,
       'VHideDetailedFields' => ($variables->getVariable("HideDetailedFields") == 'True' ? True : False),
       'VDisableField' => ($variables->getVariable("DisableField") ? True : False),
     ];
     echo view('templates/header.php', $data);
     echo view('templates/menu.php', $data);
     echo view('publications/edit.php', $data);
     echo view('templates/footer.php', $data);
   }
  }

  /**
  * Name: getStatuses
  * Purpose: Get a list of all statuses in the database
  *
  * Parameters: None
  *
  * Returns: Array of objects representing the rows
  */
  private function getStatuses() {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('Statuses');
   $builder->select("StatusID, Status, ExpectedDuration, DefaultStatus");
   $builder->where('deleted_at', null);
   $builder->orderBy("Status");

   // Return the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getReportTypes
  * Purpose: Get a list of all report types in the database
  *
  * Parameters: None
  *
  * Returns: Array of objects representing the rows
  */
  private function getReportTypes() {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('ReportTypes');
   $builder->select("ReportTypeID, ReportType, Abbreviation");
   $builder->where('deleted_at', null);
   $builder->orderBy("ReportType");

   // Return the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getLinkTypes
  * Purpose: Get a list of all link types in the database
  *
  * Parameters: None
  *
  * Returns: Array of objects representing the rows
  */
  private function getLinkTypes() {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('LinkTypes');
   $builder->select("LinkTypeID, LinkType");
   $builder->where('deleted_at', null);
   $builder->orderBy("LinkType");

   // Return the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getCostCentres
  * Purpose: Get a list of all cost centres in the database
  *
  * Parameters: None
  *
  * Returns: Array of objects representing the rows
  */
  private function getCostCentres() {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('CostCentres');
   $builder->select("CostCentreID, CONCAT(CostCentre, ' (', Description, ')') AS CostCentre");
   $builder->where('deleted_at', null);
   $builder->orderBy("CostCentre");

   // Return the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getSensitivityOptions
  * Purpose: Get a list of all sensitivity options in the database
  *
  * Parameters: None
  *
  * Returns: Array of objects representing the rows
  */
  private function getSensitivityOptions() {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('SensitivityOptions');
   $builder->select("SensitivityOptionID, SensitivityOption");
   $builder->orderBy("SensitivityOptionID");

   // Return the result
   return $builder->get()->getResult();
  }


  /**
  * Name: getStatusLog
  * Purpose: Get a list of all items in the PublicationsStatuses table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getStatusLog(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('publications.PublicationsStatuses');
   $builder->select("publications.PublicationsStatuses.PublicationsStatusesID, publications.PublicationsStatuses.DateModified, publications.Statuses.Status, users.users.displayName AS DisplayName, publications.PublicationsStatuses.DueDate, publications.PublicationsStatuses.CompletionDate");
   $builder->join('publications.Statuses', 'publications.PublicationsStatuses.StatusID = publications.Statuses.StatusID', 'left');
   $builder->join('users.users', 'publications.PublicationsStatuses.StatusPersonID = users.users.ID', 'left');
   $builder->where('publications.PublicationsStatuses.deleted_at', null);
   $builder->where('publications.PublicationsStatuses.PublicationID', $publicationID);
   $builder->orderBy("publications.PublicationsStatuses.DateModified", "DESC");

   // Return the result
   // return $builder->get()->getResult();
   return $builder->get()->getResult();
  }

  /**
  * Name: getAuthors
  * Purpose: Get a list of all items in the PublicationsAuthors table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getAuthors(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('PublicationsAuthors');
   $builder->select("PublicationsAuthorsID, DisplayName, PrimaryAuthor");
   $builder->join("vPeopleDropDown", 'PublicationsAuthors.PersonID = vPeopleDropDown.PersonID', 'left');
   $builder->where('PublicationsAuthors.deleted_at', null);
   $builder->where('PublicationID', $publicationID);
   $builder->orderBy("PrimaryAuthor", "DESC");
   $builder->orderBy("vPeopleDropDown.DisplayName");

   // Retturn the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getReviewers
  * Purpose: Get a list of all items in the PublicationsReviewers table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getReviewers(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('PublicationsReviewers');
   $builder->select("PublicationsReviewersID, DisplayName, LeadReviewer");
   $builder->join("vPeopleDropDown", 'PublicationsReviewers.PersonID = vPeopleDropDown.PersonID', 'left');
   $builder->where('PublicationsReviewers.deleted_at', null);
   $builder->where('PublicationID', $publicationID);
   $builder->orderBy("LeadReviewer", "DESC");
   $builder->orderBy("vPeopleDropDown.DisplayName");

   // Retturn the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getKeywords
  * Purpose: Get a list of all items in the PublicationsKeywords table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getKeywords(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('PublicationsKeywords');
   $builder->select("PublicationsKeywordsID, KeywordEnglish, KeywordFrench");
   $builder->join("Keywords", 'PublicationsKeywords.KeywordID = Keywords.KeywordID', 'left');
   $builder->where('PublicationsKeywords.deleted_at', null);
   $builder->where('PublicationID', $publicationID);
   $builder->orderBy("KeywordEnglish");

   // Retturn the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getLinks
  * Purpose: Get a list of all items in the PublicationsLinks table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getLinks(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('PublicationsLinks');
   $builder->select("PublicationsLinksID, Link, LinkType");
   $builder->join("LinkTypes", 'PublicationsLinks.LinkTypeID = LinkTypes.LinkTypeID', 'left');
   $builder->where('PublicationsLinks.deleted_at', null);
   $builder->where('PublicationID', $publicationID);
   $builder->orderBy("PublicationsLinksID");

   // Retturn the result
   return $builder->get()->getResult();
  }

  /**
  * Name: getRelatedPublications
  * Purpose: Get a list of all related publicationns from the RelatedPublications
  *   table
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getRelatedPublications(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the initial query
   $builder = $db->table('RelatedPublications');
   $builder->select("RelatedPublicationsID, ParentPublicationID");
   $builder->where('deleted_at', null);
   $builder->where('ChildPublicationID', $publicationID);

   // Compile the results
   $results = array();
   foreach ($builder->get()->getResult() as $row) {
     array_push($results, $this->getRelatedPublicationDetails($row->RelatedPublicationsID, $row->ParentPublicationID));
   }

   // Generate the 2nd query
   $builder2 = $db->table('RelatedPublications');
   $builder2->select("RelatedPublicationsID, ChildPublicationID");
   $builder2->where('deleted_at', null);
   $builder2->where('ParentPublicationID', $publicationID);

   // Compile the results
   foreach ($builder2->get()->getResult() as $row) {
     array_push($results, $this->getRelatedPublicationDetails($row->RelatedPublicationsID, $row->ChildPublicationID));
   }

   // Return the results
   return $results;
  }

  /**
  * Name: getRelatedPublicationDetails
  * Purpose: Get the details (PublicationID, ReportType, ReportNumber) of the
  *   specified PublicationID
  *
  * Parameters:
  *   string $relatedPublicationsID - The RelatedPublicationID (used for populating results only)
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the row
  */
  private function getRelatedPublicationDetails(string $relatedPublicationsID, string $publicationID) {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    $builder = $db->table('Publications');
    $builder->select("Publications.ReportNumber, Publications.PrimaryTitle, ReportTypes.ReportType");
    $builder->join("ReportTypes", 'Publications.ReportTypeID = ReportTypes.ReportTypeID', 'left');
    $builder->where('Publications.deleted_at', null);
    $builder->where('PublicationID', $publicationID);

    // Return the results
    $result = $builder->get()->getRow();
    return array("relatedPublicationsID" => $relatedPublicationsID, "publicationID" => $publicationID,
      "primaryTitle" => $result->PrimaryTitle, "reportNumber" => $result->ReportNumber, "reportType" => $result->ReportType);
  }

  /**
  * Name: getComments
  * Purpose: Get a list of all items in the PublicationsComments table related to this publication
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are filtering against
  *
  * Returns: Array of objects representing the rows
  */
  private function getComments(string $publicationID) {
   // Load the query builder
   $db = \Config\Database::connect('publications');

   // Generate the query
   $builder = $db->table('PublicationsComments');
   $builder->select("PublicationsCommentsID, DateEntered, Comment");
   $builder->where('deleted_at', null);
   $builder->where('PublicationID', $publicationID);
   $builder->orderBy("DateEntered", "DESC");

   // Retturn the result
   return $builder->get()->getResult();
  }

  /**
  * Name: updateCompleteStatus
  * Purpose: Updates the date completed field in the PublicationsStatuses table
  *  for the latest entry for the given PublicationID/StatusID combination
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are updating
  *   string $statusID - The StatusID we are updating
  *
  * Returns: None
  */
  private function updateCompleteStatus(string $publicationID, string $statusID) {
    // Load the authentication helper
    helper('auth');

    // Get the ID of the PublicationsStatuses row to updated
    $publicationsStatusesID = $this->getLastPublicationStatusesID($publicationID, $statusID);

    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    date_default_timezone_set("America/Edmonton");
    $builder = $db->table('PublicationsStatuses');
    $builder->set('CompletionDate', date("Y-m-d H:i:s"));
    $builder->set('ModifiedBy', user_id());
    $builder->where('PublicationsStatusesID', $publicationsStatusesID);
    $builder->update();
  }

  /**
  * Name: getLastPublicationStatusesID
  * Purpose: Updates the date completed field in the PublicationsStatuses table
  *  for the latest entry for the given PublicationID/StatusID combination
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are updating
  *   string $statusID - The StatusID we are updating
  *
  * Returns:
  */
  private function getLastPublicationStatusesID(string $publicationID, string $statusID) {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    $builder = $db->table('PublicationsStatuses');
    $builder->selectMax("PublicationsStatusesID");
    $builder->where('deleted_at', null);
    $builder->where('PublicationID', $publicationID);
    $builder->where('StatusID', $statusID);

    // Return the result
    $result = $builder->get()->getRow();
    return $result->PublicationsStatusesID;
  }

  /**
  * Name: newStatus
  * Purpose: Adds a new row to the PublicationsStatuses table using the provided parameters
  *
  * Parameters:
  *   string $publicationID - The PublicationID we are inserting
  *   string $statusID - The StatusID we are inserting
  *   string $statusPersonID - The StatusPersonID we are inserting
  *   string $dueDate - The StatusDueDate we are inserting
  *
  * Returns: None
  */
  private function newStatus(string $publicationID, string $statusID, ?string $statusPersonID, ?string $dueDate) {
    // Load the helpers
    helper(['auth']);

    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    date_default_timezone_set("America/Edmonton");
    $builder = $db->table('PublicationsStatuses');
    $builder->set('CreatedBy', user_id());
    $builder->set('PublicationID', $publicationID);
    $builder->set('StatusID', $statusID);
    $builder->set('DateModified', date("Y-m-d H:i:s"));
    if (empty($statusPersonID) == false) {
      $builder->set('StatusPersonID', $statusPersonID);
    }
    if (empty($dueDate) == false) {
      $builder->set('DueDate', $dueDate);
    }
    $builder->insert();
  }

  /**
  * Name: getLastPublicationID
  * Purpose: Gets the latest publicationID with the matching primary title,
  *  report type id, with an initial status
  *
  * Parameters:
  *   string $primaryTitle - The primary title we are searching for
  *   string $reportTypeID - The report type id we are searching for
  *
  * Returns:
  *  The publication id
  */
  private function getLastPublicationID(string $primaryTitle, string $reportTypeID) {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    $builder = $db->table('Publications');
    $builder->selectMax('PublicationID');
    $builder->where('deleted_at', null);
    $builder->where('PrimaryTitle', $primaryTitle);
    $builder->where('ReportTypeID', $reportTypeID);
    $builder->where('StatusID', $this->getDefaultStatus());

    // Return the result
    $result = $builder->get()->getRow();
    return $result->PublicationID;
  }

  /**
  * Name: getDefaultStatus
  * Purpose: Gets the first StatusID (should be only 1) of the Status row where
  *  DefaultStatus = 1 (True)
  *
  * Parameters: None
  *
  * Returns:
  *  The StatusID
  */
  private function getDefaultStatus() {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    $builder = $db->table('Statuses');
    $builder->selectMax('StatusID');
    $builder->where('deleted_at', null);
    $builder->where('DefaultStatus', 1);

    // Return the result
    $result = $builder->get()->getRow();
    if (empty($result)) {
      return null;
    }
    return $result->StatusID;
  }

  /**
  * Name: delete
  * Purpose: Generates the delete page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function delete() {
    // Get the URI service
    $uri = service('uri');

    // Check to see if the user is logged in
    if (logged_in() == false) {
      $publicationID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/publications/delete/1/' . $publicationID;
      return redirect()->to(base_url() . '/login');
    }

    // Check to see if the user is in the appropriate group
    if (in_groups(['pubsRC', 'pubsAdmin']) == false) {
      $data = [
        'title' => 'Not Authorized',
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('errors/notAuthorized.php', $data);
      echo view('templates/footer.php', $data);
      return;
    }

    // Get the model
    $model = new PublicationModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Publications::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the publication
      $model->deletePublication($this->request->getPost('publicationID'));

      // Set the page
      $page = 1;

      // Go back to index
      $idx = session('publicationIndex') ?? 'index';
      return redirect()->to(base_url() . "/publications/" . $idx . "/");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $publicationID = $uri->getSegment(4);

      // Generate the delete view
      $data = [
        'title' => 'Delete Publication',
        'publication' => $model->getPublication($publicationID),
        'page' => $page,
        'statusLog' => $this->getStatusLog($publicationID),
        'authorsList' => $this->getAuthors($publicationID),
        'reviewersList' => $this->getReviewers($publicationID),
        'keywordsList' => $this->getKeywords($publicationID),
        'relatedPublications' => $this->getRelatedPublications($publicationID),
        'linksList' => $this->getLinks($publicationID),
        'commentsList'=> $this->getComments($publicationID),
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('publications/delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  /**
  * Name: view
  * Purpose: Generates the view page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function view() {
    // Get the URI service
    $uri = service('uri');

    // Check to see if the user is logged in
    if (logged_in() == false) {
      $page = $uri->setSilent()->getSegment(3, 1);
      $publicationID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/publications/view/' . $page . '/' . $publicationID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the model
    $model = new PublicationModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Publications::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $publicationID = $uri->getSegment(4);

    // Generate the delete view
    $data = [
      'title' => 'View Publication',
      'publication' => $model->getPublication($publicationID),
      'page' => $page,
      'statusLog' => $this->getStatusLog($publicationID),
      'authorsList' => $this->getAuthors($publicationID),
      'reviewersList' => $this->getReviewers($publicationID),
      'keywordsList' => $this->getKeywords($publicationID),
      'linksList' => $this->getLinks($publicationID),
      'relatedPublications' => $this->getRelatedPublications($publicationID),
      'commentsList'=> $this->getComments($publicationID),
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('publications/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
  * Name: myPublications
  * Purpose: Generates the index page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function myPublications() {
    // Check to see if the user is logged in
    if (logged_in() == false) {
      $_SESSION['redirect_url'] = base_url() . '/publications/myPublications';
      return redirect()->to(base_url() . '/login');

      if (in_groups(['pubsAdmin', 'pubsRC', 'pubsAuth', 'pubsRCMan']) == false) {
        $data = [
          'title' => 'Not Authorized',
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('errors/notAuthorized.php', $data);
        echo view('templates/footer.php', $data);
        return;
      }
    }

    // Load helpers
    helper(['form']);

    // Get the services
    $uri = service('uri');
    $session = session();

    // Process the session data
    $this->processIndexSession($session, "myPublications");

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);

    // Get the sort parameter
    $sort = $uri->getQuery(['only' => ['sort']]);
    if ($sort != '') {
      $sort = substr($sort, 5);
      $session->set('currentSort', $sort);
      $page = 1;
    }

    // Get the filter parameter
    $filter = $uri->getQuery(['only' => ['filter']]);
    if ($filter != '') {
      $filter = substr($filter, 7);
      $session->set('filter', $filter);
    }

    // Check for a post
    if ($this->request->getMethod() === "post") {
      $session->set('filter', $this->request->getPost('filter'));
      if ($this->request->getPost('rowsPerPage') != $session->get('rowsPerPage')) {
        $session->set('rowsPerPage', $this->request->getPost('rowsPerPage'));
      }
    }

    // Generate the pager object
    $builder = $this-> generateIndexQB($session->get('filter'), $this->request->getPost('reportTypeID'), $this->request->getPost('statusID'), $this->request->getPost('costCentreID'), user_id(), null, true, $session->get('currentSort'));
    $this->pager = new \App\Libraries\MyPager(current_url(true), $builder->getCompiledSelect(), $session->get('rowsPerPage'), $session->get('maxRows'), $page);

    // Get the publication model
    $model = new PublicationModel();

    // Populate the data going to the view
    $data = [
      'publications' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'My Publications',
      'reportTypes' => $this->getReportTypes(),
      'statuses' => $this->getStatuses(),
      'costCentres' => $this->getCostCentres(),
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('publications/myPublications.php', $data);
		echo view('templates/footer.php', $data);
  }

  /**
  * Name: assignedToMe
  * Purpose: Generates the index page
  *
  * Parameters: None
  *
  * Returns: None
  */
  public function assignedToMe() {
    // Check to see if the user is logged in
    if (logged_in() == false) {
      $_SESSION['redirect_url'] = base_url() . '/publications/assignedToMe';
      return redirect()->to(base_url() . '/login');

      if (in_groups(['pubsAdmin', 'pubsRC', 'pubsAuth', 'pubsRCMan']) == false) {
        $data = [
          'title' => 'Not Authorized',
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('errors/notAuthorized.php', $data);
        echo view('templates/footer.php', $data);
        return;
      }
    }

    // Load helpers
    helper(['form']);

    // Get the services
    $uri = service('uri');
    $session = session();

    // Process the session data
    $this->processIndexSession($session, "assignedToMe");

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);

    // Get the sort parameter
    $sort = $uri->getQuery(['only' => ['sort']]);
    if ($sort != '') {
      $sort = substr($sort, 5);
      $session->set('currentSort', $sort);
      $page = 1;
    }

    // Get the filter parameter
    $filter = $uri->getQuery(['only' => ['filter']]);
    if ($filter != '') {
      $filter = substr($filter, 7);
      $session->set('filter', $filter);
    }

    // Check for a post
    if ($this->request->getMethod() === "post") {
      $session->set('filter', $this->request->getPost('filter'));
      if ($this->request->getPost('rowsPerPage') != $session->get('rowsPerPage')) {
        $session->set('rowsPerPage', $this->request->getPost('rowsPerPage'));
      }
    }

    // Generate the pager object
    $builder = $this-> generateIndexQB($session->get('filter'), $this->request->getPost('reportTypeID'), $this->request->getPost('statusID'), $this->request->getPost('costCentreID'), null, user_id(), true, $session->get('currentSort'));
    $this->pager = new \App\Libraries\MyPager(current_url(true), $builder->getCompiledSelect(), $session->get('rowsPerPage'), $session->get('maxRows'), $page);

    // Get the publication model
    $model = new PublicationModel();

    // Populate the data going to the view
    $data = [
      'publications' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Publications Assigned To Me',
      'reportTypes' => $this->getReportTypes(),
      'statuses' => $this->getStatuses(),
      'costCentres' => $this->getCostCentres(),
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('publications/assignedToMe.php', $data);
		echo view('templates/footer.php', $data);
  }

  /**
  * Name: generateReportNumber
  * Purpose: Generates (guesses) the next report number
  *
  * Parameters:
  *   $reportTypeID - The report type for the new publication
  *
  * Returns:
  *   Autogenerated report number (string)
  */
  private function generateReportNumber(string $reportTypeID) {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Get the abbreviation
    $abbreviation = $this->getAbbreviation($reportTypeID);

    // Get the next report number
    $reportNumber = $this->getNextReportNumber();

    // Generate the report number
    $year = Date('Y');

    // Return the report number
    return 'CDEV-' . $year . '-' . $reportNumber . '-' . $abbreviation;
  }

  /**
  * Name: getAbbreviation
  * Purpose: Gets the Report Type abbreviation
  *
  * Parameters:
  *   $reportTypeID - The report type ID
  *
  * Returns:
  *   Abbreviation
  *   or 'XX'  If no abbreviation is found`1
  */
  private function getAbbreviation(string $reportTypeID)
  {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Create the query
    $builder = $db->table('ReportTypes');
    $builder->select('Abbreviation');
    $builder->where('ReportTypeID', $reportTypeID);

    // Return the result
    $result = $builder->get()->getRow();
    $abbreviation = 'XX';
    if (empty($result) == false) {
      $abbreviation = $result->Abbreviation;
    }
    return $abbreviation;
  }

  /**
  * Name: getNextReportNumber
  * Purpose: Generates the next report number based on what is in the publications list
  *
  * Parameters:
  *
  * Returns:
  *   '####' If a report number can be generated
  *   'XXXX' If no report number can be generated
  */
  private function getNextReportNumber()
  {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Create the query
    $publicationID = $this->getMaxPublicationID(Date('Y'));
    $builder = $db->table('Publications');
    $builder->select('ReportNumber');
    $builder->where('PublicationID', $publicationID);

    // Return the result
    $result = $builder->get()->getRow();
    $reportNumber = 'XXXX';
    if (empty($result) == false) {
      $tRN = substr($result->ReportNumber, 10, 4);
      if (is_numeric($tRN)) {
        $rn = intval($tRN) + 1;
        $reportNumber = sprintf("%'.04d", $rn);
      }
    }
    return $reportNumber;
  }

  /**
  * Name: getMaxPublicationID
  * Purpose: Generates the newest publication id where the report number contains
  *   'CDEV-YYYY-' and is not deleted
  *
  * Parameters:
  *   $year - The year to search for
  *
  * Returns:
  *   PublicationID
  */
  private function getMaxPublicationID(string $year)
  {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Build the query
    $builder = $db->table('Publications');
    $builder->selectMax('PublicationID');
    $builder->like('ReportNumber', 'CDEV-' . $year . '-');
    $builder->where('deleted_at', null);

    // Return the result
    $result = $builder->get()->getRow();
    if (empty($result)) {
      return null;
    }
    return $result->PublicationID;
  }
}
