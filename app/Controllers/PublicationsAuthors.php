<?php namespace App\Controllers;

use App\Models\PublicationsAuthorsModel;
use CodeIgniter\Controller;

class PublicationsAuthors extends Controller {
  /**
   * Name: add
   * Purpose: Adds a row to the PublicationsAuthors table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PublicationsAuthorsID of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsAuthorsModel();

    // Get the POST variables
    $publicationID = $this->request->getPost('publicationID');
    $personID = $this->request->getPost('authorID');

    // Make sure the variables are valid
    if (empty($publicationID) or empty($personID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the author already exist?
    if ($this->publicationsAuthorsCount($publicationID, $personID) > 0) {
      echo json_encode(array("statusCode"=>202));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => user_id(),
      'PublicationID' => $publicationID,
      'PersonID' => $personID,
      'PrimaryAuthor' => 0,
    ]);

    // Get the ID of the insert
    $publicationsAuthorsID = $this->getMaxPublicationsAuthorsID($publicationID, $personID);

    // Return the success
    echo json_encode(array("statusCode"=>200, "publicationsAuthorsID"=>$publicationsAuthorsID));
  }

  /**
   * Name: remove
   * Purpose: Remove a row from the PublicationsAuthors table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function remove() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsAuthorsModel();

    // Get the POST variables
    $publicationsAuthorsID = $this->request->getPost('publicationsAuthorsID');

    // Make sure the variables are valid
    if (empty($publicationsAuthorsID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the Delete
    $model->save([
      'DeletedBy' => user_id(),
      'deleted_at' => date("Y-m-d H:i:s"),
      'PublicationsAuthorsID' => $publicationsAuthorsID,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: update
   * Purpose: Updates the PrimaryAuthor field in the PublicationsAuthors table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function update() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsAuthorsModel();

    // Get the POST variables
    $publicationsAuthorsID = $this->request->getPost('publicationsAuthorsID');
    $primaryAuthor =  $this->request->getPost('primaryAuthor');

    // Make sure the variables are valid
    if (empty($publicationsAuthorsID) or is_null($primaryAuthor)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the update
    $model->save([
      'ModifiedBy' => user_id(),
      'Modified' => date("Y-m-d H:i:s"),
      'PublicationsAuthorsID' => $publicationsAuthorsID,
      'PrimaryAuthor' => $primaryAuthor,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: getMaxPublicationsAuthorsID
   * Purpose: Finds the latest (MAX) PublicationsAuthorsID that has the matching
   *  PublicationID and PersonID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $personID - The ID of the author
   *
   * Returns: The MAX(PublicationsAuthorsID) where the PublicationID and PersonID match
   */
  private function getMaxPublicationsAuthorsID($publicationID, $personID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsAuthors');
    $builder->selectMax('PublicationsAuthorsID');
    $builder->where('PublicationID', $publicationID);
    $builder->where('PersonID', $personID);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the ID
    return $results->PublicationsAuthorsID;
  }

  /**
   * Name: publicationsAuthorsCount
   * Purpose: Searches for the number of PublicationsAuthorsIDs that has the matching
   *  PublicationID and PersonID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $personID - The ID of the author
   *
   * Returns: The number of matching rows
   */
  private function publicationsAuthorsCount($publicationID, $personID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsAuthors');
    $builder->select('PublicationsAuthorsID');
    $builder->where('deleted_at', null);
    $builder->where('PublicationID', $publicationID);
    $builder->where('PersonID', $personID);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the whether rows exist
    return $results;
  }
}
