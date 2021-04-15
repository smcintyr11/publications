<?php
  use App\Libraries\MyFormGeneration;
 ?>

<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <form class="form-group" action="/publicationsKeywords/add" method="post">
    <?= csrf_field() ?>

    <?= MyFormGeneration::generateTextBox("publicationID",
        1, "-- publicationID --", "publicationID"); ?>

    <?= MyFormGeneration::generateTextBox("keywordID",
        1, "-- keywordID --", "keywordID"); ?>

    <div class="form-group row">
      <button class="btn btn-success m-1" type="submit" name="submit">Submit</button>
    </div>
  </form>

</div>
