<?php namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "Organizations";
  protected $primaryKey = "OrganizationID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","Organization"];

  /**
   * Name: getOrganization
   * Purpose: Retrieves a specific Organization from the database
   *
   * Parameters:
   *  int $organizationID - The OrganizationID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getOrganization($organizationID) {
    return $this->find($organizationID);
  }
}
