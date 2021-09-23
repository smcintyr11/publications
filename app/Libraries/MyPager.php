<?php namespace App\Libraries;

class MyPager {
  // Member variables
  protected int $maxRows = 0;
  protected int $maxPages = 1;
  protected int $rowsPerPage = 1;
  protected \CodeIgniter\HTTP\URI $baseUrl;
  protected int $currentPage = 1;
  protected string $queryString = "";


  /**
   * Name: __construct
   * Purpose: Constructor - Assigns object initialization variables
   *
   * Parameters:
   *  URI $baseUrl - The base Url for the index page
   *  string $queryString - The query for the index page
   *  int $rowsPerPage - The number of rows per page
   *  int $maxRows - The maximum number of rows in the index
   *  int $currentPage - The current page number
   *
   * Returns: None
   */
  public function __construct(\CodeIgniter\HTTP\URI $baseUrl, string $queryString, int $rowsPerPage = 1, int $maxRows = 1, int $currentPage = 1) {
    // Assign parameters
    $this->baseUrl = $baseUrl;
    $this->queryString = $queryString;
    $this->rowsPerPage = $rowsPerPage;
    $this->currentPage = $currentPage;

    // Get the max number of rows
    $this->maxRows = $maxRows;

    // Get the max number of pages
    $this->setMaxPages();
  }

  /**
   * Name: setMaxPages
   * Purpose: Sets the maximum number of pages using internal variables
   *
   * Parameters: None
   *
   * Returns: None
   */
  protected function setMaxPages() {
    $maxPages = intdiv($this->maxRows, $this->rowsPerPage);
    if ($this->maxRows % $this->rowsPerPage != 0) {
      $maxPages = $maxPages + 1;
    }
    $this->maxPages = $maxPages;
  }

  /**
   * Name: getCurrentRows
   * Purpose: Returns the list of rows for the current page
   *
   * Parameters: None
   *
   * Returns: array - An array of rows
   */
  public function getCurrentRows() {
    // Create the query object
    $sql = $this->queryString . " LIMIT " . $this->rowsPerPage;
    if ($this->currentPage > 1) {
       $sql = $sql . " OFFSET " . ($this->rowsPerPage * ($this->currentPage - 1));
    }
    $db = \Config\Database::connect('publications');
    $query = $db->query($sql);

    // Return the result
    return $query->getResult();
  }

  /**
   * Name: getRowsPerPage
   * Purpose: Returns the number of rows per page
   *
   * Parameters: None
   *
   * Returns: int - Number of rows per page
   */
  public function getRowsPerPage() {
    // Return the number of rows per page
    return $this->rowsPerPage;
  }

  /**
   * Name: setRowsPerPage
   * Purpose: Updates the number of rows per page, and will automatically
   *  adjust the current page number to reflect the change in the number
   *  of rows per page.
   *
   * Parameters:
   *  int $rowsPerPage - The new number of rows per page
   *
   * Returns: int - The newly updated current page number
   */
  public function setRowsPerPage(int $rowsPerPage) {
    // Get the current row (currentPage * rowsPerPage)
    $currentRow = 1 + (($this->currentPage - 1) * $this->rowsPerPage);

    // Update the rows per pager
    $this->rowsPerPage = $rowsPerPage;

    // Recalculate the max number of pages
    $this->setMaxPages();

    // Figure out what page you should be on
    $newCurrentPage = intdiv($currentRow, $this->rowsPerPage) + 1;

    // Return the new page
    return $newCurrentPage;
  }

