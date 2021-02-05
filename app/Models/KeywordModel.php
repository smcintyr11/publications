<?php namespace App\Models;

use CodeIgniter\Model;

class KeywordModel extends Model {
  // Member variables
  protected $table = "Keywords";
  protected $primaryKey = "KeywordID";
  protected $allowedFields = ["KeywordEnglish", "KeywordFrench"];

  // Function to get the list of Keywords
  public function getKeywords($cur_sort = null, $filter = null, $rows = 25, $page = 1) {
    // Determine which way  we are ordering
    if ($cur_sort == "id_asc") {
      $keywords = $this->orderBy("KeywordID", "asc");
    } elseif ($cur_sort == "id_desc") {
      $keywords = $this->orderBy("KeywordID", "desc");
    } elseif ($cur_sort == "key_eng_asc") {
      $keywords = $this->orderBy("KeywordEnglish", "asc");
    } elseif ($cur_sort == "key_eng_desc") {
      $keywords = $this->orderBy("KeywordEnglish", "desc");
    } elseif ($cur_sort == "key_fr_asc") {
      $keywords = $this->orderBy("KeywordFrench", "asc");
    } elseif ($cur_sort == "key_fr_desc") {
      $keywords = $this->orderBy("KeywordFrench", "desc");
    }

    // Determine if we are filtering
    if ($filter != '') {
      $keywords = $keywords->like('KeywordEnglish', $filter);
      $keywords = $keywords->orLike('KeywordFrench', $filter);
    }

    // Return the Keywords
    return $keywords->paginate($rows, 'default', $page);
  }

  // Function to get a specific Keyword
  public function getKeyword($keywordID) {
    return $this->find($keywordID);
  }

  // Function to delete a specific Keyword
  public function deleteKeyword($keywordID) {
    $this->delete($keywordID);
  }

  // Function to get the count of the filtered $rows
  public function getCount($filter) {
    // Get the count of the filtered rows
    $db = \Config\Database::connect();
    $builder = $db->table('Keywords');
    $builder->select('KeywordEnglish');
    $builder->like('KeywordEnglish', $filter);
    $builder->orLike('KeywordFrench', $filter);
    return $builder->countAllResults();
  }
}
