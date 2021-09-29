<?php namespace App\Models;

use CodeIgniter\Model;

class ReportTypeModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "ReportTypes";
  protected $primaryKey = "ReportTypeID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","ReportType", "Abbreviation"];

  /**
   * Name: getReportType
   * Purpose: Retrieves a specific ReportType from the database
   *
   * Parameters:
   *  int $reportTypeID - The ReportTypeID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getReportType($reportTypeID) {
    return $this->find($reportTypeID);
  }

  /**
   * Name: deleteClient
   * Purpose: Deletes a specific ReportType from the database
   *
   * Parameters:
   *  int $reportTypeID - The ReportTypeID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deleteReportType($reportTypeID) {
    $this->delete($reportTypeID);
  }
}
