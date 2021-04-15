<?php namespace App\Controllers;

use App\Models\PublicationsKeywordsModel;
use CodeIgniter\Controller;

class PublicationsKeywords extends Controller {
  public function test() {
    // Generate the delete view
    $data = [
      'title' => 'Test',
    ];
    echo view('templates/header.php', $data);
    echo view('templates/menu.php', $data);
    echo view('publicationsKeywords/test.php', $data);
    echo view('templates/footer.php', $data);
  }


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
    // Create a new Model
    $model = new PublicationsKeywordsModel();

    // Get the POST variables
    $publicationID = $this->request->getPost('publicationID');
    $keywordID = $this->request->getPost('keywordID');

    // Make sure the variables are valid
    if (empty($publicationID) or empty($keywordID)) {
      echo json_encode(array("statusCode"=>201));
    }

    // Do the insert
    $model->save([
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
    // Create a new Model
    $model = new PublicationsKeywordsModel();

    // Get the POST variables
    $publicationsKeywordsID = $this->request->getPost('publicationsKeywordsID');

    // Make sure the variables are valid
    if (empty($publicationsKeywordsID)) {
      echo json_encode(array("statusCode"=>201));
    }

    // Do the delete
    $model->delete($publicationsKeywordsID);

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
    $db = \Config\Database::connect();
    $builder = $db->table('PublicationsKeywords');
    $builder->selectMax('PublicationsKeywordsID');
    $builder->where('PublicationID', $publicationID);
    $builder->where('KeywordID', $keywordID);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the ID
    return $results->PublicationsKeywordsID;
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
     $db = \Config\Database::connect();
     $builder = $db->table('Keywords');
     $builder->select('KeywordEnglish, KeywordFrench');
     $builder->where('KeywordID', $keywordID);

     // Run the query
     $results = $builder->get()->getRow();

     // Return the ID
     return array(
       "KeywordEnglish" => $results->KeywordEnglish,
       "KeywordFrench" => $results->KeywordFrench,);
   }
}
