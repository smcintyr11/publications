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
   public static function generateNewButtonURL(string $currentURL, string $newType) {
     // Parse the URL
     $t = parse_url($currentURL);

     // Create the new URL and return it
     $newUrl = $t['scheme'] . "://" . $t['host'] . ':' . $t['port'] . '/' . $newType . '/new/1';
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
    *
    * Returns: string - The HTML for these form elements
    */
    public static function generateIDTextBox(string $textboxID, ?string $value, string $textboxLabel) {
      // Generate the HTML
     $html = '<div class="form-group row">
      <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
      <div class="col-10">
      <input type="text" readonly class="form-control-plaintext" name="' . $textboxID . '" id="' . $textboxID . '" value="' . $value . '" />
      <br /></div></div>';

      // Return the resultinng HTML
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
   *
   * Returns: string - The HTML for these form elements
   */
   public static function generateTextBox(string $textboxID, ?string $value, string $placeholder, string $textboxLabel) {
     // Generate the HTML
     $html = '<div class="form-group row">
      <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
      <div class="col-10">
      <input class="form-control" type="input" name="' . $textboxID . '" value="' . $value . '" placeholder="' . $placeholder . '"  />
      <br /></div></div>';

     // Return the resultinng HTML
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
    *
    * Returns: string - The HTML for these form elements
    */
    public static function generateMultilineTextBox(string $textboxID, ?string $value, string $placeholder, string $textboxLabel, int $rows = 5) {
      // Generate the HTML
      $html = '<div class="form-group row">
       <label for="' . $textboxID . '" class="col-2 col-form-label font-weight-bold">' . $textboxLabel . ':</label>
       <div class="col-10">
       <textarea class="form-control" rows="' . $rows . '" name="' . $textboxID . '" placeholder="' . $placeholder . '" >' . $value . '</textarea>
       <br /></div></div>';

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
    *  string $newButtonURL - The URL for the new button
    *  string $lookupID     - The ID for the lookup value
    *  string $lookupValue  - The value to populate the lookupID with
    *  string $buttonID     - The ID for the button
    *
    * Returns: string - The HTML for these form elements
    */
    public static function generateLookupTextBox(string $textboxID, ?string $textboxValue, string $placeholder, string $textboxLabel, ?string $newButtonURL, string $lookupID, ?string $lookupValue, ?string $buttonID = null) {
      // Generate the HTML
      $html = '<div class="form-group row">
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
      $html = $html . '>Add ' . $textboxLabel . '</button>
      </div>
      <input type="hidden" id="' . $lookupID . '" name="' . $lookupID . '" value="' . $lookupValue . '">
      </div>';

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
     *
     * Returns: string - The HTML for these form elements
     */
     public static function generateSelect(string $selectID, ?string $value, string $placeholder, string $selectLabel, array $options) {
       // Convert the options to html
       $optionList = MyFormGeneration::generateOptions($options, $value);

       // Generate the HTML
      $html = '<div class="form-group row">
        <label for="' . $selectID . '" class="col-2 col-form-label font-weight-bold">' . $selectLabel . ':</label>
        <div class="col-10">
        <select class="form-control" id="' . $selectID . '" name="' . $selectID . '" value="' . $value . '" >
        <option value="">' . $placeholder . '</option>' . $optionList . '
        </select><br /></div></div>';

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
         $optionList = $optionList . '<option value=' . $id . '"';

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
      *  string $value       - The value to populate the checkbox with
      *  string $checkboxLabel  - The text for the label
      *
      * Returns: string - The HTML for these form elements
      */
      public static function generateCheckBox(string $checkboxID, ?string $value, string $checkboxLabel) {
        // Generate the HTML
        $html = '<div class="form-group row">
        <label for="' . $checkboxID . '" class="col-2 col-form-label font-weight-bold">' . $checkboxLabel . ':</label>
        <div class="col-10">
        <input class="custom-control custom-checkbox" type="checkbox" name="' . $checkboxID . '" value="' . $value . '" />
        <br /></div></div>';

        // Return the resultinng HTML
        return $html;
      }
}
