<?php namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = "Clients";
    protected $primaryKey = "ClientID";
    protected $allowedFields = ["Clients"];

    public function getClients($id_sort = null, $client_sort = null, $filter = null, $limit = null, $offset = null)
    {
        // Determine which way  we are ordering
        if ($id_sort == "id_asc") {
            $clients = $this->orderBy("ClientID", "asc");
        }
        elseif ($id_sort == "id_desc") {
            $clients = $this->orderBy("ClientID", "desc");
        }

        if ($client_sort == "client_asc") {
            $clients = $this->orderBy("Client", "asc");
        }
        elseif ($client_sort == "client_desc") {
            $clients = $this->orderBy("Client", "desc");
        }

        // Determine if we are filtering
        if (!is_null($filter) && $filter != '') {
            $clients = $clients->like('Client', $filter);
        }

        // Determine if we are paging
        if (is_null($limit)) {
            $clients = $clients->findAll();
        }
        else {
            if (is_null($offset)) {
                $clients = $clients->findAll($limit);
            }
            else {
                $clients = $clients->findAll($limit, $offset);
            }
        }

        // Return the clients
        return $clients;
    }
}
