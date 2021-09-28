<?php
  $data = [
    'title' => 'Authorization',
  ];
?>
<?= view('templates/header.php', $data) ?>

<?= view('templates/menu.php', $data) ?>

<div class="container my-3 py-3">
  <main role="main" class="container">
  	<?= $this->renderSection('main') ?>
  </main><!-- /.container -->
</div>

<?= view('templates/footer.php', $data) ?>
