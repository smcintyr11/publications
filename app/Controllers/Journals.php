<?php namespace App\Controllers;

use App\Models\JournalModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class Journals extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the JournalID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the JournalID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Journals');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('JournalID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('Journal', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("JournalID", "DESC");
      } elseif ($sorting == "jour_asc") {
        $builder->orderBy("Journal", "ASC");
      } elseif ($sorting == "jour_desc") {
        $builder->orderBy("Journal", "DESC");
      } else {
        $builder->orderBy("JournalID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 8) == 'Journals') {
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
    $session->set('lastPage', 'Journals::index');
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
      $_SESSION['redirect_url'] = base_url() . '/journals/index';
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

    // Get the journal model
    $model = new JournalModel();

    // Populate the data going to the view
    $data = [
      'journals' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Journals',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('journals/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/journals/new/1';
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
    $model = new JournalModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Journals::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('journal', 'Journal', 'required|max_length[256]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'Journal' => $this->request->getPost('journal'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/journals/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Journal',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('journals/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Journal',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('journals/new.php', $data);
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
      $journalID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/journals/delete/1/' . $journalID;
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

    // Get the journal model
    $model = new JournalModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Journals::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the journal
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'JournalID' => $this->request->getPost('journalID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/journals/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $journalID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($journalID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Journal',
        'journal' => $model->getJournal($journalID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('journals/delete.php', $data);
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
      $journalID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/journals/edit/' . $page . '/' . $journalID;
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
    $model = new JournalModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Journals::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('journal', 'Journal', 'required|max_length[256]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'JournalID' => $this->request->getPost('journalID'),
          'Journal' => $this->request->getPost('journal'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/journals/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Journal',
          'journal' => $model->getJournal($this->request->getPost('journalID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('journals/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $journalID = $uri->getSegment(4);

      // Generate the edit view
      $journal = $model->getJournal($journalID);
      $data = [
        'title' => 'Edit Journal',
        'journal' => $journal,
        'createdBy' => Users::getUser($journal['CreatedBy']),
        'modifiedBy' => Users::getUser($journal['ModifiedBy']),    
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('journals/edit.php', $data);
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
      $journalID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/journals/view/1/' . $journalID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the journal model
    $model = new JournalModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Journals::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $journalID = $uri->getSegment(4);

    // Generate the view
    $journal = $model->getJournal($journalID);
    $data = [
      'title' => 'View Journal',
      'journal' => $journal,
      'createdBy' => Users::getUser($journal['CreatedBy']),
      'modifiedBy' => Users::getUser($journal['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('journals/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new journal using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the JournalID of the newly inserted row
   */
  public function add() {
    // Load the authentication helper
    helper('auth');

    // Create a new Model
    $model = new JournalModel();

    // Get the POST variables
    $userid = user_id();
    $journal = $this->request->getPost('journal');

    // Make sure the variables are valid
    if (empty($journal)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the journal already exist?
    if ($this->journalCount($journal) > 0) {
      $journalID = $this->getJournalID($journal);
      echo json_encode(array("statusCode"=>202, "journalID"=>$journalID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'Journal' => $journal,
    ]);

    // Get the ID of the insert
    $journalID = $this->getJournalID($journal);

    // Return the success
    echo json_encode(array("statusCode"=>200, "journalID"=>$journalID));
  }

  /**
   * Name: searchJournal
   * Purpose: Uses a query variable passed to the URL to search for a Journal
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchJournal() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Journals');
    $builder->select('*');
    $builder->where('deleted_at', null);
    $builder->like('Journal', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->JournalID,
      'label'=>$row->Journal,
      'value'=>$row->Journal,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchJournalID
   * Purpose: Uses a query variable passed to the URL to search for a journal
   *  that matches the journal passed in
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchJournalID() {
    // Get the POST variables
    $journal = $this->request->getPost('journal');

    // See if the journal actually exists
    if ($this->journalCount($journal) > 0) {
      // Get the journalID
      $journalID = $this->getJournalID($journal);

      // Return the success
      echo json_encode(array("statusCode"=>200, "journalID"=>$journalID));
      return;
    }

    // Return the failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: journalCount
   * Purpose: Gets the number of rows with the matching journal
   *
   * Parameters:
   *   string $journal - The journal to search for
   *
   * Returns: The number of rows that match the journal
   */
  private function journalCount(string $journal) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Journals');
    $builder->select('JournalID');
    $builder->where('deleted_at', null);
    $builder->where('Journal', $journal);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the number of rows
    return $results;
  }

  /**
   * Name: getJournalID
   * Purpose: Gets the JournalID of the specified journal
   *
   * Parameters:
   *   string $journal - The journal to search for
   *
   * Returns: The JournalID
   */
  private function getJournalID(string $journal) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Journals');
    $builder->select('JournalID');
    $builder->where('deleted_at', null);
    $builder->where('Journal', $journal);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->JournalID;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified JournalID
   *
   * Parameters:
   *  string $journalID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $journalID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('JournalID', $journalID);

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
     $builder = $db->table('Journals');
     $builder->select("JournalID");
     $builder->where('deleted_at', null);
     $builder->where('JournalID !=', $id);
     $builder->where('Journal', $term);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }
}
