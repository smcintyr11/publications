<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/keywords/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="KeywordID" class="col-sm-2 col-form-label font-weight-bold">Keyword ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="KeywordID" id="KeywordID" value="<?= $keyword['KeywordID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="KeywordEnglish" class="col-sm-2 col-form-label font-weight-bold">Keyword English:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="Client" value="<?= $keyword['KeywordEnglish'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="KeywordFrench" class="col-sm-2 col-form-label font-weight-bold">Keyword French:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="Client" value="<?= $keyword['KeywordFrench'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this keyword?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/keywords/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
