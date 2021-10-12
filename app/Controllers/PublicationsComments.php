<?php namespace App\Controllers;

use App\Models\PublicationsCommentsModel;
use CodeIgniter\Controller;

class PublicationsComments extends Controller {
  /**
   * Name: get
   * Purpose: Returns a specific PublicationsComments row specified by the post terms
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PublicationsComments data
   */
  public function get() {
    // Get the POST variables
    $publicationsCommentsID = $this->request->getPost('publicationsCommentsID');

    // Make sure the variables are valid
    if (empty($publicationsCommentsID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Get the row
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsComments');
    $builder->where('deleted_at', null);
    $builder->where('PublicationsCommentsID', $publicationsCommentsID);

    // Run the query
    $results = $builder->get()->getNumRows();
    if ($results < 1) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    $builder = $db->table('publications.PublicationsComments');
    $builder->select('publications.PublicationsComments.Created, publications.PublicationsComments.Modified,
      u1.displayName AS CreatedByName, u2.displayName AS ModifiedByName, publications.PublicationsComments.PublicationsCommentsID,
      publications.PublicationsComments.PublicationID, publications.PublicationsComments.DateEntered,
      publications.PublicationsComments.Comment');
    $builder->join('users.users AS u1', 'publications.PublicationsComments.CreatedBy = u1.id', 'left');
    $builder->join('users.users AS u2', 'publications.PublicationsComments.ModifiedBy = u2.id', 'left');
    $builder->where('publications.PublicationsComments.deleted_at', null);
    $builder->where('publications.PublicationsComments.PublicationsCommentsID', $publicationsCommentsID);
    $results = $builder->get()->getRow();

    // Create the return array
    $result = array(
      "statusCode"=>200,
      "publicationComment"=>array(
        "CreatedBy"=>$results->CreatedByName,
        "Created"=>$results->Created,
        "ModifiedBy"=>$results->ModifiedByName,
        "Modified"=>$results->Modified,
        "PublicationsCommentsID"=>$results->PublicationsCommentsID,
        "DateEntered"=>$results->DateEntered,
        "Comment"=>$results->Comment,
      )
    );

    // Return the success
    echo json_encode($result);
  }

  /**
   * Name: add
   * Purpose: Adds a row to the PublicationsComments table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PublicationsCommentsID of the newly inserted row
   */
  public function add() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsCommentsModel();

    // Get the POST variables
    $publicationID = $this->request->getPost('publicationID');
    $comment = $this->request->getPost('comment');

    // Make sure the variables are valid
    if (empty($publicationID) or empty($comment)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the insert
    $model->save([
      'CreatedBy' => user_id(),
      'PublicationID' => $publicationID,
      'DateEntered' => date("c"),
      'Comment' => $comment,
    ]);

    // Get the ID of the insert
    $publicationsCommentsID = $this->getMaxPublicationsCommentsID($publicationID);

    // Return the success
    echo json_encode(array("statusCode"=>200, "publicationsCommentsID"=>$publicationsCommentsID));
  }

  /**
   * Name: getMaxPublicationsCommentsID
   * Purpose: Finds the latest (MAX) PublicationsCommentsID that has the matching
   *  PublicationID
   *
   * Parameters:
   *  $publicationID - The ID of the publication
   *
   * Returns: The MAX(PublicationsCommentsID) where the PublicationID matches
   */
  private function getMaxPublicationsCommentsID($publicationID) {
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsComments');
    $builder->selectMax('PublicationsCommentsID');
    $builder->where('deleted_at', null);
    $builder->where('PublicationID', $publicationID);

    // Run the query
    $results = $builder->get()->getRow();

    // Return the ID
    return $results->PublicationsCommentsID;
  }

  /**
   * Name: remove
   * Purpose: Remove a row from the PublicationsComments table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function remove() {
    // Load the helper functions
    helper(['auth']);

    // Create a new Model
    $model = new PublicationsCommentsModel();

    // Get the POST variables
    $publicationsCommentsID = $this->request->getPost('publicationsCommentsID');

    // Make sure the variables are valid
    if (empty($publicationsCommentsID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the delete
    $model->save([
      'DeletedBy' => user_id(),
      'deleted_at' => date("Y-m-d H:i:s"),
      'PublicationsCommentsID' => $publicationsCommentsID,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }
}
