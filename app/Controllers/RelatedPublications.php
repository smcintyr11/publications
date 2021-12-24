<?php namespace App\Controllers;

use App\Models\RelatedPublicationModel;
use CodeIgniter\Controller;

class RelatedPublications extends Controller {
  /**
   * Name: add
   * Purpose: Adds a row to the RelatedPublications table using variables from the POST
   *
   * Parameters: None
   *
   * Returns: json encoded array with status code (200 = success, 201 = failure)
   *  and the RelatedPublicationsID of the newly inserted row
   */
   public function add() {
     // Load the helper functions
     helper(['auth']);

     // Create a new Model
     $model = new RelatedPublicationModel();

     // Get the POST variables
     $parentPublicationID = $this->request->getPost('publicationID');
     $childPublicationID = $this->request->getPost('publication2ID');

     // Make sure the variables are valid
     if (empty($parentPublicationID) or empty($childPublicationID)) {
       echo json_encode(array("statusCode"=>201));
       return;
     }

     // Check to see if the relationship exists
     if ($this->isRelatedPublication($parentPublicationID, $childPublicationID)) {
       echo json_encode(array("statusCode"=>202));
       return;
     }
     if ($parentPublicationID == $childPublicationID) {
       echo json_encode(array("statusCode"=>203));
       return;
     }


     // Does the relationship already exist
     if ($this->isRelatedPublication($parentPublicationID, $childPublicationID)) {
       echo json_encode(array("statusCode"=>202));
       return;
     }

     // Do the insert
     $model->save([
       'CreatedBy' => user_id(),
       'ParentPublicationID' => $parentPublicationID,
       'ChildPublicationID' => $childPublicationID,
     ]);

     // Get the ID of the insert
     $relatedPublicationsID = $this->getMaxRelatedPublicationsID($parentPublicationID, $childPublicationID);

     // Get details of the related publication
     $details = $this->getRelatedPublicationDetails($childPublicationID);

     // Return the success
     echo json_encode(array("statusCode"=>200, "relatedPublicationsID"=>$relatedPublicationsID,
      "publicationID"=>$childPublicationID, "reportNumber"=>$details->ReportNumber,
      "primaryTitle"=>$details->PrimaryTitle, "reportType"=>$details->ReportType));
   }

   /**
    * Name: remove
    * Purpose: Remove a row from the RelatedPublications table using variables from the POST
    *
    * Parameters: None
    *
    * Returns: json encoded array with status code (200 = success, 201 = failure)
    */
   public function remove() {
     // Load the helper functions
     helper(['auth']);

     // Create a new Model
     $model = new RelatedPublicationModel();

     // Get the POST variables
     $relatedPublicationsID = $this->request->getPost('relatedPublicationsID');

     // Make sure the variables are valid
     if (empty($relatedPublicationsID)) {
       echo json_encode(array("statusCode"=>201));
       return;
     }

     // Do the Delete
     $model->save([
       'DeletedBy' => user_id(),
       'deleted_at' => date("Y-m-d H:i:s"),
       'RelatedPublicationsID' => $relatedPublicationsID,
     ]);

     // Return the success
     echo json_encode(array("statusCode"=>200));
   }

   /**
    * Name: getMaxRelatedPublicationsID
    * Purpose: Finds the latest (MAX) RelatedPublicationsID that has the matching
    *  ParentPublicationID and ChildPublicationID
    *
    * Parameters:
    *  $parentPublicationID - The ID of the parent publication
    *  $childPublicationID - The ID of the child publication
    *
    * Returns: The MAX(RelatedPublicationsID) where the ParentPublicationID and
    *   ChildPublicationID match
    */
   private function getMaxRelatedPublicationsID($parentPublicationID, $childPublicationID) {
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('RelatedPublications');
     $builder->selectMax('RelatedPublicationsID');
     $builder->where('deleted_at', null);
     $builder->where('ParentPublicationID', $parentPublicationID);
     $builder->where('ChildPublicationID', $childPublicationID);

     // Run the query
     $results = $builder->get()->getRow();

     // Return the ID
     return $results->RelatedPublicationsID;
   }

   /**
    * Name: getRelatedPublicationDetails
    * Purpose: Gets the details (publication id, report number, report type)
    *   of the specified publication
    *
    * Parameters:
    *  $relatedPublicationsID - The ID of the publication
    *
    * Returns: array of data
    */
   private function getRelatedPublicationDetails($relatedPublicationsID) {
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select('Publications.ReportNumber, Publications.PrimaryTitle, ReportTypes.ReportType');
     $builder->join('ReportTypes', 'Publications.ReportTypeID = ReportTypes.ReportTypeID', 'left');
     $builder->where('Publications.deleted_at', null);
     $builder->where('Publications.PublicationID', $relatedPublicationsID);

     // Run the query
     $results = $builder->get()->getRow();

     // Return the results
     return $results;
   }

