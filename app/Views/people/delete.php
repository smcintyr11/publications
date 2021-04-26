<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/people/delete" method="post">
    <?= csrf_field() ?>

    <input type="hidden" name="page" value="<?= $page ?>">

    <?= MyFormGeneration::generateDRAlert($dependentRecords); ?>

    <?= MyFormGeneration::generateIDTextBox("personID",
      $person['PersonID'], "Person ID"); ?>

    <?= MyFormGeneration::generateIDTextBox("displayName",
      $person['DisplayName'], "Display Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("firstName",
      $person['FirstName'], "First Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("lastName",
      $person['LastName'], "Last Name"); ?>

    <?= MyFormGeneration::generateIDTextBox("organization",
      $person['Organization'], "Organization"); ?>

    <?= MyFormGeneration::generateDeleteOptions($dependentRecords, 'people', 'person', $page); ?>
    
  </form>
</div>
