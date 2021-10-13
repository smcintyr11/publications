<?php namespace App\Controllers;

use App\Models\PublicationsKeywordsModel;
use CodeIgniter\Controller;

class PublicationsKeywords extends Controller {
  /**
   * Name: add
   * Purpose: Adds a row to the PublicationsKeywords table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  the PublicationsKeywordsID, KeywordEnglish, KeywordFrench of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsKeywordsModel();

    // Get the POST variables
    $publicationID = $this->request->getPost('publicationID');
    $keywordID = $this->request->getPost('keywordID');

    // Make sure the variables are valid
    if (empty($publicationID) or empty($keywordID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the keyword already exist?
    if ($this->publicationsKeywordsCount($publicationID, $keywordID) > 0) {
      $keyword=$this->getKeyword($keywordID);
      echo json_encode(array("statusCode"=>202,
      "keywordEnglish"=>$keyword['KeywordEnglish'],
      "keywordFrench"=>$keyword['KeywordFrench'],
      ));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => user_id(),
      'PublicationID' => $publicationID,
      'KeywordID' => $keywordID,
    ]);

    // Get the ID of the insert
    $publicationsKeywordsID = $this->getMaxPublicationsKeywordsID($publicationID, $keywordID);

    // Get the keyword textual description
    $keyword=$this->getKeyword($keywordID);

    // Return the success
    echo json_encode(array("statusCode"=>200,
    "publicationsKeywordsID"=>$publicationsKeywordsID,
    "keywordEnglish"=>$keyword['KeywordEnglish'],
    "keywordFrench"=>$keyword['KeywordFrench'],
    ));
  }

  /**
   * Name: remove
   * Purpose: Remove a row from the PublicationsKeywords table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function remove() {
    // Load the helper functions
    helper(['auth']);
    
    // Create a new Model
    $model = new PublicationsKeywordsModel();

    // Get the POST variables
    $publicationsKeywordsID = $this->request->getPost('publicationsKeywordsID');

    // Make sure the variables are valid
    if (empty($publicationsKeywordsID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the delete
    $model->save([
      'DeletedBy' => user_id(),
      'deleted_at' => date("Y-m-d H:i:s"),
      'PublicationsKeywordsID' => $publicationsKeywordsID,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: getMaxPublicationsKeywordsID
   * Purpose: Finds the latest (MAX) PublicationsKeywordsID that has the matching
   *  PublicationID and KeywordID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $keywordID - The ID of the keyword
   *
   * Returns: The MAX(PublicationsKeywordsID) where the PublicationID and KeywordID match
   */
  private function getMaxPublicationsKeywordsID($publicationID, $keywordID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsKeywords');
    $builder->selectMax('PublicationsKeywordsID');
    $builder->where('deleted_at', null);
    $builder->where('PublicationID', $publicationID);
    $builder->where('KeywordID', $keywordID);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the ID
    return $results->PublicationsKeywordsID;
  }

  /**
   * Name: publicationsKeywordsCount
   * Purpose: Searches for the number of PublicationsKeywordsIDs that has the matching
   *  PublicationID and KeywordID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $keywordID - The ID of the keyword
   *
   * Returns: The number of matching rows
   */
  private function publicationsKeywordsCount($publicationID, $keywordID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsKeywords');
    $builder->select('PublicationsKeywordsID');
    $builder->where('deleted_at', null);
    $builder->where('PublicationID', $publicationID);
    $builder->where('KeywordID', $keywordID);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the whether rows exist
    return $results;
  }

  /**
   * Name: getKeyword
   * Purpose: Finds the english and french values for the keyword
   *
   * Parameters:
   *  $keywordID - The ID of the keyword
   *
   * Returns: Array with the english and french keyword
   */
   public function getKeyword($keywordID) {
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Keywords');
     $builder->select('KeywordEnglish, KeywordFrench');
     $builder->where('deleted_at', null);
     $builder->where('KeywordID', $keywordID);

     // Run the query
     $results = $builder->get()->getRow();

     // Return the ID
     return array(
       "KeywordEnglish" => $results->KeywordEnglish,
       "KeywordFrench" => $results->KeywordFrench,);
   }
}