  /**
   * This function will generate the pager links in one of two forms
   * Form 1 - If there are 7 or less pages
   *  1 2 3 4 5 6 7 (or 1 -> # of pages e.g. 1 2 3 4)
   *
   * Form 2 - If there are more than 7+ pages
   * 1 2 ... 7 (assume page 1 is the current page)
   * 1 2 3 ... 7 (assume page 2 is the current page)
   * 1 2 3 4 ...  (assume page 3 is the current page)
   * 1 ... 3 4 5 ... 7 (assume page 4 is the current page)
   * 1 ... 4 5 6 7 (assume page 5 is the current page)
   * 1 ... 5 6 7 (assume page 6 is the current page)
   * 1 ... 6 7 (assume page 7 is the current page)
   * Logic for paging 7+ pages:
   *  - Always display 1 and last page
   *  - Always try to show (current page - 1) (current page) (current page + 1)
   *    - This fails when current page = 1 or current page = max pages.
   *      Then don't show (current page - 1) or (current page + 1) respectively
   *
   * Link for current page should be disabled to provide visual cue
   */
  // Function to create the pager links
  /**
   * Name: createLinks
   * Purpose: Generates the pager links based on the current page, and the base url.
   *  The pager links will be in one of two forms
   *    Form 1 - If there are 7 or less pages
   *      1 2 3 4 5 6 7 (or 1 -> # of pages e.g. 1 2 3 4)
   *
   *    Form 2 - If there are more than 7+ pages
   *      1 2 ... 7 (assume page 1 is the current page)
   *      1 2 3 ... 7 (assume page 2 is the current page)
   *      1 2 3 4 ...  (assume page 3 is the current page)
   *      1 ... 3 4 5 ... 7 (assume page 4 is the current page)
   *      1 ... 4 5 6 7 (assume page 5 is the current page)
   *      1 ... 5 6 7 (assume page 6 is the current page)
   *      1 ... 6 7 (assume page 7 is the current page)
   *
   *  Logic for paging 7+ pages:
   *    - Always display 1 and last page
   *    - Always try to show (current page - 1) (current page) (current page + 1)
   *      - This fails when current page = 1 or current page = max pages.
   *        Then don't show (current page - 1) or (current page + 1) respectively
   *
   * Parameters: None
   *
   * Returns: string - HTML string representing the pager links
   */
  public function createLinks() {
    // Setup the URI
    $uri = $this->baseUrl;
    $uri = new \CodeIgniter\HTTP\URI(substr($uri, 0, strpos($uri, "/index")));
    $uri->setSegment(2, 'index');

    // Generate the pager HTML
    $html = '<nav><ul class="pagination">';

    // Create the link to the first page (it will ALWAYS exist)
    if ($this->currentPage != 1) {
      $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/1">1</a>';
    } else {
      $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/1">1</a>';
    }

    // Determine how we will generate the links (one complete set )
    if ($this->maxPages <= 7) {   // Simple form
      // Create continuous stream of links
      for ($i = 2; $i <= $this->maxPages; $i++) {
        if ($this->currentPage != $i) {
          $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i . '">' . $i . '</a>';
        } else {
          $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i . '">' . $i . '</a>';
        }
      }
    } else {    // Complex form
      // Determine which complex form we are using
      if ($this->currentPage == 1) {
        // Case 1 - Current page = 1
        // 1 2 ... n
        $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/2">2</a><span class="px-2">...</span>';
      } elseif ($this->currentPage == 2) {
        // Case 2 - Current page = 2
        // 1 2 3 ... n
        for ($i = 2; $i <= 3; $i++) {
          if ($this->currentPage != $i) {
            $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          } else {
            $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          }
        }
        $html = $html . '<span class="px-2">...</span>';
      } elseif ($this->currentPage == 3) {
        // Case 3 - Current page = 3
        // 1 2 3 4 ... n
        for ($i = 2; $i <= 4; $i++) {
          if ($this->currentPage != $i) {
            $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          } else {
            $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          }
        }
        $html = $html . '<span class="px-2">...</span>';
      } elseif ($this->currentPage == ($this->maxPages - 2)) {
        // Case 4 - Current page = maxPages - 2
        // 1 ... (mp - 3) (mp-2) (mp-1) mp
        $html = $html . '<span class="px-2">...</span>';
        for ($i = $this->maxPages - 3; $i <= $this->maxPages - 1; $i++) {
          if ($this->currentPage != $i) {
            $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          } else {
            $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          }
        }
      } elseif ($this->currentPage == ($this->maxPages - 1)) {
        // Case 5 - Current page = maxPages - 1
        // 1 ... (mp-2) (mp-1) mp
        $html = $html . '<span class="px-2">...</span>';
        for ($i = $this->maxPages - 2; $i <= $this->maxPages - 1; $i++) {
          if ($this->currentPage != $i) {
            $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          } else {
            $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          }
        }
      } elseif ($this->currentPage == $this->maxPages) {
        // Case 6 - Current page = maxPages
        // 1 ... (mp-1) mp
        $html = $html . '<span class="px-2">...</span>';
        $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $this->maxPages - 1 .  '">' . $this->maxPages - 1 . '</a>';
      } else {
        // Case 7 - All other
        // 1 .. (cp-1) (cp) (cp+1) .. (mp)
        $html = $html . '<span class="px-2">...</span>';
        for ($i = $this->currentPage - 1; $i <= $this->currentPage + 1; $i++) {
          if ($this->currentPage != $i) {
            $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          } else {
            $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '/' . $i .  '">' . $i . '</a>';
          }
        }
        $html = $html . '<span class="px-2">...</span>';
      }

      // Create the link to the last page (it will always exist in this form)
      if ($this->currentPage != $this->maxPages) {
        $html = $html . '<a class="btn btn-link px-2" href="' . $uri . '">' . $this->maxPages . '</a>';
      } else {
        $html = $html . '<a class="btn btn-link px-2 disabled" href="' . $uri . '">' . $this->maxPages . '</a>';
      }
    }

    // Close out the pager HTML
    $html = $html . "</ul></nav>";

    return $html;
  }
}
