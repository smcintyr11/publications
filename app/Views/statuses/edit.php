<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/statuses/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="statusID" class="col-2 col-form-label font-weight-bold">Status ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="statusID" id="statusID" value="<?= $status['StatusID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="status" class="col-2 col-form-label font-weight-bold">Status:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="status" id="status" value="<?= set_value('status', $status['Status']) ?>"/><br />
      </div>
    </div>

    <div class="form-group row">
      <label for="expectedDuration" class="col-2 col-form-label font-weight-bold">Expected Duration:</label>
      <div class="col-10">
        <input class="form-control" type="number" name="expectedDuration" id="expectedDuration" value="<?= set_value('expectedDuration', $status['ExpectedDuration']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Status</button>
    <a class="btn btn-info m-1" href="/statuses/index/<?= $page ?>">Back to Statuses</a>
  </form>
</div>
