<?php namespace App\Controllers;

use App\Models\PublicationModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

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
   *  bool $detailed - Should only the PublicationID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
	 *
	 * Returns: QueryBuilder object
	 */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('Publications');

    // Generate the builder object
    if ($detailed) {
      $builder->select("Publications.PublicationID, CostCentre, ProjectCode, IPDNumber, CrossReferenceNumber, ReportNumber, Abbreviation, PrimaryTitle, Status, PublicationAuthors, PublicationReviewers");
    } else {
      $builder->select('Publications.PublicationID');
    }
    $builder->join('CostCentres', 'Publications.CostCentreID = CostCentres.CostCentreID', 'left');
    $builder->join('ReportTypes', 'Publications.ReportTypeID = ReportTypes.ReportTypeID', 'left');
    $builder->join('Statuses', 'Publications.StatusID = Statuses.StatusID', 'left');
    $builder->join('vPublicationAuthors', 'Publications.PublicationID = vPublicationAuthors.PublicationID', 'left');
    $builder->join('vPublicationReviewers', 'Publications.PublicationID = vPublicationReviewers.PublicationID', 'left');

    // Are we filtering
    if ($filter != '') {
      $builder->like('CostCentre', $filter);
      $builder->orLike('ProjectCode', $filter);
      $builder->orLike('IPDNumber', $filter);
      $builder->orLike('CrossReferenceNumber', $filter);
      $builder->orLike('ReportNumber', $filter);
      $builder->orLike('Abbreviation', $filter);
      $builder->orLike('PrimaryTitle', $filter);
      $builder->orLike('Status', $filter);
      $builder->orLike('PublicationAuthors', $filter);
      $builder->orLike('PublicationReviewers', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("PublicationID", "DESC");
      } elseif ($sorting == "cc_asc") {
        $builder->orderBy("CostCentre", "ASC");
      } elseif ($sorting == "cc_desc") {
        $builder->orderBy("CostCentre", "DESC");
      } elseif ($sorting == "pc_asc") {
        $builder->orderBy("ProjectCode", "ASC");
      } elseif ($sorting == "pc_desc") {
        $builder->orderBy("ProjectCode", "DESC");
      } elseif ($sorting == "ipd_asc") {
        $builder->orderBy("IPDNumber", "ASC");
      } elseif ($sorting == "ipd_desc") {
        $builder->orderBy("IPDNumber", "DESC");
      } elseif ($sorting == "xref_asc") {
        $builder->orderBy("CrossReferenceNumber", "ASC");
      } elseif ($sorting == "xref_desc") {
        $builder->orderBy("CrossReferenceNumber", "DESC");
      } elseif ($sorting == "rn_asc") {
        $builder->orderBy("ReportNumber", "ASC");
      } elseif ($sorting == "rn_desc") {
        $builder->orderBy("ReportNumber", "DESC");
      } elseif ($sorting == "abbr_asc") {
        $builder->orderBy("Abbreviation", "ASC");
      } elseif ($sorting == "abbr_desc") {
        $builder->orderBy("Abbreviation", "DESC");
      } elseif ($sorting == "pt_asc") {
        $builder->orderBy("PrimaryTitle", "ASC");
      } elseif ($sorting == "pt_desc") {
        $builder->orderBy("PrimaryTitle", "DESC");
      } elseif ($sorting == "status_asc") {
        $builder->orderBy("Status", "ASC");
      } elseif ($sorting == "status_desc") {
        $builder->orderBy("Status", "DESC");
      } elseif ($sorting == "pa_asc") {
        $builder->orderBy("PublicationAuthors", "ASC");
      } elseif ($sorting == "pa_desc") {
        $builder->orderBy("PublicationAuthors", "DESC");
      } elseif ($sorting == "pr_asc") {
        $builder->orderBy("PublicationReviewers", "ASC");
      } elseif ($sorting == "pr_desc") {
        $builder->orderBy("PublicationReviewers", "DESC");
      } else {
        $builder->orderBy("PublicationID", "ASC");
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
   *
   * Returns: int - The number of rows
   */
  public function getMaxRows(string $filter = '') {
    // Get the maximum number of rows
    return $this->generateIndexQB($filter)->get()->getNumRows();
  }

  /**
	 * Name: processIndexSession
	 * Purpose: Processes the session data populating any mission session settings.
	 *
	 * Parameters:
   *  session $session - Session object
	 *
	 * Returns: None
	 */
  public function processIndexSession($session) {
    // Setup rows per page if it doesn't exist
    if ($session->has('rowsPerPage') == false) {
      $session->set('rowsPerPage', 25);
    }

    // Are we coming from a People page
    if (substr($session->get('lastPage'), 0, 12) == 'Publications') {
      // Current sort
      if ($session->has('currentSort') == false) {
        $session->set('currentSort', 'id_asc');
      }
      // Filter
      if ($session->has('filter') == false) {
        $session->set('filter', '');
      }
      // Max rows
      if ($session->has('maxRows') == false) {
          $session->set('maxRows', $this->getMaxRows($session->get('filter')));
      }
    } else {    // Not from index - setup variables
      // Setup the filter and max rows
      $session->set('maxRows', $this->getMaxRows(''));
      $session->set('filter', '');
      $session->set('currentSort', 'id_asc');
    }

    // Last Page
    $session->set('lastPage', 'Publications::index');
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
     // Get the services
     $uri = service('uri');
     $session = session();

     // Process the session data
     $this->processIndexSession($session);

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
     $builder = $this-> generateIndexQB($session->get('filter'), true, $session->get('currentSort'));
     $this->pager = new \App\Libraries\MyPager(current_url(true), $builder->getCompiledSelect(), $session->get('rowsPerPage'), $session->get('maxRows'), $page);

     // Get the publication model
     $model = new PublicationModel();

     // Populate the data going to the view
     $data = [
       'publications' => $this->pager->getCurrentRows(),
       'links' => $this->pager->createLinks(),
       'title' => 'Publications',
       'page' => $page,
     ];


     // Generate the view
     echo view('templates/header.php', $data);
 		echo view('templates/menu.php', $data);
 		echo view('publications/index.php', $data);
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
      // Create a new Model
      $model = new PublicationModel();

      // Load the lookup tables
      $statuses = $this->getStatuses();
      $reportTypes = $this->getReportTypes();

      // Load helpers
      helper(['url', 'form']);
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
        $validation->setRule('reportTypeID', 'Report Type', 'required');
        $validation->setRule('statusID', 'Status', 'required');
        if ($validation->withRequest($this->request)->run()) {
          // Save
          $model->save([
            'PrimaryTitle' => $this->request->getPost('primaryTitle'),
            'ReportTypeID' => $this->request->getPost('reportTypeID'),
            'StatusID' => $this->request->getPost('statusID'),
          ]);

          // Go back to index
          return redirect()->to("index/".$page);
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
     // Create a new Model
     $model = new PublicationModel();

     // Load helpers
     helper(['url', 'form']);
     $validation = \Config\Services::validation();

     // Load the lookup tables
     $statuses = $this->getStatuses();
     $reportTypes = $this->getReportTypes();
     $costCentres = $this->getCostCentres();

     // Set the session last page
     $session = session();
     $session->set('lastPage', 'Publications::edit');

     // Is this a post (saving)
     if ($this->request->getMethod() === 'post') {
       // Get the view data from the form
       $page = $this->request->getPost('page');

       // Validate the data
       $validation->setRule('primaryTitle', 'Primary Title', 'required');
       $validation->setRule('reportTypeID', 'Report Type', 'required');
       $validation->setRule('statusID', 'Status', 'required');
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
       if (!empty($this->request->getPost('statusEstimatedCompletionDate'))) {
         $validation->setRule('statusEstimatedCompletionDate ', 'Status Estimated Completion Date ', 'valid_date');
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
       if ($validation->withRequest($this->request)->run()) {  // Valid
         // Save
         $model->save([
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
           'StatusID' => $this->request->getPost('statusID'), // **
           'StatusPersonID' => $this->request->getPost('statusPersonID') == "" ? null : $this->request->getPost('statusPersonID'),
           'StatusEstimatedCompletionDate' => $this->request->getPost('statusEstimatedCompletionDate') == "" ? null : $this->request->getPost('statusEstimatedCompletionDate'),
           'DOI' => $this->request->getPost('doi') == "" ? null : $this->request->getPost('doi'),
           'JournalSubmissionDate' => $this->request->getPost('journalSubmissionDate') == "" ? null : $this->request->getPost('journalSubmissionDate'),
           'JournalAcceptanceDate' => $this->request->getPost('journalAcceptanceDate') == "" ? null : $this->request->getPost('journalAcceptanceDate'),
           'ConferenceSubmissionDate' => $this->request->getPost('conferenceSubmissionDate') == "" ? null : $this->request->getPost('conferenceSubmissionDate'),
           'ConferenceAcceptanceDate' => $this->request->getPost('conferenceAcceptanceDate') == "" ? null : $this->request->getPost('conferenceAcceptanceDate'),
           'EmbargoPeriod' => $this->request->getPost('embargoPeriod') == "" ? null : $this->request->getPost('embargoPeriod'),
           'EmbargoEndDate' => $this->request->getPost('embargoEndDate') == "" ? null : $this->request->getPost('embargoEndDate'),
           'WebPublicationDate' => $this->request->getPost('webPublicationDate') == "" ? null : $this->request->getPost('webPublicationDate'),
           'SentToClient' => $this->request->getPost('sentToClient') == "" ? null : $this->request->getPost('sentToClient'),
           'SentToClientDate' => $this->request->getPost('sentToClientDate') == "" ? null : $this->request->getPost('sentToClientDate'),
           'ReportFormatted' => $this->request->getPost('reportFormatted') == "" ? null : $this->request->getPost('reportFormatted'),
           'RecordNumber' => $this->request->getPost('recordNumber') == "" ? null : $this->request->getPost('recordNumber'),
         ]);

         // Go back to index
         return redirect()->to("index/".$page);
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
           'linksList' => $this->getLinks($publicationID),
           'commentsList'=> $this->getComments($publicationID),
         ];
         echo view('templates/header.php', $data);
         echo view('templates/menu.php', $data);
         echo view('publications/edit.php', $data);
         echo view('templates/footer.php', $data);
       }
     } else {  // Load edit page
       // Get the URI service
       $uri = service('uri');

       // Parse the URI
       $page = $uri->setSilent()->getSegment(3, 1);
       $publicationID = $uri->getSegment(4);

       // Generate the edit view
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
         'linksList' => $this->getLinks($publicationID),
         'commentsList'=> $this->getComments($publicationID),
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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('Statuses');
     $builder->select("*");
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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('ReportTypes');
     $builder->select("*");
     $builder->orderBy("ReportType");

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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('CostCentres');
     $builder->select("CostCentreID, CONCAT(CostCentre, ' (', Description, ')') AS CostCentre");
     $builder->orderBy("CostCentre");

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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('PublicationsStatuses');
     $builder->select("PublicationsStatusesID, DateModified, Status, DisplayName, EstimatedCompletionDate, CompletionDate");
     $builder->join('Statuses', 'PublicationsStatuses.StatusID = Statuses.StatusID', 'left');
     $builder->join('vPeopleDropDown', 'PublicationsStatuses.StatusPersonID = vPeopleDropDown.PersonID', 'left');
     $builder->where('PublicationID', $publicationID);
     $builder->orderBy("DateModified", "DESC");

     // Return the result
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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('PublicationsAuthors');
     $builder->select("PublicationsAuthorsID, DisplayName, PrimaryAuthor");
     $builder->join("vPeopleDropDown", 'PublicationsAuthors.PersonID = vPeopleDropDown.PersonID', 'left');
     $builder->where('PublicationID', $publicationID);
     $builder->orderBy("PrimaryAuthor", "DESC");
     $builder->orderBy("vPeopleDropDown.DisplayName");

     // Retturn the result
     return $builder->get()->getResult();
   }

   /**
    * Name: getAuthors
    * Purpose: Get a list of all items in the PublicationsReviewers table related to this publication
    *
    * Parameters:
    *   string $publicationID - The PublicationID we are filtering against
    *
    * Returns: Array of objects representing the rows
    */
   private function getReviewers(string $publicationID) {
     // Load the query builder
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('PublicationsReviewers');
     $builder->select("PublicationsReviewersID, DisplayName, LeadReviewer");
     $builder->join("vPeopleDropDown", 'PublicationsReviewers.PersonID = vPeopleDropDown.PersonID', 'left');
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
     $db = \Config\Database::connect();

     // Generate the query
     $builder = $db->table('PublicationsKeywords');
     $builder->select("PublicationsKeywordsID, KeywordEnglish, KeywordFrench");
     $builder->join("Keywords", 'PublicationsKeywords.KeywordID = Keywords.KeywordID', 'left');
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
     return "";
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
     return "";
   }
}
