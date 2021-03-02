<?php namespace App\Models;

use CodeIgniter\Model;

class StatusModel extends Model {
  // Member variables
  protected $table = "Statuses";
  protected $primaryKey = "StatusID";
  protected $allowedFields = ["Status", "ExpectedDuration"];

  // Function to get the list of Statuses
  public function getStatuses($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $statuses = $this->orderBy("StatusID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $statuses = $this->orderBy("StatusID", "desc");
    } elseif ($cur_sort == "status_asc") {
      $statuses = $this->orderBy("Status", "asc");
    } elseif ($cur_sort == "status_desc") {
      $statuses = $this->orderBy("Status", "desc");
    } elseif ($cur_sort == "duration_asc") {
      $statuses = $this->orderBy("ExpectedDuration", "asc");
    } elseif ($cur_sort == "duration_desc") {
      $statuses = $this->orderBy("ExpectedDuration", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $statuses = $statuses->like('Status', $filter);
      $statuses = $statuses->orLike('ExpectedDuration', $filter);
    }

    // Return the Statuses
    return $statuses->paginate($rows, 'default', $page);
  }

  // Function to get a specific status
  public function getStatus($statusID) {
    return $this->find($statusID);
  }

  // Function to delete a specific status
  public function deleteStatus($statusID) {
    $this->delete($statusID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('Statuses');
    $builder->select('Status');
    $builder->like('Status', $filter);
    $builder->orLike('ExpectedDuration', $filter);
    return $builder->countAllResults();
  }
}
