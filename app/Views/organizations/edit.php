<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $organization['Created'] . "<br>";
      if (is_null($organization['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $organization['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/organizations/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("organizationID",
      $organization['OrganizationID'], "Organization ID"); ?>

    <?= MyFormGeneration::generateTextBox("organization",
      set_value('organization', $organization['Organization']),
      "-- Enter the organization name --", "Organization"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Organization</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/organizations/index/<?= $page ?>">Back to Organizations</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#organization").change(function(){uniqueCheck("<?= base_url() ?>/organizations/uniqueCheck", "#organization", <?= $organization['OrganizationID'] ?>, "<?= $organization['Organization'] ?>");});
});
</script>
