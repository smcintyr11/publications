<?php namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
  /**
   * Name: getUser
   * Purpose: Gets the display name from the users.users table based on the
   *  ID passed it
   *
   * Parameters:
   *  int (or NULL) $ID - The ID of the user
   *
   * Returns: None
   */
  public function getUser($ID) {
    if (is_null($ID)) {
      return null;
    }
    if ($this->getUserCount($ID) > 0) {
      // Load the query builder
      $db = \Config\Database::connect();
      $builder = $db->table('users');

      // Generate and execute the delete
      $builder->select('displayName');
      $builder->where('deleted_at', null);
      $builder->where('ID', $ID);

      // Run the query
      $result = $builder->get()->getRow();

      // Return the result
      return $result->displayName;
    }

    // User not found
    return null;
  }

  /**
   * Name: getUserCount
   * Purpose: Gets the number of rows from the users.users table that have an
   *  ID that matches the parameter
   *
   * Parameters:
   *  int (or NULL) $ID - The ID of the user
   *
   * Returns: None
   */
  private function getUserCount($ID) {
    if (is_null($ID)) {
      return 0;
    }

    // Load the query builder
    $db = \Config\Database::connect();
    $builder = $db->table('users');

    // Generate and execute the delete
    $builder->select('displayName');
    $builder->where('deleted_at', null);
    $builder->where('ID', $ID);

    // Return the result
    return $builder->get()->getNumRows();
  }
}
