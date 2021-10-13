<?php namespace App\Libraries;

class MyFormGeneration {

  /**
   * Name: generateNewButtonURL
   * Purpose: Generates a URL for the new button based on the current URL
   *
   * Parameters:
   *  string $currentURL  - The current page URL
   *  string $newType     - The type of the new button URL
   *
   * Returns: string - The URL for the new page for the given type
   */
 public static function generateNewButtonURL(string $newType) {
   // Create the new URL and return it
   $newUrl = base_url() . '/' . $newType . '/new/1';
   return $newUrl;
 }

 /**
    * Name: generateIDTextBox
    * Purpose: Generates the label and textbox HTML
    *
    * Parameters:
    *  string $textboxID   - The value to use for the input name field
    *  string $value       - The value to populate the textbox with
    *  string $textboxLabel  - The text for the label
    *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
    *
    * Returns: string - The HTML for these form elements
    */
  public static function generateIDTextBox(string $textboxID, ?string $value, string $textboxLabel, bool $hidden=false) {
    // Variable declaration
    $html = '';

    // Generate the HTML
    if ($hidden) {
      $html = '<div style="display: none;">';
    }

   $html = $html . '<div class="form-group row">
    <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
    <div class="col-10">
    <input type="text" readonly class="form-control-plaintext" name="' . $textboxID . '" id="' . $textboxID . '" value="' . $value . '" />
    <br /></div></div>';
    if ($hidden) {
      $html = $html . '</div>';
    }

    // Return the resulting HTML
    return $html;
  }

  /**
     * Name: generateItalicText
     * Purpose: Generates the label HTML
     *
     * Parameters:
     *  string $labelID - The ID for the label
     *  string $text - The text to display
     *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
     *
     * Returns: string - The HTML for these form elements
     */
   public static function generateItalicText(string $labelID, string $text, bool $hidden=false) {
     // Variable declaration
     $html = '';

     // Generate the HTML
     if ($hidden) {
       $html = '<div style="display: none;">';
     }

    $html = $html . '<div class="form-group row">
     <div class="col-12">
     <label style="font-size: 0.75em" class="font-italic" id="' . $labelID .'" name="' . $labelID . '">' . $text . '</label>
     </div></div>';
     if ($hidden) {
       $html = $html . '</div>';
     }

     // Return the resulting HTML
     return $html;
   }

  /**
   * Name: generateTextBox
   * Purpose: Generates the label and textbox HTML
   *
   * Parameters:
   *  string $textboxID   - The value to use for the input name field
   *  string $value       - The value to populate the textbox with
   *  string $placeholder - The placeholder text
   *  string $textboxLabel  - The text for the label
   *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
   *
   * Returns: string - The HTML for these form elements
   */
 public static function generateTextBox(string $textboxID, ?string $value, string $placeholder, string $textboxLabel, bool $hidden=false) {
   // Variable declaration
   $html = '';

   // Generate the HTML
   if ($hidden) {
     $html = '<div style="display: none;">';
   }

   $html = $html . '<div class="form-group row">
    <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
    <div class="col-10">
    <input class="form-control" type="input" name="' . $textboxID . '" value="' . $value . '" placeholder="' . $placeholder . '" id="' . $textboxID . '"  />
    <br /></div></div>';
    if ($hidden) {
      $html = $html . '</div>';
    }

   // Return the resulting HTML
   return $html;
 }

 /**
  * Name: generateNumberTextBox
  * Purpose: Generates the label and textbox HTML
  *
  * Parameters:
  *  string $textboxID   - The value to use for the input name field
  *  string $value       - The value to populate the textbox with
  *  string $placeholder - The placeholder text
  *  string $textboxLabel  - The text for the label
  *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
  *
  * Returns: string - The HTML for these form elements
  */
 public static function generateNumberTextBox(string $textboxID, ?string $value, string $placeholder, string $textboxLabel, bool $hidden=false) {
   // Variable declaration
   $html = '';

   // Generate the HTML
   if ($hidden) {
     $html = '<div style="display: none;">';
   }

  $html = $html . '<div class="form-group row">
   <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
   <div class="col-10">
   <input class="form-control" type="number" name="' . $textboxID . '" value="' . $value . '" placeholder="' . $placeholder . '" id="' . $textboxID . '"  />
   <br /></div></div>';
   if ($hidden) {
     $html = $html . '</div>';
   }

  // Return the resulting HTML
  return $html;
 }

