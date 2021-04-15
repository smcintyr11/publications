<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsAuthorsModel extends Model {
  // Member variables
  protected $table = "PublicationsAuthors";
  protected $primaryKey = "PublicationsAuthorsID";
  protected $allowedFields = ["PublicationID", "PersonID", "PrimaryAuthor"];
}
