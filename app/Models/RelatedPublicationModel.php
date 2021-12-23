<?php namespace App\Models;

use CodeIgniter\Model;

class RelatedPublicationModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "RelatedPublications";
  protected $primaryKey = "RelatedPublicationsID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","ParentPublicationID", "ChildPublicationID"];
}
