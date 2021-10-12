<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsAuthorsModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "PublicationsAuthors";
  protected $primaryKey = "PublicationsAuthorsID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","PublicationID", "PersonID", "PrimaryAuthor"];
}
