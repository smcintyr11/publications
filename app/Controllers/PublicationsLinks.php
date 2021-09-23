<?php namespace App\Controllers;

use App\Models\PublicationsLinksModel;
use CodeIgniter\Controller;

class PublicationsLinks extends Controller {
  /**
   * Name: get
   * Purpose: Returns a specific PublicationsLinks row specified by the post terms
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the PublicationsLinks data
   */
  public function get() {
    // Get the POST variables
    $publicationsLinksID = $this->request->getPost('publicationsLinksID');

    // Make sure the variables are valid
    if (empty($publicationsLinksID)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Get the row
    // Create the query builder object
    $db = \Config\Database::connect('publications');
    $builder = $db->table('PublicationsLinks');
    $builder->select('*');
    $builder->where('PublicationsLinksID', $publicationsLinksID);

    // Run the query
    $results = $builder->get()->getNumRows();
    if ($results < 1) {
      echo json_encode(array("statusCode"=>201));
      return;
    }
    $builder = $db->table('PublicationsLinks');
    $builder->select('*');
    $builder->where('PublicationsLinksID', $publicationsLinksID);
    $results = $builder->get()->getRow();

    // Create the return array
    $result = array(
      "statusCode"=>200,
      "publicationLink"=>array(
        "PublicationsLinksID"=>$results->PublicationsLinksID,
        "LinkTypeID"=>$results->LinkTypeID,
        "Link"=>$results->Link
      )
    );

    // Return the success
    echo json_encode($result);
  }

  /**
   * Name: update
   * Purpose: Update the specified PublicationsLinks row using data from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   */
  public function update() {
    // Create a new Model
    $model = new PublicationsLinksModel();

    // Get the POST variables
    $publicationsLinksID = $this->request->getPost('publicationsLinksID');
    $linkTypeID = $this->request->getPost('linkTypeID');
    $link = $this->request->getPost('link');

    // Make sure the variables are valid
    if (empty($publicationsLinksID) or empty($linkTypeID) or empty($link)) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Make sure the link type exists
    if ($this->linkTypeExists($linkTypeID) == false) {
      echo json_encode(array("statusCode"=>201));
      return;
    }

    // Do the update
    $model->save([
      'PublicationsLinksID' => $publicationsLinksID,
      'LinkTypeID' => $linkTypeID,
      'Link' => $link,
    ]);

    // Return the success
    echo json_encode(array("statusCode"=>200));
  }

  /**
   * Name: linkTypeExists
   * Purpose: Checks to see if the specified LinkTypeID exists in the database
   *
   * Parameters:
   *  string $linkTypeID - The LinkTypeID to search for
   *
   * Returns: true if the LinkTypeID exists or false if it does not
   */
   private function linkTypeExists(string $linkTypeID) {
     // Get the row
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('LinkTypes');
     $builder->select('LinkTypeID');
     $builder->where('LinkTypeID', $linkTypeID);

     // Run the query
     $results = $builder->get()->getNumRows();

     // Return the result
     if ($results == 1) {
       return true;
     }
     return false;
   }

   /**
    * Name: add
    * Purpose: Adds a row to the PublicationsLinks table using variables from the POST
    *
    * Parameters: None
    *
    * Returns: json encoded array with status code (200 = success, 201 = failure)
    *  and the PublicationsLinksID of the newly inserted row
    */
   public function add() {
     // Create a new Model
     $model = new PublicationsLinksModel();

     // Get the POST variables
     $publicationID = $this->request->getPost('publicationID');
     $linkTypeID = $this->request->getPost('linkTypeID');
     $link = $this->request->getPost('link');

     // Make sure the variables are valid
     if (empty($publicationID) or empty($linkTypeID) or empty($link)) {
       echo json_encode(array("statusCode"=>201));
       return;
     }

     // Make sure the link type exists
     if ($this->linkTypeExists($linkTypeID) == false) {
       echo json_encode(array("statusCode"=>201));
       return;
     }

     // Do the insert
     $model->save([
       'PublicationID' => $publicationID,
       'LinkTypeID' => $linkTypeID,
       'Link' => $link,
     ]);

     // Get the ID of the insert
     $publicationsLinksID = $this->getMaxPublicationsLinksID($publicationID, $linkTypeID, $link);

     // Return the success
     echo json_encode(array("statusCode"=>200, "publicationsLinksID"=>$publicationsLinksID));
   }

   /**
    * Name: remove
    * Purpose: Remove a row from the PublicationsLinks table using variables from the POST
    *
    * Parameters: None
    *
    * Returns: json encoded array with status code (200 = success, 201 = failure)
    */
   public function remove() {
     // Create a new Model
     $model = new PublicationsLinksModel();

     // Get the POST variables
     $publicationsLinksID = $this->request->getPost('publicationsLinksID');

     // Make sure the variables are valid
     if (empty($publicationsLinksID)) {
       echo json_encode(array("statusCode"=>201));
       return;
     }

     // Do the delete
     $model->delete($publicationsLinksID);

     // Return the success
     echo json_encode(array("statusCode"=>200));
   }

   /**
    * Name: getMaxPublicationsLinksID
    * Purpose: Finds the latest (MAX) PublicationsLinksID that has the matching
    *  PublicationID, LinkTypeID, and Link
    *
    * Parameters:
    *  $publicationID - The ID of the publication
    *  $linkTypeID - The link type ID
    *  $link - The link
    *
    * Returns: The MAX(PublicationsLinksID) where the PublicationID, LinkTypeID, and Link match
    */
   private function getMaxPublicationsLinksID($publicationID, $linkTypeID, $link) {
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('PublicationsLinks');
     $builder->selectMax('PublicationsLinksID');
     $builder->where('PublicationID', $publicationID);
     $builder->where('LinkTypeID', $linkTypeID);
     $builder->where('Link', $link);

     // Run the query
     $results = $builder->get()->getRow();

     // Return the ID
     return $results->PublicationsLinksID;
   }
 }
