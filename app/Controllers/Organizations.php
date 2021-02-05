<?php namespace App\Controllers;

use App\Models\OrganizationModel;
use CodeIgniter\Controller;

class Organizations extends Controller {
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

    // Get the organization model
    $model = new OrganizationModel();

    // Populate the data going to the view
    $data = [
      'organizations' => $model->getOrganizations($cur_sort, $filter, $rows, $page),
      'pager' => $model->pager,
      'title' => 'Organizations',
      'cur_sort' => $cur_sort,
      'page' => $page,
      'rows' => $rows,
      'filter' => $filter,
      'count' => $model->getCount($filter),
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('organizations/index.php', $data);
		echo view('templates/footer.php', $data);
  }

  public function new() {
    // Create a new Model
    $model = new OrganizationModel();

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

      $validation->setRule('organization', 'Organization', 'required|max_length[128]|is_unique[Organizations.Organization,organizationID,{organizationID}]');

      if ($validation->withRequest($this->request)->run()) {
        // Save
        $model->save([
          'Organization' => $this->request->getPost('organization'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else {  // Invalid - Redisplay the form
        // Generate the create view
        $data = [
          'title' => 'Create New Organization',
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
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
      $cur_sort = $uri->getSegment(3, 'id_asc');
      $rows = $uri->getSegment(4, 25);
      $page = $uri->setSilent()->getSegment(5, 1);
      $filter = $uri->setSilent()->getSegment(6, '');

      // Generate the create view
      $data = [
        'title' => 'Create New Organization',
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];

      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function delete() {
    // Get the organization model
    $model = new OrganizationModel();

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post') {
      // Delete the client
      $model->deleteOrganization($this->request->getPost('OrganizationID'));

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
      $organizationID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the delete view
      $data = [
        'title' => 'Delete Organization',
        'organization' => $model->getOrganization($organizationID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function edit() {
    // Create a new Model
    $model = new OrganizationModel();

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
      $validation->setRule('organization', 'Organization', 'required|max_length[128]|is_unique[Organizations.Organization,organizationID,{organizationID}]');
      if ($validation->withRequest($this->request)->run()) {  // Valid
        // Save
        $model->save([
          'OrganizationID' => $this->request->getPost('organizationID'),
          'Organization' => $this->request->getPost('organization'),
        ]);

        // Go back to index
        return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
      } else  {  // Invalid - Redisplay the form
        // Generate the view
        $data = [
          'title' => 'Edit Organization',
          'organization' => $model->getOrganization($this->request->getPost('organizationID')),
          'cur_sort' => $cur_sort,
          'rows' => $rows,
          'page' => $page,
          'filter' => $filter,
        ];
        echo view('templates/header.php', $data);
        echo view('templates/menu.php', $data);
        echo view('organizations/edit.php', $data);
        echo view('templates/footer.php', $data);
      }
    } else {  // Load edit page
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $organizationID = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the edit view
      $data = [
        'title' => 'Edit Organization',
        'organization' => $model->getOrganization($organizationID),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('organizations/edit.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
