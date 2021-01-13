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
        $cur_sort = $uri->getSegment(3);
        $rows = $uri->getSegment(4);
        $rows = 1;
        $page = $uri->setSilent()->getSegment(5, 1);
        $filter = $uri->setSilent()->getSegment(6, '');

        // Check for a post
        if ($this->request->getMethod() === "post") {
            $filter = $this->request->getPost('filter');
        }

        // Get the client model
        $model = new ClientModel();

        //$cc = $model->getClients($id_sort, $client_sort, $filter, $rows, $offset);

        // Populate the data going to the view
        //$offset = ((int)$rows * ($page - 1));
        $data = [
            // 'clients' => $model->getClients($cur_sort, $filter, $rows),
            'clients' => $model->getClients($cur_sort, $filter, $rows, $page),
            'pager' => $model->pager,
            'title' => 'Clients',
            'cur_sort' => $cur_sort,
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
}
