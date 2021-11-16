<?php namespace App\Libraries;

class Users {
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
  public static function getUser($ID) {
    if (is_null($ID)) {
      return null;
    }
    if (Users::getUserCount($ID) > 0) {
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
  private static function getUserCount($ID) {
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
