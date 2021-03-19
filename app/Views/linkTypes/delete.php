<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/linkTypes/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <div class="form-group row">
      <label for="LinkTypeID" class="col-sm-2 col-form-label font-weight-bold">Link Type ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="LinkTypeID" id="LinkTypeID" value="<?= $linkType['LinkTypeID'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label for="LinkType" class="col-sm-2 col-form-label font-weight-bold">Link Type:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" id="LinkType" value="<?= $linkType['LinkType'] ?>">
      </div>
    </div>
    <div class="form-group row">
      <label>Are you sure you wish to delete this link type?</label>
    </div>
    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Yes</button>
      <a class="btn btn-danger m-1" href="/linkTypes/index/<?= $page ?>">No</a>
    </div>
  </form>

</div>
