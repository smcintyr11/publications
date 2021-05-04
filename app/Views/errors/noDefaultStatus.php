<div class="container my-3 py-3">
  <h1><?= esc($title); ?></h1>

  <div class="alert alert-danger" role="alert">
  <p>There is no default status defined in the system.  Please visit the
  <a href="/statuses/index">Statuses</a> lookup table and either modify an existing
  status to be the default, or create a new status that is assigned the default status flag.</p>
  </div>
