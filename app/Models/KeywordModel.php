<?php namespace App\Models;

use CodeIgniter\Model;

class KeywordModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "Keywords";
  protected $primaryKey = "KeywordID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","KeywordEnglish", "KeywordFrench"];

  /**
   * Name: getKeyword
   * Purpose: Retrieves a specific Keyword from the database
   *
   * Parameters:
   *  int $keywordID - The KeywordID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getKeyword($keywordID) {
    return $this->find($keywordID);
  }
}
