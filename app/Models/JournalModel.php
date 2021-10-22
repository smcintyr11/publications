<?php namespace App\Models;

use CodeIgniter\Model;

class JournalModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "Journals";
  protected $primaryKey = "JournalID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","Journal"];

  /**
   * Name: getJournal
   * Purpose: Retrieves a specific Journal from the database
   *
   * Parameters:
   *  int $journalID - The JournalID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getJournal($journalID) {
    return $this->find($journalID);
  }
}
