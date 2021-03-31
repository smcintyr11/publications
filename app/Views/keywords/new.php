<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/keywords/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="keywordEnglish" class="col-sm-2 col-form-label font-weight-bold">Keyword English:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="keywordEnglish" value="<?= set_value('keywordEnglish') ?>" /><br />
      </div>
    </div>

    <div class="form-group row">
      <label for="keywordFrench" class="col-sm-2 col-form-label font-weight-bold">Keyword French:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="keywordFrench" value="<?= set_value('keywordFrench') ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Keyword</button>
    <a class="btn btn-info m-1" href="/keywords/index/<?= $page ?>">Back to Keywords</a>
  </form>

</div>
