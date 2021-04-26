<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <?= \Config\Services::validation()->listErrors(); ?>

  <form class="form-group" action="/linkTypes/edit" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateIDTextBox("linkTypeID",
      $linkType['LinkTypeID'], "Link Type ID"); ?>

    <?= MyFormGeneration::generateTextBox("linkType",
      set_value('linkType', $linkType['LinkType']),
      "-- Enter the link type --", "Link Type"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit">Save Link Type</button>
    <a class="btn btn-info m-1" href="/linkTypes/index/<?= $page ?>">Back to Link Types</a>
  </form>
</div>
