<?php namespace App\Controllers;

use App\Models\ClientModel;
use CodeIgniter\Controller;

class Clients extends Controller
{
    public function index()
    {
        // Get the URI service
        $uri = service('uri');

        // Parse the URI
        $id_sort = $uri->getSegment(3);
        $client_sort = $uri->getSegment(4);
        $rows = $uri->getSegment(5);
        $offset = $uri->getSegment(6);

        // Check for a post
        if ($this->request->getMethod() === "post") {
            $filter = $this->request->getPost('filter');
        } else {
            $filter = $uri->getSegment(7);
        }

        // Get the client model
        $model = new ClientModel();

        //$cc = $model->getClients($id_sort, $client_sort, $filter, $rows, $offset);

        // Populate the data going to the view
        $data = [
            'clients' => $model->getClients($id_sort, $client_sort, $filter, $rows, $offset),
            'title' => 'Clients',
            'id_sort' => $id_sort,
            'client_sort' => $client_sort,
            'filter' => $filter,
            'rows' =>  $rows,
            'offset' => $offset,
        ];

        // Generate the view
        echo view('templates/header.php', $data);
		echo view('templates/menu.php', $data);
		echo view('clients/index.php', $data);
		echo view('templates/footer.php', $data);

    }
}
