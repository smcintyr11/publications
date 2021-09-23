<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsReviewersModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "PublicationsReviewers";
  protected $primaryKey = "PublicationsReviewersID";
  protected $allowedFields = ["PublicationID", "PersonID", "LeadReviewer"];
}
