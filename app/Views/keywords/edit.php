<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/keywords/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
      <label for="keywordID" class="col-sm-2 col-form-label font-weight-bold">Client ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="keywordID" id="keywordID" value="<?= $keyword['KeywordID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="keywordEnglish" class="col-sm-2 col-form-label font-weight-bold">Keyword English:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="keywordEnglish" value="<?= set_value('keywordEnglish', $keyword['KeywordEnglish']) ?>" /><br />
      </div>
    </div>

    <div class="form-group row">
      <label for="keywordFrench" class="col-sm-2 col-form-label font-weight-bold">Keyword French:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="keywordFrench" value="<?= set_value('keywordFrench', $keyword['KeywordFrench']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Keyword</button>
    <a class="btn btn-info m-1" href="/keywords/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Keywords</a>
  </form>
</div>
