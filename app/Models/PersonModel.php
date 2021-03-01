<?php namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model {
  // Member variables
  protected $table = "People";
  protected $primaryKey = "PersonID";
  protected $allowedFields = ["LastName", "FirstName", "DisplayName", "OrganizationID"];

  // Function to get the list of People
  public function getPeopleOnPage($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table("People");
    $builder->select("PersonID, LastName, FirstName, DisplayName, Organization");
    $builder->join("Organizations", "People.OrganizationID = Organizations.OrganizationID");

    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $builder->orderBy("PersonID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $builder->orderBy("PersonID", "desc");
    } elseif ($cur_sort == "lname_asc") {
      $builder->orderBy("LastName", "asc");
    } elseif ($cur_sort == "lname_desc") {
      $builder->orderBy("LastName", "desc");
    } elseif ($cur_sort == "fname_asc") {
      $builder->orderBy("FirstName", "asc");
    } elseif ($cur_sort == "fname_desc") {
      $builder->orderBy("FirstName", "desc");
    } elseif ($cur_sort == "dname_asc") {
      $builder->orderBy("DisplayName", "asc");
    } elseif ($cur_sort == "dname_desc") {
      $builder->orderBy("DisplayName", "desc");
    } elseif ($cur_sort == "org_asc") {
      $builder->orderBy("Organization", "asc");
    } elseif ($cur_sort == "org_desc") {
      $builder->orderBy("Organization", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $builder->like('LastName', $filter);
      $builder->orLike('FirstName', $filter);
      $builder->orLike('DisplayName', $filter);
      $builder->orLike('Organization', $filter);
    }

    // Return the People
    return $builder->limit($rows, (($page-1)*$rows))->get()->getResultArray();
  }

  // Function to get the list of People
  public function getPeople($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $people = $this->orderBy("PersonID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $people = $this->orderBy("PersonID", "desc");
    } elseif ($cur_sort == "lname_asc") {
      $people = $this->orderBy("LastName", "asc");
    } elseif ($cur_sort == "lname_desc") {
      $people = $this->orderBy("LastName", "desc");
    } elseif ($cur_sort == "fname_asc") {
      $people = $this->orderBy("FirstName", "asc");
    } elseif ($cur_sort == "fname_desc") {
      $people = $this->orderBy("FirstName", "desc");
    } elseif ($cur_sort == "dname_asc") {
      $people = $this->orderBy("DisplayName", "asc");
    } elseif ($cur_sort == "dname_desc") {
      $people = $this->orderBy("DisplayName", "desc");
    } elseif ($cur_sort == "org_asc") {
      $people = $this->orderBy("Organization", "asc");
    } elseif ($cur_sort == "org_desc") {
      $people = $this->orderBy("Organization", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $people = $this->like('LastName', $filter);
      $people = $this->orLike('FirstName', $filter);
      $people = $this->orLike('DisplayName', $filter);
      $people = $this->orLike('Organization', $filter);
    }

    // Return the People
    return $people->paginate($rows, 'default', $page);
  }

  // Function to get a specific Person
  public function getPerson($personID) {
    // Create the query
    $db = \Config\Database::connect();
    $query = $db->query('SELECT p.PersonID, p.FirstName, p.LastName, p.DisplayName, p.OrganizationID, o.Organization FROM People AS p LEFT JOIN Organizations AS o ON p.OrganizationID = o.OrganizationID WHERE p.PersonID = ' . $personID);

    // Create the result
    foreach ($query->getResult() as $row) {
      $result = array(
        "PersonID" => $row->PersonID,
        "FirstName" => $row->FirstName,
        "LastName" => $row->LastName,
        "DisplayName" => $row->DisplayName,
        "OrganizationID" => $row->OrganizationID,
        "Organization" => $row->Organization,
      );
    }

    // Return the result
    return $result;
  }

  // Function to delete a specific Person
  public function deletePerson($personID) {
    $this->delete($personID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('People');
    $builder->select('DisplayName');
    if ($filter != '') {
      $builder->like('LastName', $filter);
      $builder->orLike('FirstName', $filter);
      $builder->orLike('DisplayName', $filter);
      $builder->orLike('Organization', $filter);
    }
    return $builder->countAllResults();
  }
}
