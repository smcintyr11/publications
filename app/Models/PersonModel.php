<?php namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model {
  // Member variables
  protected $DBGroup  = 'publications';
  protected $table = "People";
  protected $primaryKey = "PersonID";
  protected $useSoftDeletes = true;
  protected $allowedFields = ["CreatedBy","ModifiedBy","Modified","DeletedBy","deleted_at","LastName", "FirstName", "DisplayName", "OrganizationID"];

  /**
   * Name: getPerson
   * Purpose: Retrieves a specific Person from the database
   *
   * Parameters:
   *  int $personID - The PersonID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getPerson(int $personID) {
    // Create the query
    $db = \Config\Database::connect('publications');
    $query = $db->query('SELECT p.Created, p.CreatedBy, p.Modified, p.ModifiedBy, p.PersonID, p.FirstName, p.LastName, p.DisplayName, p.OrganizationID, o.Organization FROM People AS p LEFT JOIN Organizations AS o ON p.OrganizationID = o.OrganizationID WHERE p.PersonID = ' . $personID);

    // Create the result
    foreach ($query->getResult() as $row) {
      $result = array(
        "Created" => $row->Created,
        "CreatedBy" => $row->CreatedBy,
        "Modified" => $row->Modified,
        "ModifiedBy" => $row->ModifiedBy,
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
}
