<div class="container-lg my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/organizations/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="cur_sort" value="<?= $cur_sort ?>">
    <input type="hidden" name="rows" value="<?= $rows ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="filter" value="<?= $filter ?>">

    <div class="form-group row">
      <label for="organizationID" class="col-sm-2 col-form-label font-weight-bold">Organization ID:</label>
      <div class="col-sm-10">
        <input type="text" readonly class="form-control-plaintext" name="organizationID" id="organizationID" value="<?= $organization['OrganizationID'] ?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="organization" class="col-sm-2 col-form-label font-weight-bold">Organization:</label>
      <div class="col-sm-10">
        <input class="form-control" type="input" name="organization" id="client" value="<?= set_value('organization', $organization['Organization']) ?>"/><br />
      </div>
    </div>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Organization</button>
    <a class="btn btn-info m-1" href="/organizations/index/<?= $cur_sort ?>/<?= $rows ?>/<?= $page ?>/<?= $filter ?>">Back to Organizations</a>
  </form>
</div>
