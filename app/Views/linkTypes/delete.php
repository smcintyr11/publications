<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $linkType['Created'] . "<br>";
      if (is_null($linkType['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $linkType['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>
  
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="<?= base_url() ?>/linkTypes/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("linkTypeID",
      $linkType['LinkTypeID'], "Link Type ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("linkType",
      $linkType['LinkType'], "Link Type"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'linkTypes', 'link type', $page); ?>

  </form>

</div>
