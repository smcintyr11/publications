<?php namespace App\Models;

use CodeIgniter\Model;

class PublicationsCommentsModel extends Model {
  // Member variables
  protected $table = "PublicationsComments";
  protected $primaryKey = "PublicationsCommentsID";
  protected $allowedFields = ["PublicationID", "DateEntered", "Comment"];
}