 /**
  * Name: generateDateTextBox
  * Purpose: Generates the label and textbox HTML
  *
  * Parameters:
  *  string $textboxID   - The value to use for the input name field
  *  string $value       - The value to populate the textbox with
  *  string $textboxLabel  - The text for the label
  *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
  *
  * Returns: string - The HTML for these form elements
  */
 public static function generateDateTextBox(string $textboxID, ?string $value, string $textboxLabel, bool $hidden=false) {
   // Variable declaration
   $html = '';

   // Generate the HTML
   if ($hidden) {
     $html = '<div style="display: none;">';
   }

  $html = $html . '<div class="form-group row">
   <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
   <div class="col-10">
   <input class="form-control" type="Date" name="' . $textboxID . '" value="' . $value . '" id="' . $textboxID . '"  />
   <br /></div></div>';
   if ($hidden) {
     $html = $html . '</div>';
   }

  // Return the resulting HTML
  return $html;
 }

 /**
  * Name: generateHiddenInput
  * Purpose: Generates the HTML for a hidden input field
  *
  * Parameters:
  *  string $textboxID   - The value to use for the input name field
  *  string $value       - The value to populate the textbox with
  *
  * Returns: string - The HTML for these form elements
  */
 public static function generateHiddenInput(string $textboxID, ?string $value) {
  // Generate the HTML
  $html = '<input type="hidden" name="' . $textboxID . '" value="' . $value . '" id="' . $textboxID . '"  />';

  // Return the resulting HTML
  return $html;
 }

 /**
    * Name: generateMultilineTextBox
    * Purpose: Generates the label and textbox HTML
    *
    * Parameters:
    *  string $textboxID   - The value to use for the input name field
    *  string $value       - The value to populate the textbox with
    *  string $placeholder - The placeholder text
    *  string $textboxLabel  - The text for the label
    *  int $rows            - The number of rows for the textbox
    *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
    *
    * Returns: string - The HTML for these form elements
    */
  public static function generateMultilineTextBox(string $textboxID, ?string $value, string $placeholder, string $textboxLabel, int $rows = 5, bool $readonly = false, bool $hidden=false) {
    // Variable declaration
    $html = '';

    // Generate the HTML
    if ($hidden) {
      $html = '<div style="display: none;">';
    }

    $html = $html . '<div class="form-group row">
     <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
     <div class="col-10">
     <textarea class="form-control" rows="' . $rows . '" name="' . $textboxID . '" placeholder="' . $placeholder . '" id="' . $textboxID . '" ';
     if ($readonly == true) {
       $html = $html . "readonly ";
     }
     $html = $html . ' >' . $value . '</textarea>
     <br /></div></div>';
     if ($hidden) {
       $html = $html . '</div>';
     }

    // Return the resultinng HTML
    return $html;
  }

 /**
    * Name: generateLookupTextBoxWithButton
    * Purpose: Generates the label, textbox and button HTML
    *
    * Parameters:
    *  string $textboxID    - The value to use for the input name field
    *  string $textboxValue - The value to populate the textbox with
    *  string $placeholder  - The placeholder text
    *  string $textboxLabel  - The text for the label
    *  string $newButtonURL - The URL for the new button
    *  string $lookupID     - The ID for the lookup value
    *  string $lookupValue  - The value to populate the lookupID with
    *  string $buttonID     - The ID for the button
    *  string $buttonText   - The text for the button (if you want to specify something other than the default)
    *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
    *
    * Returns: string - The HTML for these form elements
    */
  public static function generateLookupTextBoxWithButton(string $textboxID, ?string $textboxValue, string $placeholder, string $textboxLabel, ?string $newButtonURL, string $lookupID, ?string $lookupValue, ?string $buttonID = null, ?string $buttonText = null, bool $hidden=false) {
    // Variable declaration
    $html = '';

    // Generate the HTML
    if ($hidden) {
      $html = '<div style="display: none;">';
    }

    $html = $html . '<div class="form-group row">
      <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
      <div class="col-8">
      <input class="form-control" type="input" id="' . $textboxID . '" name="' . $textboxID . '" value="' . $textboxValue . '" placeholder="' . $placeholder . '" />
      <br />
      </div>
      <div class="col-2">
      <button type="button" class="btn btn-success" ';

    if (is_null($newButtonURL) == false) {
      $html = $html . 'onclick="window.open(\'' . $newButtonURL . '\', \'_blank\');" ';
    }
    if (is_null($buttonID) == false) {
      $html = $html . 'id="' . $buttonID . '" ';
    }
    if (is_null($buttonText)) {
      $html = $html . '>Add ' . $textboxLabel . '</button>';
    } else {
      $html = $html . '>' . $buttonText . '</button>';
    }
    $html = $html . '</div>
    <input type="hidden" id="' . $lookupID . '" name="' . $lookupID . '" value="' . $lookupValue . '">
    </div>';
    if ($hidden) {
      $html = $html . '</div>';
    }

    // Return the resultinng HTML
    return $html;
  }

