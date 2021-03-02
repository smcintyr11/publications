<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/statuses/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
      <label for="statusID" class="col-sm-2 col-form-label font-weight-bold">Status ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="statusID" id="statusID" value="<?= $status['StatusID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="status" class="col-sm-2 col-form-label font-weight-bold">Status:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="status" id="status" value="<?= set_value('status', $status['Status']) ?>"/><br />
      </div>
    </div>

    <div class="form-group row">
      <label for="expectedDuration" class="col-sm-2 col-form-label font-weight-bold">Expected Duration:</label>
      <div class="col-sm-10">
        <input class="form-control" type="number" name="expectedDuration" id="expectedDuration" value="<?= set_value('expectedDuration', $status['ExpectedDuration']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Status</button>
    <a class="btn btn-info m-1" href="/statuses/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Statuses</a>
  </form>
</div>
