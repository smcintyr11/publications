<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/people/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="personID" class="col-sm-2 col-form-label font-weight-bold">Person ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="personID" id="personID" value="<?= $person['PersonID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="lastName" class="col-sm-2 col-form-label font-weight-bold">Last Name:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" id="lastName" name="lastName" value="<?= set_value('lastName', $person['LastName']) ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="firstName" class="col-sm-2 col-form-label font-weight-bold">First Name:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" id="firstName" name="firstName" value="<?= set_value('firstName', $person['FirstName']) ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="displayName" class="col-sm-2 col-form-label font-weight-bold">Display Name:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" id="displayName" name="displayName" value="<?= set_value('displayName', $person['DisplayName']) ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="organization" class="col-sm-2 col-form-label font-weight-bold">Organization:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" id="organization" name="organization" value="<?= set_value('organization', $person['Organization']) ?>"/><br />
      </div>
      <input type="hidden" id="organizationID" name="organizationID" value="<?= set_value('organizationID'), $person['OrganizationID'] ?>">
    </div>

    <button class="btn btn-success m-1" type="submit" id="submit "name="submit">Save Person</button>
    <a class="btn btn-info m-1" href="/people/index/<?= $page ?>">Back to People</a>
  </form>
</div>

<script>
$(document).ready(function(){
  var organizationID = null;
  $("#organization").autocomplete({
    minLength: 1,
    source: function(request, response) {
      $.ajax({
        url: location.protocol + "//" + location.host + "/organizations/searchLocation",
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
      organizationID = ui.item.id;
      $("#organizationID").val(ui.item.id);
    }
  }).keyup(function(event){
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
  $("#submit").click(function(){

  });
});
</script>
