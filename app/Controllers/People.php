<?php namespace App\Controllers;

use App\Models\PersonModel;
use CodeIgniter\Controller;

class People extends Controller {
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

    // Get the person model
    $model = new PersonModel();

    // Populate the data going to the view
    $data = [
      'people' => $model->getPeopleOnPage($cur_sort, $filter, $rows, $page),
      'pagerPeople' => $model->getPeople($cur_sort, $filter, $rows, $page),
      'pager' => $model->pager,
      'title' => 'People',
      'cur_sort' => $cur_sort,
      'page' => $page,
      'rows' => $rows,
      'filter' => $filter,
      'count' => $model->getCount($filter),
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('people/index.php', $data);
		echo view('templates/footer.php', $data);
  }

  public function new() {
    // Create a new Model
    $model = new PersonModel();

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

      // Set validation rules
      $validation->setRule('displayName', 'Display Name', 'required|max_length[128]');
      $validation->setRule('lastName', 'Last Name', 'max_length[64]');
      $validation->setRule('firstName', 'First Name', 'max_length[64]');
      $validation->setRule('organizationID', 'Organization', 'required');
      if ($validation->withRequest($this->request)->run()) {
        // Save
        $model->save([
          'DisplayName' => $this->request->getPost('displayName'),
          'LastName' => $this->request->getPost('lastName'),
          'FirstName' => $this->request->getPost('firstName'),
          'OrganizationID' => $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Person',
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
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
      $cur_sort = $uri->getSegment(3, 'id_asc');
      $rows = $uri->getSegment(4, 25);
      $page = $uri->setSilent()->getSegment(5, 1);
      $filter = $uri->setSilent()->getSegment(6, '');

      // Generate the create view
      $data = [
        'title' => 'Create New Person',
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];

      //echo view('templates/minimalHeader.php', $data);
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function delete() {
    // Get the person model
    $model = new PersonModel();

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the person
      $model->deletePerson($this->request->getPost('PersonID'));

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
      $personID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the delete view
      $data = [
        'title' => 'Delete Person',
        'person' => $model->getPerson($personID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function edit() {
    // Create a new Model
    $model = new PersonModel();

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
      $validation->setRule('displayName', 'Display Name', 'required|max_length[128]');
      $validation->setRule('lastName', 'Last Name', 'max_length[64]');
      $validation->setRule('firstName', 'First Name', 'max_length[64]');
      $validation->setRule('organizationID', 'Organization', 'required');
      if ($validation->withRequest($this->request)->run()) {  // Valid
        // Save
        $model->save([
          'PersonID' => $this->request->getPost('personID'),
          'FirstName' => $this->request->getPost('firstName'),
          'LastName' => $this->request->getPost('lastName'),
          'DisplayName' => $this->request->getPost('displayName'),
          'OrganizationID' => $this->request->getPost('organizationID'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Person',
          'person' => $model->getPerson($this->request->getPost('personID')),
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
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
      $personID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the edit view
      $data = [
        'title' => 'Edit Person',
        'person' => $model->getPerson($personID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('people/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
