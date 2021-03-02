<?php namespace App\Controllers;

use App\Models\StatusModel;
use CodeIgniter\Controller;

class Statuses extends Controller {
  public function index() {
    // Get the URI service
    $uri = service('uri');

    // Parse the URI
    $cur_sort = $uri->getSegment(3);
    $rows = $uri->getSegment(4);
    $page = $uri->setSilent()->getSegment(5, 1);
    $filter = $uri->setSilent()->getSegment(6, '');

    // Check for a post
    if ($this->request->getMethod() === "post") {
      $filter = $this->request->getPost('filter');
    }

    // Get the model
    $model = new StatusModel();

    // Populate the data going to the view
    $data = [
      'statuses' => $model->getStatuses($cur_sort, $filter, $rows, $page),
      'pager' => $model->pager,
      'title' => 'Statuses',
      'cur_sort' => $cur_sort,
      'page' => $page,
      'rows' => $rows,
      'filter' => $filter,
      'count' => $model->getCount($filter),
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('statuses/index.php', $data);
		echo view('templates/footer.php', $data);
  }

  public function new() {
    // Create a new Model
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $cur_sort = $this->request->getPost('cur_sort');
      $rows = $this->request->getPost('rows');
      $page = $this->request->getPost('page');
      $filter = $this->request->getPost('filter');

      $validation->setRule('status', 'Status', 'required|max_length[64]|is_unique[Statuses.Status,statusID,{statusID}]');
      $validation->setRule('expectedDuration', 'ExpectedDuration', 'permit_empty|integer');

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
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Status',
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
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
      $cur_sort = $uri->getSegment(3, 'id_asc');
      $rows = $uri->getSegment(4, 25);
      $page = $uri->setSilent()->getSegment(5, 1);
      $filter = $uri->setSilent()->getSegment(6, '');

      // Generate the create view
      $data = [
        'title' => 'Create New Status',
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function delete() {
    // Get the model
    $model = new StatusModel();

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->deleteStatus($this->request->getPost('StatusID'));

      // Get the view data from the form
      $cur_sort = $this->request->getPost('cur_sort');
      $rows = $this->request->getPost('rows');
      $page = $this->request->getPost('page');
      $filter = $this->request->getPost('filter');

      // Go back to index
       return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
    } else {  // // Not post - show delete form
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $statusID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the delete view
      $data = [
        'title' => 'Delete Status',
        'status' => $model->getStatus($statusID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function edit() {
    // Create a new Model
    $model = new StatusModel();

    // Load helpers
    helper(['url', 'form']);
    $validation = \Config\Services::validation();

    // Is this a post (saving)
    if ($this->request->getMethod() === 'post') {
      // Get the view data from the form
      $cur_sort = $this->request->getPost('cur_sort');
      $rows = $this->request->getPost('rows');
      $page = $this->request->getPost('page');
      $filter = $this->request->getPost('filter');

      // Validate the data
      $validation->setRule('status', 'Status', 'required|max_length[64]|is_unique[Statuses.Status,statusID,{statusID}]');
      $validation->setRule('expectedDuration', 'ExpectedDuration', 'permit_empty|integer');
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
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Status',
          'status' => $model->getStatus($this->request->getPost('statusID')),
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
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
      $statusID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the edit view
      $data = [
        'title' => 'Edit Status',
        'status' => $model->getStatus($statusID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('statuses/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
