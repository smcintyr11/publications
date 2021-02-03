<?php namespace App\Models;

use CodeIgniter\Model;

class FiscalYearModel extends Model {
  // Member variables
  protected $table = "FiscalYears";
  protected $primaryKey = "FiscalYearID";
  protected $allowedFields = ["FiscalYear"];

  // Function to get the list of Fiscal Years
  public function getFiscalYears($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $fiscalYears = $this->orderBy("FiscalYearID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $fiscalYears = $this->orderBy("FiscalYearID", "desc");
    } elseif ($cur_sort == "fy_asc") {
      $fiscalYears = $this->orderBy("FiscalYear", "asc");
    } elseif ($cur_sort == "fy_desc") {
      $fiscalYears = $this->orderBy("FiscalYear", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $fiscalYears = $fiscalYears->like('FiscalYear', $filter);
    }

    // Return the Fiscal Years
    return $fiscalYears->paginate($rows, 'default', $page);
  }

  // Function to get a specific Fiscal Year
  public function getFiscalYear($fiscalYearID) {
    return $this->find($fiscalYearID);
  }

  // Function to delete a specific Fiscal Year
  public function deleteFiscalYear($fiscalYearID) {
    return $this->delete($fiscalYearID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('FiscalYears');
    $builder->select('FiscalYear');
    $builder->like('FiscalYear', $filter);
    return $builder->countAllResults();
  }
}