   /**
    * Name: isRelatedPublication
    * Purpose: Searches for a RelatedPublicationID where the ParentPublicationID
    *   and ChildPublicationID (or vice versa) match the supplied parameters
    *
    * Parameters:
    *  $parentPublicationID - The ID of the parent publication
    *  $childPublicationID - The ID of the child publication
    *
    * Returns: Boolean - Matching record or not
    */
   private function isRelatedPublication($parentPublicationID, $childPublicationID) {
     // Create the query builder object
     $db = \Config\Database::connect('publications');
     $builder = $db->table('RelatedPublications');
     $builder->select('RelatedPublicationsID');
     $builder->where('deleted_at', null);
     $builder->where('ParentPublicationID', $parentPublicationID);
     $builder->where('ChildPublicationID', $childPublicationID);

     // Run the query
     $rows = $builder->get()->getNumRows();

     // Check for a match
     if ($rows > 0) { return True; }


     // Run the 2nd query
     $builder2 = $db->table('RelatedPublications');
     $builder2->select('RelatedPublicationsID');
     $builder2->where('deleted_at', null);
     $builder2->where('ParentPublicationID', $childPublicationID);
     $builder2->where('ChildPublicationID', $parentPublicationID);

     // Run the query
     $rows2 = $builder2->get()->getNumRows();

     // Check for a match
     if ($rows2 > 0) { return True; }

     // No match
     return False;
   }

   /**
    * Name: searchPublication
    * Purpose: Uses a query variable passed to the URL to search for a publication
    *  by report number that is like the search term
    *
    * Parameters: None
    *
    * Returns: Outputs JSON - An array of data
    */
   public function searchPublication() {
     // Varoable declaration
     $autoComplete = array();

     // Build the query
     $searchString = $this->request->getVar('term');
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select('PublicationID, CONCAT(PrimaryTitle, " (", ReportNumber, ")") AS Description');
     $builder->where('deleted_at', null);
     $builder->groupStart();
       $builder->like('ReportNumber', $searchString);
       $builder->orLike('PrimaryTitle', $searchString);
     $builder->groupEnd();
     $builder->orderBy('PublicationID', 'DESC');

     // Run the query and compile an array of organization data
     $autoComplete = array();
     $query = $builder->get();
     foreach ($query->getResult() as $row)
     {
       $item = array(
       'id'=>$row->PublicationID,
       'label'=>$row->Description,
       'value'=>$row->Description,
       );
       array_push($autoComplete,$item);
     }

     // Output JSON response
     echo json_encode($autoComplete);
   }

   /**
    * Name: searchExactReportNumber
    * Purpose: Uses a query variable passed to the URL to search for a
    *   publication with the exact report number
    *
    * Parameters: None
    *
    * Returns: Outputs JSON - An array of data
    */
   public function searchExactReportNumber() {
     // Build the query
     $searchString = $this->request->getVar('reportNumber');
     $db = \Config\Database::connect('publications');
     $builder = $db->table('Publications');
     $builder->select('PublicationID');
     $builder->where('deleted_at', null);
     $builder->groupStart();
       $builder->where('ReportNumber', $searchString);
       $builder->orWhere('PrimaryTitle', $searchString);
     $builder->groupEnd();

     // Search for a result
     $result = $builder->get()->getRow();
     if (empty($result)) {
       echo json_encode(array("statusCode"=>201));
       return;
     }
     echo json_encode(array("statusCode"=>200, "publicationID"=>$result->PublicationID));
   }

   /**
    * Name: getTitle
    * Purpose: Search for a publication title using variables from the POST
    *
    * Parameters: None
    *
    * Returns: json encoded array with status code (200 = success, 201 = failure)
    */
   public function getTitle() {
     // Get the publication id
     $publicationID = $this->request->getPost('publicationID');

     // Load the query builder
     $db = \Config\Database::connect('publications');

     // Create the query
     $builder = $db->table('Publications');
     $builder->select('PrimaryTitle');
     $builder->where('PublicationID', $publicationID);
     $builder->where('deleted_at', null);

     // Return the result
     $result = $builder->get()->getRow();
     if (empty($result)) {
       echo json_encode(array("statusCode"=>201));
     }
     echo json_encode(array("statusCode"=>200, "title"=>$result->PrimaryTitle));
   }
}
