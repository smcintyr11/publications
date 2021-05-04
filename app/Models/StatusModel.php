<?php namespace App\Models;

use CodeIgniter\Model;

class StatusModel extends Model {
  // Member variables
  protected $table = "Statuses";
  protected $primaryKey = "StatusID";
  protected $allowedFields = ["Status", "ExpectedDuration", "DefaultStatus"];

  /**
   * Name: getStatus
   * Purpose: Retrieves a specific Status from the database
   *
   * Parameters:
   *  int $statusID - The StatusID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getStatus($statusID) {
    return $this->find($statusID);
  }

  /**
   * Name: deleteStatus
   * Purpose: Deletes a specific Status from the database
   *
   * Parameters:
   *  int $statusID - The StatusID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deleteStatus($statusID) {
    $this->delete($statusID);
  }
}
