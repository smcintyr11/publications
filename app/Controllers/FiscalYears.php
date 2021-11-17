<?php namespace App\Controllers;

use App\Models\FiscalYearModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class FiscalYears extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the FiscalYearID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the FiscalYearID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('FiscalYears');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('FiscalYearID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('FiscalYear', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("FiscalYearID", "DESC");
      } elseif ($sorting == "fy_asc") {
        $builder->orderBy("FiscalYear", "ASC");
      } elseif ($sorting == "fy_desc") {
        $builder->orderBy("FiscalYear", "DESC");
      } else {
        $builder->orderBy("FiscalYearID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 11) == 'FiscalYears') {
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
    $session->set('lastPage', 'FiscalYears::index');
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
      $_SESSION['redirect_url'] = base_url() . '/fiscalYears/index';
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

    // Get the fiscal year model
    $model = new FiscalYearModel();

    // Populate the data going to the view
    $data = [
      'fiscalYears' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Fiscal Years',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('fiscalYears/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/fiscalYears/new/1';
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
    $model = new FiscalYearModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'FiscalYears::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('fiscalYear', 'Fiscal Year', 'required|regex_match[\d{4} \/ \d{4}]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'FiscalYear' => $this->request->getPost('fiscalYear'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/fiscalYears/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Fiscal Year',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('fiscalYears/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Fiscal Year',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears/new.php', $data);
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
      $fiscalYearID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/fiscalYears/delete/1/' . $fiscalYearID;
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

    // Get the fiscal year model
    $model = new FiscalYearModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'FiscalYears::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the fiscal year
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'FiscalYearID' => $this->request->getPost('fiscalYearID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
       return redirect()->to(base_url() . "/fiscalYears/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $fiscalYearID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($fiscalYearID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Fiscal Year',
        'fiscalYear' => $model->getFiscalYear($fiscalYearID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears/delete.php', $data);
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
      $fiscalYearID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/fiscalYears/edit/' . $page . '/' . $fiscalYearID;
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
    $model = new FiscalYearModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'FiscalYears::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('fiscalYear', 'Fiscal Year', 'required|max_length[11]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'FiscalYearID' => $this->request->getPost('fiscalYearID'),
          'FiscalYear' => $this->request->getPost('fiscalYear'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/fiscalYears/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Fiscal Year',
          'fiscalYear' => $model->getFiscalYear($this->request->getPost('fiscalYearID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('fiscalYears/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $fiscalYearID = $uri->getSegment(4);

      // Generate the edit view
      $fiscalYear = $model->getFiscalYear($fiscalYearID);
      $data = [
        'title' => 'Edit Fiscal Year',
        'fiscalYear' => $fiscalYear,
        'createdBy' => Users::getUser($fiscalYear['CreatedBy']),
        'modifiedBy' => Users::getUser($fiscalYear['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears/edit.php', $data);
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
      $fiscalYearID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/fiscalYears/view/1/' . $fiscalYearID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the fiscal year model
    $model = new FiscalYearModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'FiscalYears::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $fiscalYearID = $uri->getSegment(4);

    // Generate the view
    $fiscalYear = $model->getFiscalYear($fiscalYearID);
    $data = [
      'title' => 'View Fiscal Year',
      'fiscalYear' => $fiscalYear,
      'createdBy' => Users::getUser($fiscalYear['CreatedBy']),
      'modifiedBy' => Users::getUser($fiscalYear['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('fiscalYears/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new fiscal year using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the FiscalYearID of the newly inserted row
   */
  public function add() {
    // Load the authentication helper
    helper('auth');

    // Create a new Model
    $model = new FiscalYearModel();

    // Get the POST variables
    $userid = user_id();
    $fiscalYear = $this->request->getPost('fiscalYear');

    // Make sure the variables are valid
    if (empty($fiscalYear)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the fiscal year already exist?
    if ($this->fiscalYearCount($fiscalYear) > 0) {
      $fiscalYearID = $this->getFiscalYearID($fiscalYear);
      echo json_encode(array("statusCode"=>202, "fiscalYearID"=>$fiscalYearID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'FiscalYear' => $fiscalYear,
    ]);

    // Get the ID of the insert
    $fiscalYearID = $this->getFiscalYearID($fiscalYear);

    // Return the success
    echo json_encode(array("statusCode"=>200, "fiscalYearID"=>$fiscalYearID));
  }

  /**
   * Name: searchFiscalYear
   * Purpose: Uses a query variable passed to the URL to search for a fiscal year
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchFiscalYear() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('FiscalYears');
    $builder->where('deleted_at', null);
    $builder->like('FiscalYear', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->FiscalYearID,
      'label'=>$row->FiscalYear,
      'value'=>$row->FiscalYear,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchFiscalYearID
   * Purpose: Uses a query variable passed to the URL to search for a fiscal year
   *  that matches the fiscal year passed in
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchFiscalYearID() {
    // Get the POST variables
    $fiscalYear = $this->request->getPost('fiscalYear');

    // See if the fiscal year actually exists
    if ($this->fiscalYearCount($fiscalYear) > 0) {
      // Get the fiscalYearID
      $fiscalYearID = $this->getFiscalYearID($fiscalYear);

      // Return the success
      echo json_encode(array("statusCode"=>200, "fiscalYearID"=>$fiscalYearID));
      return;
    }

    // Return the failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: fiscalYearCount
   * Purpose: Gets the number of rows with the matching fiscal year
   *
   * Parameters:
   *   string $fiscalYear - The fiscal year to search for
   *
   * Returns: The number of rows that match the fiscal year
   */
  private function fiscalYearCount(string $fiscalYear) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('FiscalYears');
    $builder->select('FiscalYearID');
    $builder->where('deleted_at', null);
    $builder->where('FiscalYear', $fiscalYear);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the number of rows
    return $results;
  }

  /**
   * Name: getFiscalYearID
   * Purpose: Gets the FiscalYearID of the specified fiscal year
   *
   * Parameters:
   *   string $fiscalYear - The fiscal year to search for
   *
   * Returns: The FiscalYearID
   */
  private function getFiscalYearID(string $fiscalYear) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('FiscalYears');
    $builder->select('FiscalYearID');
    $builder->where('deleted_at', null);
    $builder->where('FiscalYear', $fiscalYear);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->FiscalYearID;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified FiscalYearID
   *
   * Parameters:
   *  string $fiscalYearID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $fiscalYearID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('FiscalYearID', $fiscalYearID);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     if ($result > 0) {
       return true;
     }

     return false;
   }

   /**
    * Name: uniqueCheck
    * Purpose: Uses a post variable to search for unique (deleted_at = null) term
    *
    * Parameters: None
    *
    * Returns: Outputs JSON - An array of data
    */
   public function uniqueCheck() {
     // Get the POST variables
     $term = $this->request->getPost('term');
     $id = $this->request->getPost('id');

     // Build the query
     $db = \Config\Database::connect('publications');
     $builder = $db->table('FiscalYears');
     $builder->select("FiscalYearID");
     $builder->where('deleted_at', null);
     $builder->where('FiscalYearID !=', $id);
     $builder->where('FiscalYear', $term);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }
}
