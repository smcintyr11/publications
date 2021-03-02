<?php namespace App\Models;

use CodeIgniter\Model;

class ReportTypeModel extends Model {
  // Member variables
  protected $table = "ReportTypes";
  protected $primaryKey = "ReportTypeID";
  protected $allowedFields = ["ReportType", "Abbreviation"];

  // Function to get the list of Report Types
  public function getReportTypes($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $reportTypes = $this->orderBy("ReportTypeID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $reportTypes = $this->orderBy("ReportTypeID", "desc");
    } elseif ($cur_sort == "rt_asc") {
      $reportTypes = $this->orderBy("ReportType", "asc");
    } elseif ($cur_sort == "rt_desc") {
      $reportTypes = $this->orderBy("ReportType", "desc");
    } elseif ($cur_sort == "abbreviation_asc") {
      $reportTypes = $this->orderBy("Abbreviation", "asc");
    } elseif ($cur_sort == "abbreviation_desc") {
      $reportTypes = $this->orderBy("Abbreviation", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $reportTypes = $reportTypes->like('ReportType', $filter);
      $reportTypes = $reportTypes->orLike('Abbreviation', $filter);
    }

    // Return the Report Types
    return $reportTypes->paginate($rows, 'default', $page);
  }

  // Function to get a specific Report Type
  public function getReportType($reportTypeID) {
    return $this->find($reportTypeID);
  }

  // Function to delete a specific Report Type
  public function deleteReportType($reportTypeID) {
    $this->delete($reportTypeID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('ReportTypes');
    $builder->select('ReportType');
    $builder->like('ReportType', $filter);
    $builder->orLike('Abbreviation', $filter);
    return $builder->countAllResults();
  }
}
