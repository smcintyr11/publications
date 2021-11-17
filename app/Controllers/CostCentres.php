<?php namespace App\Controllers;

use App\Models\CostCentreModel;
use App\Libraries\Users;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

// Load the helpers
helper(['url', 'auth']);

class CostCentres extends Controller
{
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the CostCentreID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the CostCentreID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect('publications');
    $builder = $db->table('CostCentres');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('CostCentreID');
    }

    // Are we filtering
    $builder->where('deleted_at', null);
    if ($filter != '') {
      $builder->like('CostCentre', $filter);
      $builder->orLike('Description', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("CostCentreID", "DESC");
      } elseif ($sorting == "cc_asc") {
        $builder->orderBy("CostCentre", "ASC");
      } elseif ($sorting == "cc_desc") {
        $builder->orderBy("CostCentre", "DESC");
      } elseif ($sorting == "desc_asc") {
        $builder->orderBy("Description", "ASC");
      } elseif ($sorting == "desc_desc") {
        $builder->orderBy("Description", "DESC");
      } else {
        $builder->orderBy("CostCentreID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 11) == 'CostCentres') {
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
    $session->set('lastPage', 'CostCentres::index');
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
      $_SESSION['redirect_url'] = base_url() . '/costCentres/index';
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

    // Get the cost centre model
    $model = new CostCentreModel();

    // Populate the data going to the view
    $data = [
      'costCentres' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Cost Centres',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('costCentres/index.php', $data);
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
      $_SESSION['redirect_url'] = base_url() . '/costCentres/new/1';
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
    $model = new CostCentreModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'CostCentres::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('costCentre', 'Cost Centre', 'required|max_length[64]');
      $validation->setRule('description', 'Description', 'required|max_length[256]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {
          // Save
          $model->save([
            'CreatedBy' => user_id(),
            'CostCentre' => $this->request->getPost('costCentre'),
            'Description' => $this->request->getPost('description'),
          ]);

          // Go back to index
          return redirect()->to(base_url() . "/costCentres/index/".$page);
        } else {  // Invalid - Redisplay the form
          // Generate the create view
          $data = [
            'title' => 'Create New Cost Centre',
            'page' => $page,
          ];

          echo view('templates/header.php', $data);
          echo view('templates/menu.php', $data);
          echo view('costCentres/new.php', $data);
          echo view('templates/footer.php', $data);
        }

    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Cost Centre',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('costCentres/new.php', $data);
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
      $costCentreID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/costCentres/delete/1/' . $costCentreID;
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

    // Get the cost centre model
    $model = new CostCentreModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'CostCentres::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the cost centre
      $model->save([
        'DeletedBy' => user_id(),
        'deleted_at' => date("Y-m-d H:i:s"),
        'CostCentreID' => $this->request->getPost('costCentreID'),
      ]);

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to(base_url() . "/costCentres/index");
    } else {  // // Not post - show delete form
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $costCentreID = $uri->getSegment(4);

      // Look for dependent records
      $dependentRecords = $this->findDependentRecords($costCentreID);

      // Generate the delete view
      $data = [
        'title' => 'Delete Cost Centre',
        'costCentre' => $model->getCostCentre($costCentreID),
        'page' => $page,
        'dependentRecords' => $dependentRecords,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('costCentres/delete.php', $data);
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
      $costCentreID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/costCentres/edit/' . $page . '/' . $costCentreID;
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
    $model = new CostCentreModel();

    // Load helpers
    helper(['url', 'form', 'auth']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'CostCentres::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('costCentre', 'Cost Centre', 'required|max_length[64]');
      $validation->setRule('description', 'Description', 'required|max_length[256]');
      if ($validation->withRequest($this->request)->run(null, null, 'publications')) {  // Valid
        // Save
        $model->save([
          'ModifiedBy' => user_id(),
          'Modified' => date("Y-m-d H:i:s"),
          'CostCentreID' => $this->request->getPost('costCentreID'),
          'CostCentre' => $this->request->getPost('costCentre'),
          'Description' => $this->request->getPost('description'),
        ]);

        // Go back to index
        return redirect()->to(base_url() . "/costCentres/index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Cost Centre',
          'costCentre' => $model->getCostCentre($this->request->getPost('costCentreID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('costCentres/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $costCentreID = $uri->getSegment(4);

      // Generate the edit view
      $costCentre = $model->getCostCentre($costCentreID);
      $data = [
        'title' => 'Edit Cost Centre',
        'costCentre' => $costCentre,
        'createdBy' => Users::getUser($costCentre['CreatedBy']),
        'modifiedBy' => Users::getUser($costCentre['ModifiedBy']),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('costCentres/edit.php', $data);
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
      $costCentreID = $uri->getSegment(4);
      $_SESSION['redirect_url'] = base_url() . '/costCentres/view/1/' . $costCentreID;
      return redirect()->to(base_url() . '/login');
    }

    // Get the cost centre model
    $model = new CostCentreModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'CostCentres::view');

    // Parse the URI
    $page = $uri->setSilent()->getSegment(3, 1);
    $costCentreID = $uri->getSegment(4);

    // Generate the view
    $costCentre = $model->getCostCentre($costCentreID);
    $data = [
      'title' => 'View Cost Centre',
      'costCentre' => $costCentre,
      'createdBy' => Users::getUser($costCentre['CreatedBy']),
      'modifiedBy' => Users::getUser($costCentre['ModifiedBy']),
      'page' => $page,
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('costCentres/view.php', $data);
    echo view('templates/footer.php', $data);
  }

  /**
   * Name: findDependentRecords
   * Purpose: Searches the Publications table for records with the
   *  specified CostCentreID
   *
   * Parameters:
   *  string $costCentreID
   *
   * Returns:
   *  boolean - True if dependent records exist Otherwise false
   */
   private function findDependentRecords(string $costCentreID) {
     // Build the query for the Publications table
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select("PublicationID");
     $builder->where('deleted_at', null);
     $builder->where('CostCentreID', $costCentreID);

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
     $builder = $db->table('CostCentres');
     $builder->select("CostCentreID");
     $builder->where('deleted_at', null);
     $builder->where('CostCentre', $term);
     $builder->where('CostCentreID !=', $id);

     // Get the number of rows
     $result = $builder->get()->getNumRows();
     $unique = true;
     if ($result > 0) {
       $unique = false;
     }

     echo json_encode(array("statusCode"=>200, "unique"=>$unique));
   }
}
