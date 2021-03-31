<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/people/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="PersonID" class="col-sm-2 col-form-label font-weight-bold">Person ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="PersonID" id="PersonID" value="<?= $person['PersonID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="DisplayName" class="col-sm-2 col-form-label font-weight-bold">Display Name:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="DisplayName" id="DisplayName" value="<?= $person['DisplayName'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="FirstName" class="col-sm-2 col-form-label font-weight-bold">First Name:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="FirstName" id="FirstName" value="<?= $person['FirstName'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="LastName" class="col-sm-2 col-form-label font-weight-bold">Last Name:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="LastName" id="LastName" value="<?= $person['LastName'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="Organization" class="col-sm-2 col-form-label font-weight-bold">Organization:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="Organization" id="Organization" value="<?= $person['Organization'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this person?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/people/index/<?= $page ?>">No</a>
    </div>
  </form>
</div>
