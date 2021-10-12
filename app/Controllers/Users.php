<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Users extends Controller {
  /**
   * Name: searchPerson
   * Purpose: Uses a query variable passed to the URL to search for a person
   *  that is like the search term.
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchPerson() {
    // Varoable declaration
    $autoComplete = array();

    // Build the query
    $searchString = $this->request->getVar('term');
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder->select('id, displayName');
    $builder->where('deleted_at', null);
    $builder->like('displayName', $searchString);
    $builder->orLike('firstname', $searchString);
    $builder->orLike('lastname', $searchString);

    // Run the query and compile an array of organization data
    $autoComplete = array();
    $query = $builder->get();
    foreach ($query->getResult() as $row)
    {
      $item = array(
      'id'=>$row->id,
      'label'=>$row->displayName,
      'value'=>$row->displayName,
      );
      array_push($autoComplete,$item);
    }

    // Output JSON response
    echo json_encode($autoComplete);
  }

  /**
   * Name: searchExactDisplayName
   * Purpose: Uses a query variable passed to the URL to search for a person
   *  that matches the search term
   *
   * Parameters: None
   *
   * Returns: Outputs JSON - An array of data
   */
  public function searchExactDisplayName() {
    // Variable declaration
    $searchString = $this->request->getVar('displayName');

    // Is there an exact match
    if ($this->exactDisplayNameCount($searchString) > 0) {
      // Build the query
      $db = \Config\Database::connect();
      $builder = $db->table('users');
      $builder->select('ID');
      $builder->where('deleted_at', null);
      $builder->where('displayName', $searchString);

      // Run the query
      $result = $builder->get()->getRow();

      // Return success
      echo json_encode(array("statusCode"=>200, "ID"=>$result->ID));
      return;
    }

    // Return failure
    echo json_encode(array("statusCode"=>201));
  }

  /**
   * Name: exactDisplayNameCount
   * Purpose: Finds out how many rows have a person's displayName that exactly
   * matches the search string passed in
   *
   * Parameters:
   *  string $searchString - The person to search for
   *
   * Returns: Number of matching rows
   */
  private function exactDisplayNameCount(string $searchString) {
    // Build the query
    $db = \Config\Database::connect();
    $builder = $db->table('users');
    $builder->select('ID');
    $builder->where('deleted_at', null);
    $builder->where('displayName', $searchString);

    // Run the query
    $result = $builder->get()->getNumRows();

    // Return the number of rows
    return $result;
  }
}
