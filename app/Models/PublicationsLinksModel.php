<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsLinksModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "PublicationsLinks";
  protected $primaryKey = "PublicationsLinksID";
  protected $allowedFields = ["PublicationID", "LinkTypeID", "Link"];
}
