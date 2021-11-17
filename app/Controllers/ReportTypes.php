<?php namespace App\Controllers;

use App\Models\ReportTypeModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class ReportTypes extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the ReportTypeID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the ReportTypeID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('ReportTypes');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('ReportTypeID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('ReportType', $filter);
      $builder->orLike('Abbreviation', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("ReportTypeID", "DESC");
      } elseif ($sorting == "rt_asc") {
        $builder->orderBy("ReportType", "ASC");
      } elseif ($sorting == "rt_desc") {
        $builder->orderBy("ReportType", "DESC");
      } elseif ($sorting == "abbr_asc") {
        $builder->orderBy("Abbreviation", "ASC");
      } elseif ($sorting == "abbr_desc") {
        $builder->orderBy("Abbreviation", "DESC");
      } else {
        $builder->orderBy("ReportTypeID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 11) == 'ReportTypes') {
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
    $session->set('lastPage', 'ReportTypes::index');
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
      $_SESSION['redirect_url'] = base_url() . '/reportTypes/index';
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

    // Get the URI service
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

    // Get the report type model
    $model = new ReportTypeModel();

    // Populate the data going to the view
    $data = [
      'reportTypes' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Report Types',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('reportTypes/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/reportTypes/new/1';
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

    // Create a new Model
    $model = new ReportTypeModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('reportType', 'Report Type', 'required|max_length[64]');
      $validation->setRule('abbreviation', 'Abbreviation', 'required|max_length[16]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'ReportType' => $this->request->getPost('reportType'),
          'Abbreviation' => $this->request->getPost('abbreviation'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/reportTypes/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Report Type',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('reportTypes/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Report Type',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/new.php', $data);
      echo view('templates/footer.php', $data);
    }
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
      $reportTypeID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/reportTypes/delete/1/' . $reportTypeID;
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

    // Get the report type model
    $model = new ReportTypeModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the report type
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'ReportTypeID' => $this->request->getPost('reportTypeID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/reportTypes/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $reportTypeID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($reportTypeID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Report Type',
        'reportType' => $model->getReportType($reportTypeID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/delete.php', $data);
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
      $reportTypeID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/reportTypes/edit/' . $page . '/' . $reportTypeID;
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

    // Create a new Model
    $model = new ReportTypeModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('reportType', 'Report Type', 'required|max_length[64]');
      $validation->setRule('abbreviation', 'Abbreviation', 'required|max_length[16]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'ReportTypeID' => $this->request->getPost('reportTypeID'),
          'ReportType' => $this->request->getPost('reportType'),
          'Abbreviation' => $this->request->getPost('abbreviation'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/reportTypes/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Report Type',
          'reportType' => $model->getReportType($this->request->getPost('reportTypeID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('reportTypes/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $reportTypeID = $uri->getSegment(4);

      // Generate the edit view
      $reportType = $model->getReportType($reportTypeID);
      $data = [
        'title' => 'Edit Report Type',
        'reportType' => $reportType,
        'createdBy' => Users::getUser($reportType['CreatedBy']),
        'modifiedBy' => Users::getUser($reportType['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/edit.php', $data);
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
      $reportTypeID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/reportTypes/view/1/' . $reportTypeID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the report type model
    $model = new ReportTypeModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $reportTypeID = $uri->getSegment(4);

    // Generate the view
    $reportType = $model->getReportType($reportTypeID);
    $data = [
      'title' => 'View Report Type',
      'reportType' => $reportType,
      'createdBy' => Users::getUser($reportType['CreatedBy']),
      'modifiedBy' => Users::getUser($reportType['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('reportTypes/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new report type using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the ReportTypeID of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new ReportTypeModel();

    // Get the POST variables
    $userid = user_id();
    $reportType = $this->request->getPost('reportType');
    $abbreviation = $this->request->getPost('abbreviation');

    // Make sure the variables are valid
    if ((empty($reportType)) || (empty($abbreviation))) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the report type already exist?
    if ($this->reportTypeCount($reportType) > 0) {
      $reportTypeID = $this->getReportTypeID($reportType);
      echo json_encode(array("statusCode"=>202, "reportTypeID"=>$reportTypeID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'ReportType' => $reportType,
      'Abbreviation' => $abbreviation,
    ]);

    // Get the ID of the insert
    $reportTypeID = $this->getReportTypeID($reportType);

    // Return the success
    echo json_encode(array("statusCode"=>200, "reportTypeID"=>$reportTypeID));
  }

  /**
   * Name: searchReportType
   * Purpose: Uses a query variable passed to the URL to search for a report type
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchReportType() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('ReportTypes');
    $builder->where('deleted_at', null);
    $builder->like('ReportType', $searchString);
    $builder->orLike('Abbreviation', $searchString);
    $builder->select('ReportTypeID,CONCAT (ReportType, " (", Abbreviation, ")") AS DDValue');


    // Run the query and compile an array of report data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->ReportTypeID,
      'label'=>$row->DDValue,
      'value'=>$row->DDValue,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchReportTypeID
   * Purpose: Uses a query variable passed to the URL to search for a report type
   *  that matches the report type passed in
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchReportTypeID() {
    // Get the POST variables
    $reportType = $this->request->getPost('reportType');

    // See if the report type actually exists
    if ($this->reportTypeCount($reportType) > 0) {
      // Get the reportTypeID
      $reportTypeID = $this->getReportTypeID($reportType);

      // Return the success
      echo json_encode(array("statusCode"=>200, "reportTypeID"=>$reportTypeID));
      return;
    }

    // Return the failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: reportTypeCount
   * Purpose: Gets the number of rows with the matching report type
   *
   * Parameters:
   *   string $reportType - The name of the report type to search for
   *
   * Returns: The number of rows that match the report type
   */
  private function reportTypeCount(string $reportType) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('ReportTypes');
    $builder->select('ReportTypeID');
    $builder->where('deleted_at', null);
    $builder->where('ReportType', $reportType);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the number of rows
    return $results;
  }

  /**
   * Name: getReportTypeID
   * Purpose: Gets the ReportTypeID of the specified report type
   *
   * Parameters:
   *   string $reportType - The report type to search for
   *
   * Returns: The ReportTypeID
   */
  private function getReportTypeID(string $reportType) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('ReportTypes');
    $builder->select('ReportTypeID');
    $builder->where('deleted_at', null);
    $builder->where('ReportType', $reportType);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->ReportTypeID;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified ReportTypeID
   *
   * Parameters:
   *  string $reportTypeID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $reportTypeID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('ReportTypeID', $reportTypeID);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     if ($result > 0) {
       return true;
     }

     return false;
   }

   /**
    * Name: uniqueCheckRT
    * Purpose: Uses a post variable to search for unique (deleted_at = null) term
    *
    * Parameters: None
    *
    * Returns: Outputs JSON - An array of data
    */
   public function uniqueCheckRT() {
     // Get the POST variables
     $term = $this->request->getPost('term');
     $id = $this->request->getPost('id');

     // Build the query
     $db = \Config\Database::connect('publications');
     $builder = $db->table('ReportTypes');
     $builder->select("ReportTypeID");
     $builder->where('deleted_at', null);
     $builder->where('ReportTypeID !=', $id);
     $builder->where('ReportType', $term);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }

   /**
    * Name: uniqueCheckAB
    * Purpose: Uses a post variable to search for unique (deleted_at = null) term
    *
    * Parameters: None
    *
    * Returns: Outputs JSON - An array of data
    */
   public function uniqueCheckAB() {
     // Get the POST variables
     $term = $this->request->getPost('term');
     $id = $this->request->getPost('id');

     // Build the query
     $db = \Config\Database::connect('publications');
     $builder = $db->table('ReportTypes');
     $builder->select("ReportTypeID");
     $builder->where('deleted_at', null);
     $builder->where('ReportTypeID !=', $id);
     $builder->where('Abbreviation', $term);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }
}