  /**
     * Name: generateLookupTextBox
     * Purpose: Generates the label and textbox HTML
     *
     * Parameters:
     *  string $textboxID    - The value to use for the input name field
     *  string $textboxValue - The value to populate the textbox with
     *  string $placeholder  - The placeholder text
     *  string $textboxLabel  - The text for the label
     *  string $lookupID     - The ID for the lookup value
     *  string $lookupValue  - The value to populate the lookupID with
     *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
     *
     * Returns: string - The HTML for these form elements
     */
   public static function generateLookupTextBox(string $textboxID, ?string $textboxValue, string $placeholder, string $textboxLabel, string $lookupID, ?string $lookupValue, bool $hidden=false) {
     // Variable declaration
     $html = '';

     // Generate the HTML
     if ($hidden) {
       $html = '<div style="display: none;">';
     }

     $html = $html . '<div class="form-group row">
       <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
       <div class="col-10">
         <input class="form-control" type="input" id="' . $textboxID . '" name="' . $textboxID . '" value="' . $textboxValue . '" placeholder="' . $placeholder . '" />
         <input type="hidden" id="' . $lookupID . '" name="' . $lookupID . '" value="' . $lookupValue . '">
         <br />
       </div>
     </div>';
     if ($hidden) {
       $html = $html . '</div>';
     }

     // Return the resultinng HTML
     return $html;
   }

  /**
     * Name: generateSelectBox
     * Purpose: Generates the label and textbox HTML
     *
     * Parameters:
     *  string $selectID   - The value to use for the select name field
     *  string $value       - The value to populate the select with
     *  string $placeholder - The placeholder text for the first option
     *  string $textboxLabel  - The text for the label
     *  array $optionList  - The array of rows to be converted to options
     *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
     *
     * Returns: string - The HTML for these form elements
     */
 public static function generateSelect(string $selectID, ?string $value, string $placeholder, string $selectLabel, array $options, bool $hidden=false) {
   // Convert the options to html
   $optionList = MyFormGeneration::generateOptions($options, $value);

   // Variable declaration
   $html = '';

   // Generate the HTML
   if ($hidden) {
     $html = '<div style="display: none;">';
   }

  $html = $html . '<div class="form-group row">
    <label for="' . $selectID . '" class="col-2 col-form-label font-weight-bold">' . $selectLabel . ':</label>
    <div class="col-10">
    <select class="form-control" id="' . $selectID . '" name="' . $selectID . '" value="' . $value . '" >
    <option value="">' . $placeholder . '</option>' . $optionList . '
    </select><br /></div></div>';
    if ($hidden) {
      $html = $html . '</div>';
    }

   // Return the resultinng HTML
   return $html;
 }

 /**
      * Name: generateOptions
      * Purpose: Converts an array of database rows to HTML options
      *
      * Parameters:
      *  array $options   - The array of rows to be converted to options
      *  string $value       - The selected ID
      *
      * Returns: string - The HTML for these form elements
      */
 private static function generateOptions (array $options, ?string $value) {
   // Variable declaration
   $optionList = '';

   // Loop through the array (each row from the database is an array item (e.g. $options[0]....$options[n]))
   foreach ($options as $option) {
     // Convert the current from from stdClass to array
     $t = json_decode(json_encode($option), true);

     // Grab the ID and text from the array
     $id = current($t);
     $text = next($t);

     // Generate the html for the option
     $optionList = $optionList . '<option value="' . $id . '"';

     // If the id == value then mark the option as selected
     if ($id == $value) {
       $optionList = $optionList . ' selected="selected"';
     }
     $optionList = $optionList . '>' . $text . '</option>';
   }

   // Return the html
   return $optionList;
 }

 /**
      * Name: generateCheckBox
      * Purpose: Generates the label and checkbox HTML
      *
      * Parameters:
      *  string $checkboxID   - The value to use for the checkbox
      *  bool $value       - The value to populate the checkbox with
      *  string $checkboxLabel  - The text for the label
      *  bool $hidden - A boolean value indicating whether to hide the html element with the display style element
      *
      * Returns: string - The HTML for these form elements
      */
  public static function generateCheckBox(string $checkboxID, ?bool $value, string $checkboxLabel, bool $hidden=false) {
    // Variable declaration
    $html = '';

    // Generate the HTML
    if ($hidden) {
      $html = '<div style="display: none;">';
    }

    $html = $html . '<div class="form-group row">
    <label for="' . $checkboxID . '" class="col-2 col-form-label font-weight-bold">' . $checkboxLabel . ':</label>
    <div class="col-10">
    <input class="custom-control custom-checkbox" type="checkbox" name="' . $checkboxID . '" ';
    if ($value) {
      $html = $html . " checked ";
    }
    $html = $html . '/><br /></div></div>';
    if ($hidden) {
      $html = $html . '</div>';
    }

    // Return the resultinng HTML
    return $html;
  }

