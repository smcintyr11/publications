<?php namespace App\Controllers;

use App\Models\ClientModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class Clients extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the ClientID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the ClientID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Clients');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('ClientID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('Client', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("ClientID", "DESC");
      } elseif ($sorting == "client_asc") {
        $builder->orderBy("Client", "ASC");
      } elseif ($sorting == "client_desc") {
        $builder->orderBy("Client", "DESC");
      } else {
        $builder->orderBy("ClientID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 7) == 'Clients') {
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
    $session->set('lastPage', 'Clients::index');
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
      $_SESSION['redirect_url'] = base_url() . '/clients/index';
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

    // Get the model
    $model = new ClientModel();

    // Populate the data going to the view
    $data = [
      'clients' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Clients / Publishers',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('clients/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/clients/new/1';
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
    $model = new ClientModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Clients::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('client', 'Client / Publisher', 'required|max_length[128]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'Client' => $this->request->getPost('client'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/clients/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Client / Publisher',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('clients/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Client / Publisher',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('clients/new.php', $data);
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

    // Load the helper functions
    helper(['auth']);

    // Check to see if the user is logged in
    if (logged_in() == false) {
      $clientID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/clients/delete/1/' . $clientID;
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
    $model = new ClientModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Clients::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'ClientID' => $this->request->getPost('clientID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/clients/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $clientID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($clientID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Client / Publisher',
        'client' => $model->getClient($clientID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('clients/delete.php', $data);
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
      $clientID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/clients/edit/' . $page . '/' . $clientID;
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
    $model = new ClientModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Clients::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('client', 'Client / Publisher', 'required|max_length[128]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'ClientID' => $this->request->getPost('clientID'),
          'Client' => $this->request->getPost('client'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/clients/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Client / Publisher',
          'client' => $model->getClient($this->request->getPost('clientID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('clients/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $clientID = $uri->getSegment(4);

      // Generate the edit view
      $client = $model->getClient($clientID);
      $data = [
        'title' => 'Edit Client / Publisher',
        'client' => $clientID,
        'createdBy' => Users::getUser($client['CreatedBy']),
        'modifiedBy' => Users::getUser($client['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('clients/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  /**
   * Name: add
   * Purpose: Adds a new client using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the ClientID of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new ClientModel();

    // Get the POST variables
    $userid = user_id();
    $client = $this->request->getPost('client');

    // Make sure the variables are valid
    if (empty($client)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the client already exist?
    if ($this->clientCount($client) > 0) {
      $clientID = $this->getClientID($client);
      echo json_encode(array("statusCode"=>202, "clientID"=>$clientID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'Client' => $client,
    ]);

    // Get the ID of the insert
    $clientID = $this->getClientID($client);

    // Return the success
    echo json_encode(array("statusCode"=>200, "clientID"=>$clientID));
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

    // Load the helper functions
    helper(['auth']);

    // Check to see if the user is logged in
    if (logged_in() == false) {
      $clientID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/clients/view/1/' . $clientID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the model
    $model = new ClientModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Clients::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $clientID = $uri->getSegment(4);

    // Generate the  view
    $client = $model->getClient($clientID);
    $data = [
      'title' => 'View Client / Publisher',
      'client' => $client,
      'createdBy' => Users::getUser($client['CreatedBy']),
      'modifiedBy' => Users::getUser($client['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('clients/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: searchClient
   * Purpose: Uses a query variable passed to the URL to search for a client
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchClient() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Clients');
    $builder->select('*');
    $builder->where('deleted_at', null);
    $builder->like('Client', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->ClientID,
      'label'=>$row->Client,
      'value'=>$row->Client,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchClientID
   * Purpose: Uses a query variable passed to the URL to search for a client
   *  that matches the client passed in
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchClientID() {
    // Get the POST variables
    $client = $this->request->getPost('client');

    // See if the client actually exists
    if ($this->clientCount($client) > 0) {
      // Get the clientID
      $clientID = $this->getClientID($client);

      // Return the success
      echo json_encode(array("statusCode"=>200, "clientID"=>$clientID));
      return;
    }

    // Return the failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: clientCount
   * Purpose: Gets the number of rows with the matching client
   *
   * Parameters:
   *   string $client - The client to search for
   *
   * Returns: The number of rows that match the client
   */
  private function clientCount(string $client) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Clients');
    $builder->select('ClientID');
    $builder->where('deleted_at', null);
    $builder->where('Client', $client);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the number of rows
    return $results;
  }

  /**
   * Name: getClientID
   * Purpose: Gets the ClientID of the specified client
   *
   * Parameters:
   *   string $client - The client to search for
   *
   * Returns: The ClientID
   */
  private function getClientID(string $client) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Clients');
    $builder->select('ClientID');
    $builder->where('deleted_at', null);
    $builder->where('Client', $client);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->ClientID;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified ClientID
   *
   * Parameters:
   *  string $clientID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $clientID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('ClientID', $clientID);

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
     $builder = $db->table('Clients');
     $builder->select("ClientID");
     $builder->where('deleted_at', null);
     $builder->where('Client', $term);
     $builder->where('ClientID !=', $id);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }
}
