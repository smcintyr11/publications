<?php namespace App\Controllers;

use App\Models\PublicationsReviewersModel;
use CodeIgniter\Controller;

class PublicationsReviewers extends Controller {
  /**
   * Name: add
   * Purpose: Adds a row to the PublicationsReviewers table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PublicationsReviewersID of the newly inserted row
   */
  public function add() {
    // Create a new Model
    $model = new PublicationsReviewersModel();

    // Get the POST variables
    $publicationID = $this->request->getPost('publicationID');
    $personID = $this->request->getPost('reviewerID');

    // Make sure the variables are valid
    if (empty($publicationID) or empty($personID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Does the reviewer already exist?
    if ($this->publicationsReviewersCount($publicationID, $personID) > 0) {
      echo json_encode(array("statusCode"=>202));
      return;
    }

    // Do the insert
    $model->save([
      'PublicationID' => $publicationID,
      'PersonID' => $personID,
      'LeadReviewer' => 0,
    ]);

    // Get the ID of the insert
    $publicationsReviewersID = $this->getMaxPublicationsReviewersID($publicationID, $personID);

    // Return the success
    echo json_encode(array("statusCode"=>200, "publicationsReviewersID"=>$publicationsReviewersID));
  }

  /**
   * Name: remove
   * Purpose: Remove a row from the PublicationsReviewers table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function remove() {
    // Create a new Model
    $model = new PublicationsReviewersModel();

    // Get the POST variables
    $publicationsReviewersID = $this->request->getPost('publicationsReviewersID');

    // Make sure the variables are valid
    if (empty($publicationsReviewersID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the delete
    $model->delete($publicationsReviewersID);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: update
   * Purpose: Updates the LeadReviewer field in the PublicationsReviewers table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function update() {
    // Create a new Model
    $model = new PublicationsReviewersModel();

    // Get the POST variables
    $publicationsReviewersID = $this->request->getPost('publicationsReviewersID');
    $leadReviewer =  $this->request->getPost('leadReviewer');

    // Make sure the variables are valid
    if (empty($publicationsReviewersID) or is_null($leadReviewer)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the update
    $model->save([
      'PublicationsReviewersID' => $publicationsReviewersID,
      'LeadReviewer' => $leadReviewer,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: getMaxPublicationsReviewersID
   * Purpose: Finds the latest (MAX) PublicationsReviewersID that has the matching
   *  PublicationID and PersonID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $personID - The ID of the reviewer
   *
   * Returns: The MAX(PublicationsReviewersID) where the PublicationID and PersonID match
   */
  private function getMaxPublicationsReviewersID($publicationID, $personID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsReviewers');
    $builder->selectMax('PublicationsReviewersID');
    $builder->where('PublicationID', $publicationID);
    $builder->where('PersonID', $personID);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the ID
    return $results->PublicationsReviewersID;
  }

  /**
   * Name: publicationsReviewersCount
   * Purpose: Searches for the number of PublicationsReviewersID that has the matching
   *  PublicationID and PersonID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *  $personID - The ID of the reviewer
   *
   * Returns: The number of matching rows
   */
  private function publicationsReviewersCount($publicationID, $personID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsReviewers');
    $builder->select('PublicationsReviewersID');
    $builder->where('PublicationID', $publicationID);
    $builder->where('PersonID', $personID);

    // Run the query
    $results = $builder->get()->getNumRows();

    // Return the whether rows exist
    return $results;
  }
}
