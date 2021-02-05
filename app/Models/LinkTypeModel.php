<?php namespace App\Models;

use CodeIgniter\Model;

class LinkTypeModel extends Model {
  // Member variables
  protected $table = "LinkTypes";
  protected $primaryKey = "LinkTypeID";
  protected $allowedFields = ["LinkType"];

  // Function to get the list of Link Types
  public function getLinkTypes($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $linkTypes = $this->orderBy("LinkTypeID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $linkTypes = $this->orderBy("LinkTypeID", "desc");
    } elseif ($cur_sort == "linkType_asc") {
      $linkTypes = $this->orderBy("LinkType", "asc");
    } elseif ($cur_sort == "linkType_desc") {
      $linkTypes = $this->orderBy("LinkType", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $linkTypes = $linkTypes->like('LinkType', $filter);
    }

    // Return the Link Types
    return $linkTypes->paginate($rows, 'default', $page);
  }

  // Function to get a specific Link Type
  public function getLinkType($linkTypeID) {
    return $this->find($linkTypeID);
  }

  // Function to delete a specific Link Type
  public function deleteLinkType($linkTypeID) {
    $this->delete($linkTypeID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('LinkTypes');
    $builder->select('LinkType');
    $builder->like('LinkType', $filter);
    return $builder->countAllResults();
  }
}