  /**
       * Name: generateDRAlert
       * Purpose: Generates the dependentRecords HTML if there are dependent Records
       *
       * Parameters:
       *  bool $dependentRecords - A boolean indicating whether there are dependent records
       *
       * Returns: string - The HTML for the alert or an empty string
       */
 public static function generateDRAlert(bool $dependentRecords) {
   if ($dependentRecords) {
     $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
     There are dependent records.  You are unable to delete this record.</div>';
     return $html;
   }
   return "";
 }

 /**
        * Name: generateDeleteOptions
        * Purpose: Generates the "Return to <<controller>>" or "Delete Yes/No"
        *   output for delete pages
        *
        * Parameters:
        *  bool $dependentRecords - A boolean indicating whether there are dependent records
        *  string $contoller - The name of the controller this delete page is for
        *  string $singular - The singluar name of a controller item (e.g. Person rather than People)
        *  string $page - The index page number
        *
        * Returns: string - The HTML for the form part
        */
 public static function generateDeleteOptions(bool $dependentRecords, string $controller, string $singular, string $page) {
   // Dependent Records
   if ($dependentRecords) {
     $html = '<div class="form-group row">
       <a class="btn btn-info m-1" href="/' . $controller . '/index/' .
       $page . '">Return to ' . ucfirst($controller) . '</a>
       </div>';
   } else {
     // No dependent records
     $html = '<div class="form-group row">
      <label>Are you sure you wish to delete this ' . $singular . '?</label>
      </div>
      <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/' . $controller . '/index/' . $page . '">No</a>
      </div>';
   }
   // Return the html
   return $html;
 }

 /**
  * Name: generateColumnHeader
  * Purpose: Generates the column header for the index page
  *
  * Parameters:
  *  string $contoller - The name of the controller this delete page is for
  *  string $columnTitle - The title of the column
  *  string $sortParam - The sort parameter to pass to the link
  *  string $currentSort - The session's current sort parameter
  *  string $upID - The id for sorting up
  *  string $downID - The id for sorting down
  *
  * Returns: string - The HTML for the form part
  */
  public static function generateColumnHeader(string $controller, string $columnTitle, string $sortParam, string $currentSort, string $upID, string $downID) {
    // Generate the html
    $html = '<th scope="col" class="align-top">
      <a class="btn btn-link" href="/' . $controller . '/index/1?sort=' . $sortParam . '">' . $columnTitle . '</a>';
      if ($currentSort == $upID) {
        $html = $html . '<i class="fas fa-sort-up"></i>';
      } elseif ($currentSort == $downID) {
        $html = $html . '<i class="fas fa-sort-down"></i>';
      }
      $html = $html . "</th>";

    // Return the html
    return $html;
  }

  /**
   * Name: generateColumnHeaderWithFilter
   * Purpose: Generates the column header for the index page
   *
   * Parameters:
   *  string $contoller - The name of the controller this delete page is for
   *  string $columnTitle - The title of the column
   *  string $sortParam - The sort parameter to pass to the link
   *  string $currentSort - The session's current sort parameter
   *  string $upID - The id for sorting up
   *  string $downID - The id for sorting down
   *  string $selectID - The value to use for the select name field
   *  string $value - The value to populate the select with
   *  string $placeholder - The placeholder text for the first option
   *  string $formName - The form to associate the select control with
   *  array $options - The array of rows to be converted to options
   *
   * Returns: string - The HTML for the form part
   */
   public static function generateColumnHeaderWithFilter(string $controller, string $columnTitle, string $sortParam, string $currentSort, string $upID, string $downID, string $selectID, ?string $value, string $placeholder, ?string $formName, array $options) {
     // Convert the options to html
     $optionList = MyFormGeneration::generateOptions($options, $value);

     // Generate the html
     $html = '<th scope="col" class="align-top">
       <a class="btn btn-link" href="/' . $controller . '/index/1?sort=' . $sortParam . '">' . $columnTitle . '</a>';
       if ($currentSort == $upID) {
         $html = $html . '<i class="fas fa-sort-up"></i>';
       } elseif ($currentSort == $downID) {
         $html = $html . '<i class="fas fa-sort-down"></i>';
       }
       $html = $html . '<i class="fas fa-filter"></i><select class="form-control" id="' . $selectID . '" name="' . $selectID . '" value="' . $value . '"';
       if (empty($formName) == false) {
         $html = $html . 'form="' . $formName . '"  onchange="this.form.submit()"';
       }
       $html = $html . ' ><option value="">' . $placeholder . '</option>' . $optionList . '</select></th>';

     // Return the html
     return $html;
   }

