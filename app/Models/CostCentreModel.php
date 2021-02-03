<?php namespace App\Models;

use CodeIgniter\Model;

class CostCentreModel extends Model {
  // Member variables
  protected $table = "CostCentres";
  protected $primaryKey = "CostCentreID";
  protected $allowedFields = ["CostCentre","Description"];

  // Function to get the list of Cost Centres
  public function getCostCentres($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $costCentres = $this->orderBy("CostCentreID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $costCentres = $this->orderBy("CostCentreID", "desc");
    } elseif ($cur_sort == "cc_asc") {
      $costCentres = $this->orderBy("CostCentre", "asc");
    } elseif ($cur_sort == "cc_desc") {
      $costCentres = $this->orderBy("CostCentre", "desc");
    } elseif ($cur_sort == "desc_asc") {
      $costCentres = $this->orderBy("Description", "asc");
    } elseif ($cur_sort == "desc_desc") {
      $costCentres = $this->orderBy("Description", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $costCentres = $costCentres->like('CostCentre', $filter);
      $costCentres = $costCentres->orLike('Description', $filter);
    }

    // Return the Cost Centres
    return $costCentres->paginate($rows, 'default', $page);
  }

  // Function to get a specific Cost Centre
  public function getCostCentre($costCentreID) {
    return $this->find($costCentreID);
  }

  // Function to delete a specific Cost Centre
  public function deleteCostCentre($costCentreID) {
    $this->delete($costCentreID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('CostCentres');
    $builder->select('CostCentre');
    $builder->like('CostCentre', $filter);
    $builder->orLike('Description', $filter);
    return $builder->countAllResults();
  }
}
