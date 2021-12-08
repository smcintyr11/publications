<?php namespace App\Controllers;

use App\Models\OrganizationModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class Organizations extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the OrganizationID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the OrganizationID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Organizations');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('OrganizationID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('Organization', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("OrganizationID", "DESC");
      } elseif ($sorting == "org_asc") {
        $builder->orderBy("Organization", "ASC");
      } elseif ($sorting == "org_desc") {
        $builder->orderBy("Organization", "DESC");
      } else {
        $builder->orderBy("OrganizationID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 13) == 'Organizations') {
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
    $session->set('lastPage', 'Organizations::index');
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
      $_SESSION['redirect_url'] = base_url() . '/organizations/index';
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

    // Load the helpers
    helper(['url', 'form', 'auth']);

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

    // Get the organization model
    $model = new OrganizationModel();

    // Populate the data going to the view
    $data = [
      'organizations' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Organizations',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('organizations/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/organizations/new/1';
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
    $model = new OrganizationModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Organizations::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('organization', 'Organization', 'required|max_length[128]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'Organization' => $this->request->getPost('organization'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/organizations/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Organization',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('organizations/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Organization',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/new.php', $data);
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
      $organizationID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/organizations/delete/1/' . $organizationID;
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

    // Get the organization model
    $model = new OrganizationModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Organizations::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'OrganizationID' => $this->request->getPost('organizationID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/organizations/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $organizationID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($organizationID);

      // Generate the delete view
      $organization = $model->getOrganization($organizationID);
      $data = [
        'title' => 'Delete Organization',
        'organization' => $organization,
        'createdBy' => Users::getUser($organization['CreatedBy']),
        'modifiedBy' => Users::getUser($organization['ModifiedBy']),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/delete.php', $data);
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
      $organizationID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/organizations/edit/' . $page . '/' . $organizationID;
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
    $model = new OrganizationModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Organizations::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('organization', 'Organization', 'required|max_length[128]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'OrganizationID' => $this->request->getPost('organizationID'),
          'Organization' => $this->request->getPost('organization'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/organizations/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Organization',
          'organization' => $model->getOrganization($this->request->getPost('organizationID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('organizations/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $organizationID = $uri->getSegment(4);

      // Generate the edit view
      $organization = $model->getOrganization($organizationID);
      $data = [
        'title' => 'Edit Organization',
        'organization' => $organization,
        'createdBy' => Users::getUser($organization['CreatedBy']),
        'modifiedBy' => Users::getUser($organization['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/edit.php', $data);
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
      $organizationID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/organizations/view/1/' . $organizationID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the organization model
    $model = new OrganizationModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'Organizations::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $organizationID = $uri->getSegment(4);

    // Generate the view
    $organization = $model->getOrganization($organizationID);
    $data = [
      'title' => 'View Organization',
      'organization' => $organization,
      'createdBy' => Users::getUser($organization['CreatedBy']),
      'modifiedBy' => Users::getUser($organization['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('organizations/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new organization using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the OrganizationID of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new OrganizationModel();

    // Get the POST variables
    $userid = user_id();
    $organization = $this->request->getPost('organization');

    // Make sure the variables are valid
    if (empty($organization)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the organization already exist?
    if ($this->organizationCount($organization) > 0) {
      $organizationID = $this->getOrganizationID($organization);
      echo json_encode(array("statusCode"=>202, "organizationID"=>$organizationID));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'Organization' => $organization,
    ]);

    // Get the ID of the insert
    $organizationID = $this->getOrganizationID($organization);

    // Return the success
    echo json_encode(array("statusCode"=>200, "organizationID"=>$organizationID));
  }

  /**
   * Name: searchOrganization
   * Purpose: Uses a query variable passed to the URL to search for an organization
   *  name that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchOrganization() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('Organizations');
    $builder->where('deleted_at', null);
    $builder->like('Organization', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->OrganizationID,
      'label'=>$row->Organization,
      'value'=>$row->Organization,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the People and Publications table for records with the
   *  specified OrganizationID
   *
   * Parameters:
   *  string $organizationID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
 private function findDependentRecords(string $organizationID) {
   // Build the query for the people table
   $db = \Config\Database::connect('publications');
   $builder = $db->table('People');
   $builder->select("PersonID");
   $builder->where('deleted_at', null);
   $builder->where('OrganizationID', $organizationID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   // Build the query for the Publications table
   $builder = $db->table('Publications');
   $builder->select("PublicationID");
   $builder->where('OrganizationID', $organizationID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   return false;
 }

 /**
  * Name: searchOrganizationID
  * Purpose: Gets the OrganizationID of the specified organization from POST
  *   variables
  *
  * Parameters: None
  *
  * Returns: json encoded array with status code (200 = success, 201 = failure)
  *  and the OrganizationID
  */
 public function searchOrganizationID() {
   // Get the POST variables
   $organization = $this->request->getPost('organization');

   // See if the organization actually exists
   if ($this->organizationCount($organization) > 0) {
     // Get the organizationID
     $organizationID = $this->getOrganizationID($organization);

     // Return the success
     echo json_encode(array("statusCode"=>200, "organizationID"=>$organizationID));
     return;
   }

   // Return the failure
   echo json_encode(array("statusCode"=>201));
 }

 /**
  * Name: organizationCount
  * Purpose: Gets the number of rows with the matching organization
  *
  * Parameters:
  *   string $organization - The organization to search for
  *
  * Returns: The number of rows that match the organization
  */
 private function organizationCount(string $organization) {
   // Create the query builder object
   $db = \Config\Database::connect('publications');
   $builder = $db->table('Organizations');
   $builder->select('OrganizationID');
   $builder->where('deleted_at', null);
   $builder->where('Organization', $organization);

   // Run the query
   $results = $builder->get()->getNumRows();

   // Return the number of rows
   return $results;
 }

 /**
  * Name: getOrganizationID
  * Purpose: Gets the OrganizationID of the specified organization
  *
  * Parameters:
  *   string $organization - The organization to search for
  *
  * Returns: The OrganizationID
  */
 private function getOrganizationID(string $organization) {
   // Create the query builder object
   $db = \Config\Database::connect('publications');
   $builder = $db->table('Organizations');
   $builder->select('OrganizationID');
   $builder->where('deleted_at', null);
   $builder->where('Organization', $organization);

   // Run the query
   $results = $builder->get()->getRow();

   // Return the result
   return $results->OrganizationID;
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
   $builder = $db->table('Organizations');
   $builder->select("OrganizationID");
   $builder->where('deleted_at', null);
   $builder->where('OrganizationID !=', $id);
   $builder->where('Organization', $term);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   $unique = true;
   if ($result > 0) {
     $unique = false;
   }

   echo json_encode(array("statusCode"=>200, "unique"=>$unique));
 }
}
