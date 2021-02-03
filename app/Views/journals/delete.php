<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/journals/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
      <label for="JournalID" class="col-sm-2 col-form-label font-weight-bold">Journal ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="JournalID" id="JournalID" value="<?= $journal['JournalID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="Journal" class="col-sm-2 col-form-label font-weight-bold">Journal:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="Journal" value="<?= $journal['Journal'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this journal?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/journals/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">No</a>
    </div>
  </form>

</div>
