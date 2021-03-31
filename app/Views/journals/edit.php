<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/journals/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="journalID" class="col-sm-2 col-form-label font-weight-bold">Journal ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="journalID" id="journalID" value="<?= $journal['JournalID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="journal" class="col-sm-2 col-form-label font-weight-bold">Journal:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="journal" id="journal" value="<?= set_value('journal', $journal['Journal']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Journal</button>
    <a class="btn btn-info m-1" href="/journals/index<?= $page ?>">Back to Journals</a>
  </form>
</div>
