<script type="text/javascript" src="/scripts/unique.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/organizations/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("organization",
      set_value('organization'), "-- Enter the organization name --", "Organization"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Organization</button>
    <a class="btn btn-info m-1" href="/organizations/index/<?= $page ?>">Back to Organizations</a>
  </form>

</div>

<script type="text/javascript">
$(document).ready(function(){
  // Add uniqueness checking to the link type
  $("#organization").change(function(){uniqueCheck("/organizations/uniqueCheck", "#organization");});
});
</script>
