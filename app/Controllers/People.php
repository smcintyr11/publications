<?php namespace App\Controllers;

use App\Models\PersonModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

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
    $db = \Config\Database::connect();
    $builder = $db->table('People');

    // Generate the builder object
    if ($detailed) {
      $builder->select("PersonID, LastName, FirstName, DisplayName, Organization");
    } else {
      $builder->select('People.PersonID');
    }
    $builder->join('Organizations', 'People.OrganizationID = Organizations.OrganizationID', 'left');

    // Are we filtering
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
    // Create a new Model
    $model = new PersonModel();

    // Load helpers
    helper(['url', 'form']);
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
      if (($validation->withRequest($this->request)->run()) && ($duplicate == false)) {
        // Save
        $model->save([
          'DisplayName' => $this->request->getPost('displayName'),
          'LastName' => $this->request->getPost('lastName'),
          'FirstName' => $this->request->getPost('firstName'),
          'OrganizationID' => $this->request->getPost('organizationID') == "" ? null : $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to("index/".$page);
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
    // Get the person model
    $model = new PersonModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'People::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the person
      $model->deletePerson($this->request->getPost('personID'));

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
       return redirect()->to("index");
    } else {  // // Not post - show delete form
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $personID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($personID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Person',
        'person' => $model->getPerson($personID),
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
    // Create a new Model
    $model = new PersonModel();

    // Load helpers
    helper(['url', 'form']);
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
      if (($validation->withRequest($this->request)->run()) && ($duplicate == false)) {   // Valid
        // Save
        $model->save([
          'PersonID' => $this->request->getPost('personID'),
          'FirstName' => $this->request->getPost('firstName'),
          'LastName' => $this->request->getPost('lastName'),
          'DisplayName' => $this->request->getPost('displayName'),
          'OrganizationID' => $this->request->getPost('organizationID') == "" ? null : $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to("index/".$page);
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
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $personID = $uri->getSegment(4);

      // Generate the edit view
      $data = [
        'title' => 'Edit Person',
        'person' => $model->getPerson($personID),
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
    $db = \Config\Database::connect();
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
   $db = \Config\Database::connect();
   $builder = $db->table('PublicationsAuthors');
   $builder->select("PublicationID");
   $builder->where('PersonID', $personID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   // Build the query for the PublicationsReviewers table
   $builder = $db->table('PublicationsReviewers');
   $builder->select("PublicationID");
   $builder->where('PersonID', $personID);

   // Get the number of rows
   $result = $builder->get()->getNumRows();
   if ($result > 0) {
     return true;
   }

   // Build the query for the PublicationsStatuses table
   $builder = $db->table('PublicationsStatuses');
   $builder->select("PublicationID");
   $builder->where('StatusPersonID', $personID);

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
    *   FirstName, DisplaName, OrganizationID
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
    $db = \Config\Database::connect();
    $builder = $db->table('People');
    $builder->select("PersonID");
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
