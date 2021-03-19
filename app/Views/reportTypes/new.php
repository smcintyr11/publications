<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/reportTypes/new" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="reportType" class="col-sm-2 col-form-label font-weight-bold">Report Type:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="reportType" value="<?= set_value('reportType') ?>"/><br />
      </div>
    </div>
    <div class="form-group row">
      <label for="abbreviation" class="col-sm-2 col-form-label font-weight-bold">Abbreviation:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="abbreviation" value="<?= set_value('abbreviation') ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Create Report Type</button>
    <a class="btn btn-info m-1" href="/reportTypes/index/<?= $page ?>">Back to Report Types</a>
  </form>

</div>