  /**
   * Name: generateRowsPerPage
   * Purpose: Generates the column header for the index page
   *
   * Parameters:
   *  string $rowsPerPage - The rows per page session variable
   *  string $links - The html for the links (should come from the controller)
   *
   * Returns: string - The HTML for the form part
   */
 public static function generateRowsPerPage(string $rowsPerPage, string $links) {
   // Generate the html
   $html = '<div class="row">
    <div class="col-1 btn">Page:</div>
    <div class="col-7">' . $links . '</div>
    <div class="col-2 btn text-right">Rows per page:</div>
    <div class="col-2">
    <select class="form-control mr-2" name="rowsPerPage" id="rowsPerPage" form="frmSearch" onchange="this.form.submit()">
    <option value=25 ' . ($rowsPerPage == 25 ? 'selected' : '') . '>25</option>
    <option value=50 ' . ($rowsPerPage == 50 ? 'selected' : '') . '>50</option>
    <option value=100 ' . ($rowsPerPage == 100 ? 'selected' : '') . '>100</option>
    </select></div></div>
   ';

   // Return the html
   return $html;
 }

 /**
  * Name: generateIndexSearch
  * Purpose: Generates the search form for the index page
  *
  * Parameters:
  *  string $controller - The name of the controller
  *  string $csrf_field - The html for the csrf_field
  *
  * Returns: string - The HTML for the form part
  */
  public static function generateIndexSearch(string $controller, string $csrf_field) {
    // Generate the html
    $html = '<form class="form-inline" action="/' . $controller . '/index/1" method="post" id="frmSearch">
      ' . $csrf_field . '
      <input class="form-control mr-2" type="text" name="filter" placeholder="Search">
      <button class="btn btn-success m-1" type="submit">Search</button>
      <a class="btn btn-info m-1" href="/' . $controller . '/index/1?filter=">Reset</a>
      </form>';

    // Return the html
    return $html;
  }

  /**
     * Name: generateIndexRowButtons
     * Purpose: Generates the HTML for the Edit and Delete buttons for a row on
     *  the index page
     *
     * Parameters:
     *  string $controller   - The name of the index controller
     *  string $page         - The current page number
     *  string $id           - The id (e.g. PublicationID) of the current row of
     *                         the table
     *  bool $renderEdit     - Should the edit button be rendered
     *  bool $renderDelete   - Should the delete button be rendered
     *  bool $enableEdit     - If the edit button is rendered, should it be enabled
     *  bool $enableDelete   - If the delete button is rendered, should it be enabled
     *  bool $showRush       - Should the rush button be rendered
     *  bool $renderView     - Should the view button be rendered (only necessary with publications)
     *
     * Returns: string - The HTML for these form elements
     */
 public static function generateIndexRowButtons(string $controller, string $page, string $id, bool $renderEdit=true, bool $renderDelete=true, bool $enableEdit=true, bool $enableDelete=true, bool $showRush=false, bool $renderView=false) {
   // Generate the HTML
  $html = '<td>';
  if ($renderView) {
      $html = $html . '<a href="/' . $controller . '/view/' . $page . '/' . $id . '" class="btn btn-success m-1 far fa-eye"></a>';
  }
  if ($renderEdit) {
      $html = $html . '<a href="/' . $controller . '/edit/' . $page . '/' . $id . '" class="btn btn-info m-1 fas fa-edit';
      if ($enableEdit == false) {
        $html = $html . ' disabled';
      }
      $html = $html . '"></a>';
  }
  if ($renderDelete) {
    if ($renderView) {
      $html = $html . "<br>";
    }
    $html = $html . ' <a href="/' . $controller . '/delete/1/' . $id . '" class="btn btn-danger m-1 fas fa-trash-alt';
    if ($enableDelete == false) {
      $html = $html . ' disabled';
    }
    $html = $html . '"></a>';
  }
  if ($showRush) {
    $html = $html . '<i class="btn btn-warning m-1 far fa-star"></i></div>';
  }

  $html = $html . '</td>';
   // Return the resulting HTML
   return $html;
 }

}
