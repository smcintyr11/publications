<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsReviewersModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "PublicationsReviewers";
  protected $primaryKey = "PublicationsReviewersID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","PublicationID", "PersonID", "LeadReviewer"];
}
