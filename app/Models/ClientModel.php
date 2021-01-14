<?php namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = "Clients";
    protected $primaryKey = "ClientID";
    protected $allowedFields = ["Clients"];

    public function getClients($cur_sort = null, $filter = null, $rows = 25, $page = 1)
    {
        // Determine which way  we are ordering
        if ($cur_sort == "id_asc") {
            $clients = $this->orderBy("ClientID", "asc");
        } elseif ($cur_sort == "id_desc") {
            $clients = $this->orderBy("ClientID", "desc");
        } elseif ($cur_sort == "client_asc") {
            $clients = $this->orderBy("Client", "asc");
        } elseif ($cur_sort == "client_desc") {
            $clients = $this->orderBy("Client", "desc");
        }

        // Determine if we are filtering
        if ($filter != '') {
            $clients = $clients->like('Client', $filter);
        }

        // Return the clients
        return $clients->paginate($rows, 'default', $page);
    }

    public function getCount($filter)
    {
        // Get the count of the filtered rows
        $db = \Config\Database::connect();
        $builder = $db->table('Clients');
        $builder->select('Client');
        $builder->like('Client', $filter);
        return $builder->countAllResults();
    }
}
