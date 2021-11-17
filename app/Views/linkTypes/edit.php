<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

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

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/linkTypes/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("linkTypeID",
      $linkType['LinkTypeID'], "Link Type ID"); ?>

    <?= MyFormGeneration::generateTextBox("linkType",
      set_value('linkType', $linkType['LinkType']),
      "-- Enter the link type --", "Link Type"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Link Type</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/linkTypes/index/<?= $page ?>">Back to Link Types</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#linkType").change(function(){uniqueCheck("<?= base_url() ?>/linkTypes/uniqueCheck", "#linkType", <?= $linkType['LinkTypeID'] ?>, "<?= $linkType['LinkType'] ?>");});
});
</script>
