<?php namespace App\Models;

use CodeIgniter\Model;

class OrganizationModel extends Model {
  // Member variables
  protected $table = "Organizations";
  protected $primaryKey = "OrganizationID";
  protected $allowedFields = ["Organization"];

  // Function to get the list of Organizations
  public function getOrganizations($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $organizations = $this->orderBy("OrganizationID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $organizations = $this->orderBy("OrganizationID", "desc");
    } elseif ($cur_sort == "org_asc") {
      $organizations = $this->orderBy("Organization", "asc");
    } elseif ($cur_sort == "org_desc") {
      $organizations = $this->orderBy("Organization", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $organizations = $organizations->like('Organization', $filter);
    }

    // Return the Organizations
    return $organizations->paginate($rows, 'default', $page);
  }

  // Function to get a specific Organization
  public function getOrganization($organizationID) {
    return $this->find($organizationID);
  }

  // Function to delete a specific Organization
  public function deleteOrganization($organizationID) {
    $this->delete($organizationID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('Organizations');
    $builder->select('Organization');
    $builder->like('Organization', $filter);
    return $builder->countAllResults();
  }
}
