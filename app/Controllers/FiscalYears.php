<?php namespace App\Controllers;

use App\Models\FiscalYearModel;
use CodeIgniter\Controller;

class FiscalYears extends Controller {
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

    // Get the fiscal year model
    $model = new FiscalYearModel();

    // Populate the data going to the view
    $data = [
      'fiscalYears' => $model->getFiscalYears($cur_sort, $filter, $rows, $page),
      'pager' => $model->pager,
      'title' => 'Fiscal Years',
      'cur_sort' => $cur_sort,
      'page' => $page,
      'rows' => $rows,
      'filter' => $filter,
      'count' => $model->getCount($filter),
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('fiscalYears/index.php', $data);
		echo view('templates/footer.php', $data);
  }

  public function new() {
    // Create a new Model
    $model = new FiscalYearModel();

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

      $validation->setRule('fiscalYear', 'Fiscal Year', 'required|max_length[11]|is_unique[FiscalYears.FiscalYear,fiscalYearID,{fiscalYearID}]');

      if ($validation->withRequest($this->request)->run()) {
        // Save
        $model->save([
          'FiscalYear' => $this->request->getPost('fiscalYear'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Fiscal Year',
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
        ];

        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('fiscalYears/new.php', $data);
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
        'title' => 'Create New Fiscal Year',
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function delete() {
    // Get the fiscal year model
    $model = new FiscalYearModel();

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the fiscal year
      $model->deleteFiscalYear($this->request->getPost('FiscalYearID'));

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
      $fiscalYearID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the delete view
      $data = [
        'title' => 'Delete Fiscal Year',
        'fiscalYear' => $model->getFiscalYear($fiscalYearID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears

      /delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function edit() {
    // Create a new Model
    $model = new FiscalYearModel();

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
      $validation->setRule('fiscalYear', 'Fiscal Year', 'required|max_length[11]|is_unique[FiscalYears.FiscalYear,fiscalYearID,{fiscalYearID}]');
      if ($validation->withRequest($this->request)->run()) {  // Valid
        // Save
        $model->save([
          'FiscalYearID' => $this->request->getPost('fiscalYearID'),
          'FiscalYear' => $this->request->getPost('fiscalYear'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Fiscal Year',
          'fiscalYear' => $model->getFiscalYear($this->request->getPost('fiscalYearID')),
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('fiscalYears/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $fiscalYearID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the edit view
      $data = [
        'title' => 'Edit Fiscal Year',
        'fiscalYear' => $model->getFiscalYear($fiscalYearID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('fiscalYears/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
