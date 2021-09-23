<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsKeywordsModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "PublicationsKeywords";
  protected $primaryKey = "PublicationsKeywordsID";
  protected $allowedFields = ["PublicationID", "KeywordID"];
}
