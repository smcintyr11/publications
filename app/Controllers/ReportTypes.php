<?php namespace App\Controllers;

use App\Models\ReportTypeModel;
use App\Libraries\MyPager;
use CodeIgniter\Controller;

class ReportTypes extends Controller {
  /**
   * Name: generateIndexQB
   * Purpose: Generates a query builder object for the index page using the filter
   *          provided.
   *          If $detailed == false then the QB object will only grab the ReportTypeID
   *          which is useful for row counts.  Otherwise is will return all columns
   *
   * Parameters:
   *  string $filter - A string that will be used to filter columns
   *  bool $detailed - Should only the ReportTypeID be returned or all the columns
   *  string $sorting - A string that represents the type of sorting on the query
   *
   * Returns: QueryBuilder object
   */
  public function generateIndexQB(string $filter, bool $detailed = false, string $sorting = '') {
    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('ReportTypes');

    // Generate the builder object
    if ($detailed) {
      $builder->select("*");
    } else {
      $builder->select('ReportTypeID');
    }

    // Are we filtering
    if ($filter != '') {
      $builder->like('ReportType', $filter);
      $builder->orLike('Abbreviation', $filter);
    }

    // Are we sorting
    if ($detailed and $sorting != '') {
      if ($sorting == "id_desc") {
        $builder->orderBy("ReportTypeID", "DESC");
      } elseif ($sorting == "rt_asc") {
        $builder->orderBy("ReportType", "ASC");
      } elseif ($sorting == "rt_desc") {
        $builder->orderBy("ReportType", "DESC");
      } elseif ($sorting == "abbr_asc") {
        $builder->orderBy("Abbreviation", "ASC");
      } elseif ($sorting == "abbr_desc") {
        $builder->orderBy("Abbreviation", "DESC");
      } else {
        $builder->orderBy("ReportTypeID", "ASC");
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
    if (substr($session->get('lastPage'), 0, 11) == 'ReportTypes') {
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
    $session->set('lastPage', 'ReportTypes::index');
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

    // Get the report type model
    $model = new ReportTypeModel();

    // Populate the data going to the view
    $data = [
      'reportTypes' => $this->pager->getCurrentRows(),
      'links' => $this->pager->createLinks(),
      'title' => 'Report Types',
      'page' => $page,
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('reportTypes/index.php', $data);
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
    $model = new ReportTypeModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::new');

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Set validation rules
      $validation->setRule('reportType', 'Report Type', 'required|max_length[64]|is_unique[ReportTypes.ReportType,reportTypeID,{reportTypeID}]');
      $validation->setRule('abbreviation', 'Abbreviation', 'required|max_length[16]|is_unique[ReportTypes.Abbreviation,reportTypeID,{reportTypeID}]');
      if ($validation->withRequest($this->request)->run()) {
        // Save
        $model->save([
          'ReportType' => $this->request->getPost('reportType'),
          'Abbreviation' => $this->request->getPost('abbreviation'),
        ]);

        // Go back to index
        return redirect()->to("index/".$page);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Report Type',
          'page' => $page,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('reportTypes/new.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // HTTP GET request
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);

      // Generate the create view
      $data = [
        'title' => 'Create New Report Type',
        'page' => $page,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/new.php', $data);
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
    // Get the report type model
    $model = new ReportTypeModel();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::delete');

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the report type
      $model->deleteReportType($this->request->getPost('ReportTypeID'));

      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Go back to index
      return redirect()->to("index");
    } else {  // // Not post - show delete form
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $reportTypeID = $uri->getSegment(4);

      // Generate the delete view
      $data = [
        'title' => 'Delete Report Type',
        'reportType' => $model->getReportType($reportTypeID),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/delete.php', $data);
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
    $model = new ReportTypeModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // Set the session last page
    $session = session();
    $session->set('lastPage', 'ReportTypes::edit');

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $page = $this->request->getPost('page');

      // Validate the data
      $validation->setRule('reportType', 'Report Type', 'required|max_length[64]|is_unique[ReportTypes.ReportType,reportTypeID,{reportTypeID}]');
      $validation->setRule('abbreviation', 'Abbreviation', 'required|max_length[16]|is_unique[ReportTypes.Abbreviation,reportTypeID,{reportTypeID}]');
      if ($validation->withRequest($this->request)->run()) {  // Valid
        // Save
        $model->save([
          'ReportTypeID' => $this->request->getPost('reportTypeID'),
          'ReportType' => $this->request->getPost('reportType'),
          'Abbreviation' => $this->request->getPost('abbreviation'),
        ]);

        // Go back to index
        return redirect()->to("index/".$page);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Report Type',
          'reportType' => $model->getReportType($this->request->getPost('reportTypeID')),
          'page' => $page,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('reportTypes/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $page = $uri->setSilent()->getSegment(3, 1);
      $reportTypeID = $uri->getSegment(4);

      // Generate the edit view
      $data = [
        'title' => 'Edit Report Type',
        'reportType' => $model->getReportType($reportTypeID),
        'page' => $page,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('reportTypes/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
