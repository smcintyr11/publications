<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/linkTypes/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="linkTypeID" class="col-2 col-form-label font-weight-bold">Link Type ID:</label>
      <div class="col-10">
        <input type="text" readonly class="form-control-plaintext" name="linkTypeID" id="linkTypeID" value="<?= $linkType['LinkTypeID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="linkType" class="col-2 col-form-label font-weight-bold">Link Type:</label>
      <div class="col-10">
        <input class="form-control" type="input" name="linkType" id="linkType" value="<?= set_value('linkType', $linkType['LinkType']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Link Type</button>
    <a class="btn btn-info m-1" href="/linkTypes/index/<?= $page ?>">Back to Link Types</a>
  </form>
</div>
