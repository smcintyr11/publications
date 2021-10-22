<?php namespace App\Controllers;

use App\Models\StatusModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the authentication helper
helper('auth');

class Statuses extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the StatusID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the StatusID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Statuses');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('StatusID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('Status', $filter);
      $builder->orLike('ExpectedDuration', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("StatusID", "DESC");
      } elseif ($sorting == "status_asc") {
        $builder->orderBy("Status", "ASC");
      } elseif ($sorting == "status_desc") {
        $builder->orderBy("Status", "DESC");
      } elseif ($sorting == "ed_asc") {
        $builder->orderBy("ExpectedDuration", "ASC");
      } elseif ($sorting == "ed_desc") {
        $builder->orderBy("ExpectedDuration", "DESC");
      } else {
        $builder->orderBy("StatusID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 8) == 'Statuses') {
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
    $session->set('lastPage', 'Statuses::index');
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
      return redirect()->to('/login');

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

    // Get the model
    $model = new StatusModel();

    // Populate the data going to the view
    $data = [
      'statuses' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Statuses',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('statuses/index.php', $data);
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
      return redirect()->to('/login');
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
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('status', 'Status', 'required|max_length[64]');
      $validation->setRule('expectedDuration', 'Expected Duration', 'permit_empty|integer|greater_than_equal_to[1]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // An empty string is returned when nothing is entered, convert that to NULL
        $expectedDuration = $this->request->getPost('expectedDuration');
        if ($expectedDuration == "") {
          $expectedDuration = NULL;
        }

        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'Status' => $this->request->getPost('status'),
          'ExpectedDuration' => $expectedDuration,
        ]);

        // If the make default was checked update the default status
        if ($this->request->getPost('defaultStatus') == true) {
          // Get the newly added status
          $statusID = $this->getLastStatusID($this->request->getPost('status'), $expectedDuration);

          // Change all the statuses to false
          $this->clearDefaultStatus();

          // Set the newly created status to default
          $this->setDefaultStatus($statusID);
        }

        // Go back to index
        return redirect()->to("index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Status',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('statuses/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);


      // Generate the create view
      $data = [
        'title' => 'Create New Status',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/new.php', $data);
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
    // Check to see if the user is logged in
    if (logged_in() == false) {
      return redirect()->to('/login');
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
    $model = new StatusModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'StatusID' => $this->request->getPost('statusID'),
      ]);

      // Was this the default status?
      if ($this->request->getPost('defaultStatus') == "Yes") {
        // Find the latest status id
        $lastStatusID = $this->getMaxStatusID();

        // Set the last status to the default
        if (empty($lastStatusID) == false) {
          $this->setDefaultStatus($lastStatusID);
        }
      }

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to("index");
    } else {  // // Not post - show delete form
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $statusID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($statusID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Status',
        'status' => $model->getStatus($statusID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/delete.php', $data);
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
    // Check to see if the user is logged in
    if (logged_in() == false) {
      return redirect()->to('/login');
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
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('status', 'Status', 'required|max_length[64]');
      $validation->setRule('expectedDuration', 'Expected Duration', 'permit_empty|integer|greater_than_equal_to[1]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // An empty string is returned when nothing is entered, convert that to NULL
        $expectedDuration = $this->request->getPost('expectedDuration');
        if ($expectedDuration == "") {
          $expectedDuration = NULL;
        }

        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'StatusID' => $this->request->getPost('statusID'),
          'Modified' => date("Y-m-d H:i:s"),
          'Status' => $this->request->getPost('status'),
          'ExpectedDuration' => $expectedDuration,
        ]);

        // If the make default was checked update the default status
        if ($this->request->getPost('defaultStatus') == true) {
          // Change all the statuses to false
          $this->clearDefaultStatus();

          // Set the status to default
          $this->setDefaultStatus($this->request->getPost('statusID'));
        }

        // Go back to index
        return redirect()->to("index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Status',
          'status' => $model->getStatus($this->request->getPost('statusID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('statuses/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $statusID = $uri->getSegment(4);

      // Generate the edit view
      $data = [
        'title' => 'Edit Status',
        'status' => $model->getStatus($statusID),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  /**
   * Name: searchStatus
   * Purpose: Uses a query variable passed to the URL to search for a status
   *  name that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchStatus() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Statuses');
    $builder->where('deleted_at', null);
    $builder->like('Status', $searchString);

    // Run the query and compile an array of status data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->StatusID,
      'label'=>$row->Status,
      'value'=>$row->Status,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: getExpectedDuration
   * Purpose: Uses a query variable passed to the URL to search for a status'
   *  expected duration
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function getExpectedDuration() {
    // Get the POST variables
    $statusID = $this->request->getPost('statusID');

    // Make sure the variables are valid
    if (empty($statusID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Get the row
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Statuses');
    $builder->select('ExpectedDuration');
    $builder->where('deleted_at', null);
    $builder->where('StatusID', $statusID);

    // Run the query
    $results = $builder->get()->getNumRows();
    if ($results < 1) {
      echo json_encode(array("statusCode"=>201));
      return;
    }
    $builder = $db->table('Statuses');
    $builder->select('ExpectedDuration');
    $builder->where('StatusID', $statusID);
    $results = $builder->get()->getRow();

    // Create the return array
    $result = array(
      "statusCode"=>200,
      "expectedDuration"=>$results->ExpectedDuration,
    );

    // Return the success
    echo json_encode($result);
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified StatusID
   *
   * Parameters:
   *  string $statusID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $statusID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('PublicationsStatuses');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('StatusID', $statusID);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     if ($result > 0) {
       return true;
     }

     return false;
   }

   /**
    * Name: getLastStatusID
    * Purpose: Gets the latest StatusID with the matching status, and
    *  expectedDuration
    *
    * Parameters:
    *   string $status - The primary title we are searching for
    *   string $expectedDuration - The report type id we are searching for
    *
    * Returns:
    *  The status id
    */
   private function getLastStatusID(string $status, ?string $expectedDuration) {
     // Load the query builder
     $db = \Config\Database::connect('publications');

     // Generate the query
     $builder = $db->table('Statuses');
     $builder->selectMax('StatusID');
     $builder->where('deleted_at', null);
     $builder->where('Status', $status);
     $builder->where('ExpectedDuration', $expectedDuration);

     // Return the result
     $result = $builder->get()->getRow();
     return $result->StatusID;
   }

   /**
    * Name: getLastStatusID
    * Purpose: Gets the latest StatusID
    *
    * Parameters: None
    *
    * Returns:
    *  The last status id
    */
   private function getMaxStatusID() {
     // Load the query builder
     $db = \Config\Database::connect('publications');

     // Generate the query
     $builder = $db->table('Statuses');
     $builder->where('deleted_at', null);
     $builder->selectMax('StatusID');

     // Return the result
     $result = $builder->get()->getRow();
     if (empty($result)) {
       return null;
     }
     return $result->StatusID;
   }

   /**
    * Name: clearDefaultStatus
    * Purpose: Sets the DefaultStatus column to 0 for any row in the database
    *   where it is not 0
    *
    * Parameters: None
    *
    * Returns: None
    */
   private function clearDefaultStatus() {
     // Load the query builder
     $db = \Config\Database::connect('publications');

     // Generate the query
     $builder = $db->table('Statuses');
     $builder->set('DefaultStatus', 0);
     $builder->where('DefaultStatus', 1);

     // Run the update
     $builder->update();
   }

  /**
   * Name: setDefaultStatus
   * Purpose: Sets the DefaultStatus column to 1 for the specified StatusID row
   *
   * Parameters:
   *  string $statusID - The StatusID of the Status to make the default
   *
   * Returns: None
   */
  private function setDefaultStatus(string $statusID) {
    // Load the query builder
    $db = \Config\Database::connect('publications');

    // Generate the query
    $builder = $db->table('Statuses');
    $builder->set('DefaultStatus', 1);
    $builder->where('deleted_at', null);
    $builder->where('StatusID', $statusID);

    // Run the update
    $builder->update();
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
    $builder = $db->table('Statuses');
    $builder->select("StatusID");
    $builder->where('deleted_at', null);
    $builder->where('Status', $term);
    $builder->where('StatusID !=', $id);

    // Get the number of rows
    $result = $builder->get()->getNumRows();
    $unique = true;
    if ($result > 0) {
      $unique = false;
    }

    echo json_encode(array("statusCode"=>200, "unique"=>$unique));
  }
}
