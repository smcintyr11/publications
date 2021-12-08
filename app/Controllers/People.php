<?php namespace App\Controllers;

use App\Models\PersonModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class People extends Controller {
  /**
	 * Name: generateIndexQB
	 * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the personID
   *          which is useful for row counts.  Otherwise is will return all columns
	 *
	 * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the PersonID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
	 *
	 * Returns: QueryBuilder object
	 */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('People');

    // Generate the builder object
    if ($detailed) {
      $builder->select("PersonID, LastName, FirstName, DisplayName, Organization");
    } else {
      $builder->select('People.PersonID');
    }
    $builder->join('Organizations', 'People.OrganizationID = Organizations.OrganizationID', 'left');

    // Are we filtering
    $builder->where('People.deleted_at', null);
    if ($filter != '') {
      $builder->like('People.Lastname', $filter);
      $builder->orLike('People.Firstname', $filter);
      $builder->orLike('People.DisplayName', $filter);
      $builder->orLike('Organizations.Organization', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("PersonID", "DESC");
      } elseif ($sorting == "lname_asc") {
        $builder->orderBy("LastName", "ASC");
      } elseif ($sorting == "lname_desc") {
        $builder->orderBy("LastName", "DESC");
      } elseif ($sorting == "fname_asc") {
        $builder->orderBy("FirstName", "ASC");
      } elseif ($sorting == "fname_desc") {
        $builder->orderBy("FirstName", "DESC");
      } elseif ($sorting == "dname_asc") {
        $builder->orderBy("DisplayName", "ASC");
      } elseif ($sorting == "dname_desc") {
        $builder->orderBy("DisplayName", "DESC");
      } elseif ($sorting == "org_asc") {
        $builder->orderBy("Organization", "ASC");
      } elseif ($sorting == "org_desc") {
        $builder->orderBy("Organization", "DESC");
      } else {
        $builder->orderBy("PersonID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 6) == 'People') {
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
    $session->set('lastPage', 'People::index');
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
      $_SESSION['redirect_url'] = base_url() . '/people/index';
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

    // Get the person model
    $model = new PersonModel();

    // Populate the data going to the view
    $data = [
      'people' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'People',
      'page' => $page,
    ];


    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('people/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/people/new/1';
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
    $model = new PersonModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'People::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('displayName', 'Display Name', 'required|max_length[128]');
      $validation->setRule('lastName', 'Last Name', 'max_length[64]');
      $validation->setRule('firstName', 'First Name', 'max_length[64]');
      //$validation->setRule('organizationID', 'Organization', 'required');
      $duplicate = $this->isDuplicate($this->request->getPost('lastName'),
        $this->request->getPost('firstName'), $this->request->getPost('displayName'),
        $this->request->getPost('organizationID'));
      if (($validation->withRequest($this->request)->run(null, null, 'publications')) && ($duplicate == false)) {
        // Save
        $model->save([
          'CreatedBy' => user_id(),
          'DisplayName' => $this->request->getPost('displayName'),
          'LastName' => $this->request->getPost('lastName'),
          'FirstName' => $this->request->getPost('firstName'),
          'OrganizationID' => $this->request->getPost('organizationID') == "" ? null : $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/people/index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Person',
          'page' => $page,
          'duplicate' => $duplicate,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('people/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Person',
        'page' => $page,
        'duplicate' => false,
      ];

      //echo view('templates/minimalHeader.php', $data);
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/new.php', $data);
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
      $personID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/people/delete/1/' . $personID;
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

    // Get the person model
    $model = new PersonModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'People::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the person
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'PersonID' => $this->request->getPost('personID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
       return redirect()->to(base_url() . "/people/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $personID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($personID);

      // Generate the delete view
      $person = $model->getPerson($personID);
      $data = [
        'title' => 'Delete Person',
        'person' => $person,
        'createdBy' => Users::getUser($person['CreatedBy']),
        'modifiedBy' => Users::getUser($person['ModifiedBy']),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/delete.php', $data);
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
      $personID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/people/edit/' . $page . '/' . $personID;
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
    $model = new PersonModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'People::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('displayName', 'Display Name', 'required|max_length[128]');
      $validation->setRule('lastName', 'Last Name', 'max_length[64]');
      $validation->setRule('firstName', 'First Name', 'max_length[64]');
      //$validation->setRule('organizationID', 'Organization', 'required');
      $duplicate = $this->isDuplicate($this->request->getPost('lastName'),
        $this->request->getPost('firstName'), $this->request->getPost('displayName'),
        $this->request->getPost('organizationID'), $this->request->getPost('personID'));
      if (($validation->withRequest($this->request)->run(null, null, 'publications')) && ($duplicate == false)) {   // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'PersonID' => $this->request->getPost('personID'),
          'FirstName' => $this->request->getPost('firstName'),
          'LastName' => $this->request->getPost('lastName'),
          'DisplayName' => $this->request->getPost('displayName'),
          'OrganizationID' => $this->request->getPost('organizationID') == "" ? null : $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/people/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Person',
          'person' => $model->getPerson($this->request->getPost('personID')),
          'page' => $page,
          'duplicate' => $duplicate,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('people/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $personID = $uri->getSegment(4);

      // Generate the edit view
      $person = $model->getPerson($personID);
      $data = [
        'title' => 'Edit Person',
        'person' => $person,
        'createdBy' => Users::getUser($person['CreatedBy']),
        'modifiedBy' => Users::getUser($person['ModifiedBy']),
        'page' => $page,
        'duplicate' => false,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/edit.php', $data);
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
      $personID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/people/view/1/' . $personID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the person model
    $model = new PersonModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'People::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $personID = $uri->getSegment(4);

    // Generate the view
    $person = $model->getPerson($personID);
    $data = [
      'title' => 'View Person',
      'person' => $person,
      'createdBy' => Users::getUser($person['CreatedBy']),
      'modifiedBy' => Users::getUser($person['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('people/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: add
   * Purpose: Adds a new person using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PersonID andn DisplayName of the newly inserted row
   */
  public function add() {
    // Create a new Model
    $model = new PersonModel();

    // Get the POST variables
    $userid = user_id();
    $lastName = $this->request->getPost('lastName');
    $firstName = $this->request->getPost('firstName');
    $displayName = $this->request->getPost('displayName');
    $organizationID = $this->request->getPost('organizationID');

    // Make sure the variables are valid
    if (empty($displayName)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the person already exist?
    if ($this->isDuplicate($lastName, $firstName, $displayName, $organizationID)) {
      $personID = $this->getPersonID($displayName, $organizationID);

      echo json_encode(array("statusCode"=>202, "personID"=>$personID, "DisplayName"=>$displayName));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => $userid,
      'LastName' => empty($lastName) ? null : $lastName,
      'FirstName' => empty($firstName) ? null : $firstName,
      'DisplayName' => $displayName,
      'OrganizationID' => empty($organizationID) ? null : $organizationID,
    ]);

    // Get the ID of the insert
    $personID = $this->getPersonID($displayName, $organizationID);

    // Get the full display name (since it has an organization typically)
    $displayName = $this->getPersonDisplayName($personID);

    // Return the success
    echo json_encode(array("statusCode"=>200, "personID"=>$personID, "displayName"=>$displayName));
  }

  /**
   * Name: searchPerson
   * Purpose: Uses a query variable passed to the URL to search for a person
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchPerson() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect('publications');
    $builder = $db->table('People');
    $builder->join('vPeopleDropDown', 'People.PersonID = vPeopleDropDown.PersonID', 'left');
    $builder->select('People.PersonID, vPeopleDropDown.DisplayName');
    $builder->like('People.DisplayName', $searchString);
    $builder->orLike('FirstName', $searchString);
    $builder->orLike('LastName', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->PersonID,
      'label'=>$row->DisplayName,
      'value'=>$row->DisplayName,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchExactDisplayName
   * Purpose: Uses a query variable passed to the URL to search for a person
   *  that matches the search term
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchExactDisplayName() {
    // Variable declaration
    $searchString = $this->request->getVar('displayName');

    // Is there an exact match
    if ($this->exactDisplayNameCount($searchString) > 0) {
      // Create optional search string
      $searchString2 = $searchString . " (No affiliation)";

      // Build the query
      $db = \Config\Database::connect('publications');
      $builder = $db->table('vPeopleDropDown');
      $builder->select('PersonID, DisplayName');
      $builder->where('DisplayName', $searchString);
      $builder->orWhere('DisplayName', $searchString2);

      // Run the query
      $result = $builder->get()->getRow();

      // Return success
      echo json_encode(array("statusCode"=>200, "personID"=>$result->PersonID, "displayName"=>$result->DisplayName));
      return;
    }

    // Return failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: exactDisplayNameCount
   * Purpose: Finds out how many rows have a person's displayName that exactly
   * matches the search string passed in
   *
   * Parameters:
   *  string $searchString - The person to search for
   *
   * Returns: Number of matching rows
   */
  private function exactDisplayNameCount(string $searchString) {
    // Create optional search string
    $searchString2 = $searchString . " (No affiliation)";

    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('vPeopleDropDown');
    $builder->select('PersonID');
    $builder->where('DisplayName', $searchString);
    $builder->orWhere('DisplayName', $searchString2);

    // Run the query
    $result = $builder->get()->getNumRows();

    // Return the number of rows
    return $result;
  }

  /**
   * Name: getPersonID
   * Purpose: Gets the PersonID of the specified person
   *
   * Parameters:
   *   string $searchString - The person to search for
   *
   * Returns: The PersonID
   */
  private function getPersonID(string $displayName, ?string $organizationID) {
    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('People');
    $builder->select('PersonID');
    $builder->where('deleted_at', null);
    $builder->where('DisplayName', $displayName);
    $builder->where('OrganizationID', (empty($organizationID) ? null : $organizationID) );

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->PersonID;
  }

  /**
   * Name: getPersonDisplayName
   * Purpose: Gets the DisplayName of the specified PersonID
   *
   * Parameters:
   *   string $searchString - The PersonID to search for
   *
   * Returns: The DisplayName
   */
  private function getPersonDisplayName(string $searchString) {
    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('vPeopleDropDown');
    $builder->select('DisplayName');
    $builder->where('PersonID', $searchString);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the result
    return $results->DisplayName;
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the PublicationsAuthors, PublicationsReviewers, PublicationsStatuses
   *   table for records with the specified PersonID
   *
   * Parameters:
   *  string $personID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
  private function findDependentRecords(string $personID) {
   // Build the query for the PublicationsAuthors table
   $db = \Config\Database::connect('publications');
   $builder = $db->table('PublicationsAuthors');
   $builder->select("PublicationID");
   $builder->where('deleted_at', null);
   $builder->where('PersonID', $personID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   // Build the query for the PublicationsReviewers table
   $builder = $db->table('PublicationsReviewers');
   $builder->select("PublicationID");
   $builder->where('deleted_at', null);
   $builder->where('PersonID', $personID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   return false;
  }

  /**
    * Name: isDuplicate
    * Purpose: Searches for a Person with identical information for LastName,
    *   FirstName, DisplayName, OrganizationID
    *
    * Parameters:
    *  string $lastName - The last name to search for
    *  string $firstName - The first name to search for
    *  string $displayName - The display name to search for
    *  string $organizationID - The organization id to search for
    *  string $personID - The current record (if editing)
    *
    * Returns:
    *  boolean - True if dependent records exist Otherwise false
    */
  private function isDuplicate(?string $lastName, ?string $firstName, ?string $displayName, ?string $organizationID, ?string $personID = null) {
    // Build the query for the PublicationsAuthors table
    $db = \Config\Database::connect('publications');
    $builder = $db->table('People');
    $builder->select("PersonID");
    $builder->where('deleted_at', null);
    $builder->where('LastName', $lastName);
    $builder->where('FirstName', $firstName);
    $builder->where('DisplayName', $displayName);
    $builder->where('OrganizationID', $organizationID);
    if (empty($personID) == false) {
      $builder->where('PersonID !=', $personID);
    }

    // Get the number of rows
    $result = $builder->get()->getNumRows();
    if ($result > 0) {
      return true;
    }
    return false;
  }
}
