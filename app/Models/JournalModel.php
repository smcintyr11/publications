<?php namespace App\Models;

use CodeIgniter\Model;

class JournalModel extends Model {
  // Member variables
  protected $table = "Journals";
  protected $primaryKey = "JournalID";
  protected $allowedFields = ["Journal"];

  // Function to get the list of Journals
  public function getJournals($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $journals = $this->orderBy("JournalID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $journals = $this->orderBy("JournalID", "desc");
    } elseif ($cur_sort == "journal_asc") {
      $journals = $this->orderBy("Journal", "asc");
    } elseif ($cur_sort == "journal_desc") {
      $journals = $this->orderBy("Journal", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $journals = $journals->like('Journal', $filter);
    }

    // Return the Journals
    return $journals->paginate($rows, 'default', $page);
  }

  // Function to get a specific Journal
  public function getJournal($journalID) {
    return $this->find($journalID);
  }

  // Function to delete a specific Journal
  public function deleteJournal($journalID) {
    $this->delete($journalID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('Journals');
    $builder->select('Journal');
    $builder->like('Journal', $filter);
    return $builder->countAllResults();
  }
}
