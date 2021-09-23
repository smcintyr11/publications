<?php namespace App\Models;

use CodeIgniter\Model;

class FiscalYearModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "FiscalYears";
  protected $primaryKey = "FiscalYearID";
  protected $allowedFields = ["FiscalYear"];

  /**
   * Name: getFiscalYear
   * Purpose: Retrieves a specific Fiscal Year from the database
   *
   * Parameters:
   *  int $fiscalYearID - The FiscalYearID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getFiscalYear($fiscalYearID) {
    return $this->find($fiscalYearID);
  }

  /**
   * Name: deleteFiscalYear
   * Purpose: Deletes a specific Fiscal Year from the database
   *
   * Parameters:
   *  int $fiscalYearID - The FiscalYearID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deleteFiscalYear($fiscalYearID) {
    return $this->delete($fiscalYearID);
  }
}
