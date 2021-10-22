<?php namespace App\Models;

use CodeIgniter\Model;

class CostCentreModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "CostCentres";
  protected $primaryKey = "CostCentreID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","CostCentre","Description"];

  /**
   * Name: getCostCentre
   * Purpose: Retrieves a specific Cost Centre from the database
   *
   * Parameters:
   *  int $costCentreID - The CostCentreID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getCostCentre($costCentreID) {
    return $this->find($costCentreID);
  }
}
