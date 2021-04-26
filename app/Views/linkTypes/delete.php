<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/linkTypes/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("linkTypeID",
      $linkType['LinkTypeID'], "Link Type ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("linkType",
      $linkType['LinkType'], "Link Type"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'linkTypes', 'link type', $page); ?>

  </form>

</div>
