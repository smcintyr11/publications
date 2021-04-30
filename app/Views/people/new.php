<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>
  <?php
    if ($duplicate == true) {
      echo ('<div class="alert alert-danger alert-dismissible fade show" role="alert">
      That person already exists in the system.</div>');
    }
   ?>

  <form class="form-group" action="/people/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateTextBox("lastName",
      set_value('lastName'), "-- Enter the person's last name --", "Last Name"); ?>

    <?= MyFormGeneration::generateTextBox("firstName",
      set_value('firstName'), "-- Enter the person's first name --", "First Name"); ?>

    <?= MyFormGeneration::generateTextBox("displayName",
      set_value('displayName'), "-- How the person's name appears as an author or reviewer.  The system will try to autogenerate it, but you can edit. --", "Display Name"); ?>

    <?= MyFormGeneration::generateLookupTextBox("organization",
      set_value('organization'), "-- Enter an organization --", "Organization",
      MyFormGeneration::generateNewButtonURL("organizations"), "organizationID",
      set_value('organizationID')); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Person</button>
    <a class="btn btn-info m-1" href="/people/index/<?= $page ?>">Back to People</a>
  </form>
</div>

<script>
$(document).ready(function(){
  $("#organization").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/organizations/searchOrganization",
        datatype: "json",
        data: {
          term: request.term,
        },
        success: function(data) {
          data = $.parseJSON(data);
          response(data);
        },
      });
    },
    select: function(event, ui) {
      $("#organizationID").val(ui.item.id);
    }
  }).keyup(function(){
    if (event.which != 13) {
      $("#organizationID").val("");
    }
  });
  function generateDisplayName() {
    $("#displayName").val($("#lastName").val() + ", " + $("#firstName").val());
  };
  $("#firstName").change(function(){
    generateDisplayName();
  });
  $("#lastName").change(function(){
    generateDisplayName();
  });

});
</script>
