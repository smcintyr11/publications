<script type="text/javascript" src="<?= base_url() ?>/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="<?= base_url() ?>/linkTypes/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("linkType",
      set_value('linkType'), "-- Enter the link type --", "Link Type"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit" id="submit">Create Link Type</button>
    <a class="btn btn-info m-1" href="<?= base_url() ?>/linkTypes/index/<?= $page ?>">Back to Link Types</a>
  </form>

</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#linkType").change(function(){uniqueCheck("<?= base_url() ?>/linkTypes/uniqueCheck", "#linkType");});
});
</script>
