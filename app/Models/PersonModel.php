<?php namespace App\Models;

use CodeIgniter\Model;

class PersonModel extends Model {
  // Member variables
  protected $table = "People";
  protected $primaryKey = "PersonID";
  protected $allowedFields = ["LastName", "FirstName", "DisplayName", "OrganizationID"];

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

  /**
   * Name: deletePerson
   * Purpose: Deletes a specific Person from the database
   *
   * Parameters:
   *  int $personID - The PersonID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deletePerson($personID) {
    $this->delete($personID);
  }
}
