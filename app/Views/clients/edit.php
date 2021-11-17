<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <div class="form-row">
    <?php
      $version = "Created by " . $createdBy . " on " . $client['Created'] . "<br>";
      if (is_null($client['Modified'])) {
        $version = $version . "Not modified";
      } else {
        $version = $version . "Modified by " . $modifiedBy . " on " . $client['Modified'];
      }
      echo (MyFormGeneration::generateItalicText("Version", $version, 12, "right"));
      ?>
  </div>

  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/clients/edit" method="post">
    <br />
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("clientID",
      $client['ClientID'], "Client / Publisher ID"); ?>


    <?= MyFormGeneration::generateTextBox("client",
      set_value('client', $client['Client']),
      "-- Enter the client or publisher name --", "Client / Publisher"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Client / Publisher</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/clients/index/<?= $page ?>">Back to Clients / Publishers</a>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#client").change(function(){uniqueCheck("<?= base_url() ?>/clients/uniqueCheck", "#client", <?= $client['ClientID'] ?>, "<?= $client['Client'] ?>");});
});
</script>
