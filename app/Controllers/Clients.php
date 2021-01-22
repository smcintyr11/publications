<?php namespace App\Controllers;

use App\Models\ClientModel;
use CodeIgniter\Controller;

class Clients extends Controller
{
  public function new()
  {
    // Create a new Model
    $model = new ClientModel();

    // If this is a post and valid save it and go back to index
    if ($this->request->getMethod() === 'post')
    {
      // Get the view data from the form
      $cur_sort = $this->request->getPost('cur_sort');
      $rows = $this->request->getPost('rows');
      $page = $this->request->getPost('page');
      $filter = $this->request->getPost('filter');

      if ($this->validate([
          'client' => 'required',
        ]))
        {
          // Save
          $model->save([
            'Client' => $this->request->getPost('client'),
          ]);

          // Go back to index
          return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
        }
        else {
          // Generate the create view
          $data = [
            'title' => 'Create New Client',
            'cur_sort' => $cur_sort,
            'rows' => $rows,
            'page' => $page,
            'filter' => $filter,
          ];
          echo view('templates/header.php', $data);
          echo view('templates/menu.php', $data);
          echo view('clients/new.php', $data);
          echo view('templates/footer.php', $data);
        }
    }
    else {
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $cur_sort = $uri->getSegment(3, 'id_asc');
      $rows = $uri->getSegment(4, 25);
      $page = $uri->setSilent()->getSegment(5, 1);
      $filter = $uri->setSilent()->getSegment(6, '');

      // Generate the create view
      $data = [
        'title' => 'Create New Client',
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('clients/new.php', $data);
      echo view('templates/footer.php', $data);
    }
  }

  public function index()
  {
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

    // Get the client model
    $model = new ClientModel();

    // Populate the data going to the view
    $data = [
        'clients' => $model->getClients($cur_sort, $filter, $rows, $page),
        'pager' => $model->pager,
        'title' => 'Clients',
        'cur_sort' => $cur_sort,
        'page' => $page,
        'rows' => $rows,
        'filter' => $filter,
        'count' => $model->getCount($filter),
    ];

    // Generate the view
    echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('clients/index.php', $data);
		echo view('templates/footer.php', $data);
  }

  public function delete()
  {
    // Get the client model
    $model = new ClientModel();

    // Is this a post (deleting)
    if ($this->request->getMethod() === 'post')
    {
      // Delete the clients
      $model->deleteClient($this->request->getPost('ClientID'));

      // Get the view data from the form
      $cur_sort = $this->request->getPost('cur_sort');
      $rows = $this->request->getPost('rows');
      $page = $this->request->getPost('page');
      $filter = $this->request->getPost('filter');

      // Go back to index
      return redirect()->to("index/".$cur_sort."/".$rows."/".$page."/".$filter);
    }
    else   // Not post - show delete form
    {
      // Get the URI service
      $uri = service('uri');

      // Parse the URI
      $client_id = $uri->getSegment(3);
      $cur_sort = $uri->getSegment(4);
      $rows = $uri->getSegment(5);
      $page = $uri->setSilent()->getSegment(6, 1);
      $filter = $uri->setSilent()->getSegment(7, '');

      // Generate the delete view
      $data = [
        'title' => 'Delete Client',
        'client' => $model->getClient($client_id),
        'cur_sort' => $cur_sort,
        'rows' => $rows,
        'page' => $page,
        'filter' => $filter,
      ];
      echo view('templates/header.php', $data);
      echo view('templates/menu.php', $data);
      echo view('clients/delete.php', $data);
      echo view('templates/footer.php', $data);
    }
  }
}
