<?php namespace App\Models;

use CodeIgniter\Model;

class LinkTypeModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "LinkTypes";
  protected $primaryKey = "LinkTypeID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","LinkType"];

  /**
   * Name: getLinkType
   * Purpose: Retrieves a specific Link Type from the database
   *
   * Parameters:
   *  int $linkTypeID - The LinkTypeID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getLinkType($linkTypeID) {
    return $this->find($linkTypeID);
  }
}
