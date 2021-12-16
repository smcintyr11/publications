<?php namespace App\Controllers;

use CodeIgniter\Controller;

class SystemVariables extends Controller {
  /**
   * Name: getVariable
   * Purpose: Returns the value of the specified variable
   *
   * Parameters:
   *  $variableName - The variable to search for
   *
   * Returns:
   *  The value of the variable
   */
  public function getVariable($variableName) {
    // Build the query
    $db = \Config\Database::connect('publications');
    $builder = $db->table('SystemVariables');
    $builder->select('Value');
    $builder->where('VariableName', $variableName);

    // Run the query
    $result = $builder->get()->getRow();

    // Return the result
    return $result->Value;
  }
}
