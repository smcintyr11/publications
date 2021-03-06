<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/clients/new" method="post">
    <br />
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("client",
      set_value('client'),
      "-- Enter the client or publisher name --", "Client / Publisher"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Client / Publisher</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/clients/index/<?= $page ?>">Back to Clients / Publishers</a>
  </form>

</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#client").change(function(){uniqueCheck("<?= base_url() ?>/clients/uniqueCheck", "#client", null);});
});
</script>
