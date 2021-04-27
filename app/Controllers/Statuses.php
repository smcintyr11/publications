<?php namespace App\Controllers;

use App\Models\StatusModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

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
    $db = \Config\Database::connect();
    $builder = $db->table('Statuses');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('StatusID');
    }

    // Are we filtering
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
    // Create a new Model
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('status', 'Status', 'required|max_length[64]|is_unique[Statuses.Status,statusID,{statusID}]');
      $validation->setRule('expectedDuration', 'Expected Duration', 'permit_empty|integer|greater_than_equal_to[1]');
      if ($validation->withRequest($this->request)->run()) {
        // An empty string is returned when nothing is entered, convert that to NULL
        $expectedDuration = $this->request->getPost('expectedDuration');
        if ($expectedDuration == "") {
          $expectedDuration = NULL;
        }

        // Save
        $model->save([
          'Status' => $this->request->getPost('status'),
          'ExpectedDuration' => $expectedDuration,
        ]);

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
    // Get the model
    $model = new StatusModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->deleteStatus($this->request->getPost('statusID'));

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
    // Create a new Model
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Statuses::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('status', 'Status', 'required|max_length[64]|is_unique[Statuses.Status,statusID,{statusID}]');
      $validation->setRule('expectedDuration', 'Expected Duration', 'permit_empty|integer|greater_than_equal_to[1]');
      if ($validation->withRequest($this->request)->run()) {  // Valid
        // An empty string is returned when nothing is entered, convert that to NULL
        $expectedDuration = $this->request->getPost('expectedDuration');
        if ($expectedDuration == "") {
          $expectedDuration = NULL;
        }

        // Save
        $model->save([
          'StatusID' => $this->request->getPost('statusID'),
          'Status' => $this->request->getPost('status'),
          'ExpectedDuration' => $expectedDuration,
        ]);

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
   * Purpose: Uses a query variable passed to the URL to search for an organization
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
    $db = \Config\Database::connect();
    $builder = $db->table('Statuses');
    $builder->like('Status', $searchString);

    // Run the query and compile an array of organization data
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
     $db = \Config\Database::connect();
     $builder = $db->table('PublicationsStatuses');
     $builder->select("PublicationID");
     $builder->where('StatusID', $statusID);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     if ($result > 0) {
       return true;
     }

     return false;
   }
}
