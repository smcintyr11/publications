<?php namespace App\Controllers;

use App\Models\KeywordModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class Keywords extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the KeywordID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the KeywordID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Keywords');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('KeywordID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('KeywordEnglish', $filter);
      $builder->orLike('KeywordFrench', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("KeywordID", "DESC");
      } elseif ($sorting == "keye_asc") {
        $builder->orderBy("KeywordEnglish", "ASC");
      } elseif ($sorting == "keye_desc") {
        $builder->orderBy("KeywordEnglish", "DESC");
      }  elseif ($sorting == "keyf_asc") {
        $builder->orderBy("KeywordFrench", "ASC");
      } elseif ($sorting == "keyf_desc") {
        $builder->orderBy("KeywordFrench", "DESC");
      } else {
        $builder->orderBy("KeywordID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 8) == 'Keywords') {
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
    $session->set('lastPage', 'Keywords::index');
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
      $_SESSION['redirect_url'] = base_url() . '/keywords/index';
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

    // Get the keyword model
    $model = new KeywordModel();

    // Populate the data going to the view
    $data = [
      'keywords' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Keywords',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('keywords/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/keywords/new/1';
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
    $model = new KeywordModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Keywords::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('keywordEnglish', 'Keyword English', 'required|max_length[128]|is_unique[Keywords.KeywordEnglish,keywordID,{keywordID}]');
      $validation->setRule('keywordFrench', 'Keyword French', 'required|max_length[128]|is_unique[Keywords.KeywordFrench,keywordID,{keywordID}]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'KeywordEnglish' => $this->request->getPost('keywordEnglish'),
          'KeywordFrench' => $this->request->getPost('keywordFrench'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/keywords/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Keyword',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('keywords/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Keyword',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('keywords/new.php', $data);
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
      $keywordID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/keywords/delete/1/' . $keywordID;
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

    // Get the keyword model
    $model = new KeywordModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Keywords::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the keyword
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'KeywordID' => $this->request->getPost('keywordID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/keywords/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $keywordID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($keywordID);

      // Generate the delete view
      $keyword = $model->getKeyword($keywordID);
      $data = [
        'title' => 'Delete Keyword',
        'keyword' => $keyword,
        'createdBy' => Users::getUser($keyword['CreatedBy']),
        'modifiedBy' => Users::getUser($keyword['ModifiedBy']),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('keywords/delete.php', $data);
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
      $keywordID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/keywords/edit/' . $page . '/' . $keywordID;
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
    $model = new KeywordModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Keywords::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('keywordEnglish', 'Keyword English', 'required|max_length[128]|is_unique[Keywords.KeywordEnglish,keywordID,{keywordID}]');
      $validation->setRule('keywordFrench', 'Keyword French', 'required|max_length[128]|is_unique[Keywords.KeywordFrench,keywordID,{keywordID}]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'KeywordID' => $this->request->getPost('keywordID'),
          'KeywordEnglish' => $this->request->getPost('keywordEnglish'),
          'KeywordFrench' => $this->request->getPost('keywordFrench'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/keywords/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Keyword',
          'keyword' => $model->getKeyword($this->request->getPost('keywordID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('keywords/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $keywordID = $uri->getSegment(4);

      // Generate the edit view
      $keyword = $model->getKeyword($keywordID);
      $data = [
        'title' => 'Edit Keyword',
        'keyword' => $keyword,
        'createdBy' => Users::getUser($keyword['CreatedBy']),
        'modifiedBy' => Users::getUser($keyword['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('keywords/edit.php', $data);
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
      $keywordID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/keywords/view/1/' . $keywordID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the keyword model
    $model = new KeywordModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Keywords::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $keywordID = $uri->getSegment(4);

    // Generate the view
    $keyword = $model->getKeyword($keywordID);
    $data = [
      'title' => 'View Keyword',
      'keyword' => $keyword,
      'createdBy' => Users::getUser($keyword['CreatedBy']),
      'modifiedBy' => Users::getUser($keyword['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('keywords/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new keyword using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the KeywordID of the newly inserted row
   */
  public function add() {
    // Load the authentication helper
    helper('auth');

    // Create a new Model
    $model = new KeywordModel();

    // Get the POST variables
    $userid = user_id();
    $keywordE = $this->request->getPost('keywordE');
    $keywordF = $this->request->getPost('keywordF');

    // Make sure the variables are valid
    if (empty($keywordE) || empty($keywordF)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the keyword already exist?
    if ($this->exactKeywordCount($keywordE) > 0) {
      $keywordID = $this->getKeywordID($keywordE);

      echo json_encode(array("statusCode"=>202, "keywordID"=>$keywordID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'KeywordEnglish' => $keywordE,
      'KeywordFrench' => $keywordF,
    ]);

    // Get the ID of the insert
    $keywordID = $this->getKeywordID($keywordE);

    // Return the success
    echo json_encode(array("statusCode"=>200, "keywordID"=>$keywordID));
  }

  /**
   * Name: searchKeyword
   * Purpose: Uses a query variable passed to the URL to search for a keyword
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchKeyword() {
    // Variable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Keywords');
    $builder->select('KeywordID, CONCAT(KeywordEnglish, " | ", KeywordFrench) AS Keyword');
    $builder->where('deleted_at', null);
    $builder->like('KeywordEnglish', $searchString);
    $builder->orLike('KeywordFrench', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->KeywordID,
      'label'=>$row->Keyword,
      'value'=>$row->Keyword,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchExactKeyword
   * Purpose: Uses a query variable passed to the URL to search for a keyword
   *  that matches the search term
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchExactKeyword() {
    // Variable declaration
    $searchString = $this->request->getVar('keyword');

    if ($this->exactKeywordCount($searchString) > 0) {

      // Build the query
      $db = \Config\Database::connect('publications');
      $builder = $db->table('Keywords');
      $builder->select('KeywordID, CONCAT(KeywordEnglish, " | ", KeywordFrench) AS Keyword');
      $builder->where('KeywordEnglish', $searchString);
      $builder->orWhere('KeywordFrench', $searchString);
      $builder->orWhere('CONCAT(KeywordEnglish, " | ", KeywordFrench)', $searchString);

      // Run the query and compile an array of organization data
      $result = $builder->get()->getRow();


      // Return success
      echo json_encode(array("statusCode"=>200, "keywordID"=>$result->KeywordID, "keyword"=>$result->Keyword));
      return;
    }

    // Return failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: exactKeywordCount
   * Purpose: Finds out how many rows have a keyword that exactly matches the
   *  search string passed in (exact match = "KeywordEnglish" OR "KeywordFrench" OR
   *  "KeywordEnglish | KeywordFrench")
   *
   * Parameters:
   *  string $searchString - The keyword to search for
   *
   * Returns: Number of matching rows
   */
  private function exactKeywordCount(string $searchString) {
    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Keywords');
    $builder->select('KeywordID');
    $builder->where('deleted_at', null);
    $builder->where('KeywordEnglish', $searchString);
    $builder->orWhere('KeywordFrench', $searchString);
    $builder->orWhere('CONCAT(KeywordEnglish, " | ", KeywordFrench)', $searchString);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the number of rows
    return $results;
  }

  /**
   * Name: getKeywordID
   * Purpose: Gets the KeywordID of the specified keyword
   *
   * Parameters:
   *   string $searchString - The keyword to search for
   *
   * Returns: The KeywordID
   */
  private function getKeywordID(string $searchString) {
    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Keywords');
    $builder->select('KeywordID, CONCAT(KeywordEnglish, " | ", KeywordFrench) AS Keyword, KeywordEnglish, KeywordFrench');
    $builder->where('deleted_at', null);
    $builder->where('KeywordEnglish', $searchString);
    $builder->orWhere('KeywordFrench', $searchString);
    $builder->orWhere('CONCAT(KeywordEnglish, " | ", KeywordFrench)', $searchString);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->KeywordID;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified KeywordID
   *
   * Parameters:
   *  string $keywordID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $keywordID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('PublicationsKeywords');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('KeywordID', $keywordID);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     if ($result > 0) {
       return true;
     }

     return false;
   }
}
