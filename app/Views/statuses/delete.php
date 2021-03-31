<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/statuses/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="StatusID" class="col-2 col-form-label font-weight-bold">Status ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="StatusID" id="StatusID" value="<?= $status['StatusID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="Status" class="col-2 col-form-label font-weight-bold">Status:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" id="Status" value="<?= $status['Status'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="ExpectedDuration" class="col-2 col-form-label font-weight-bold">Expected Duration:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" id="ExpectedDuration" value="<?= $status['ExpectedDuration'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this status?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/statuses/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
