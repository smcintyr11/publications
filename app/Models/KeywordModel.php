<?php namespace App\Models;

use CodeIgniter\Model;

class KeywordModel extends Model {
  // Member variables
  protected $table = "Keywords";
  protected $primaryKey = "KeywordID";
  protected $allowedFields = ["KeywordEnglish", "KeywordFrench"];

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

  /**
   * Name: deleteKeyword
   * Purpose: Deletes a specific Keyword from the database
   *
   * Parameters:
   *  int $keywordID - The KeywordID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deleteKeyword($keywordID) {
    $this->delete($keywordID);
  }
}
