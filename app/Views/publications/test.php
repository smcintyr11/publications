<!-- Load Table Sorter -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.widgets.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/css/theme.bootstrap_4.min.css" integrity="sha512-2C6AmJKgt4B+bQc08/TwUeFKkq8CsBNlTaNcNgUmsDJSU1Fg+R6azDbho+ZzuxEkJnCjLZQMozSq3y97ZmgwjA==" crossorigin="anonymous" />
<script type="text/javascript" src="/scripts/lookup.js"></script>
<script type="text/javascript" src="/scripts/publicationsEdit.js"></script>

<?php use App\Libraries\MyFormGeneration; ?>

<div class="container my-3 py-3">
  <form class="form-group" action="/Keywords/searchExactKeyword" method="post">
    <?= csrf_field() ?>

    <?= MyFormGeneration::generateIDTextBox("publicationID", 29, "Publication ID") ?>

    <?= MyFormGeneration::generateTextBox("keyword", '', "-- Enter the keyword --", "keyword"); ?>

    <button class="btn btn-success m-1" type="submit" name="submit" id="btnSubmit">Create keyword</button>
  </form>
</div>
