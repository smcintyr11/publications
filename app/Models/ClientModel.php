<?php namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model {
  // Member variables
  protected $table = "Clients";
  protected $primaryKey = "ClientID";
  protected $allowedFields = ["Client"];

  /**
   * Name: getClient
   * Purpose: Retrieves a specific Client from the database
   *
   * Parameters:
   *  int $clientID - The ClientID that corresponds to a row in the database
   *
   * Returns: array - An array representing the database row
   */
  public function getClient($clientID) {
    return $this->find($clientID);
  }

  /**
   * Name: deleteClient
   * Purpose: Deletes a specific Client from the database
   *
   * Parameters:
   *  int $clientID - The ClientID that corresponds to a row in the database
   *
   * Returns: None
   */
  public function deleteClient($clientID) {
    $this->delete($clientID);
  }
}
