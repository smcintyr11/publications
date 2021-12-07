<div class="container my-3 py-3">
  <h1>Welcome to the CanmetENERGY Devon Publications Tracker</h1>

  <p>This system contains a database of all publications created by CanmetENERGY
    Devon.  It contains information such as authors, reviewers, publishers,
    related project codes and so on.<p>
  <p>To access the publications, click on <strong>Publications</strong> in <thead>
    menu bar.</p>
  </thead>
  <p>Please send any feedback to
    <a href="mailto:scott.mcIntyre@nrcan-rncan.gc.ca">Scott McIntyre</a></p>
  <br />
  <table class="table">
    <thead>
      <tr>
        <th scope="col">Updated On</th>
        <th scope="col">Version #</th>
        <th scope="col">Details</th>
      </tr>
    </thead>
    <tr>
      <td>
      <?php
        echo ($updated);
        $now = time();
        $updatedTime = strtotime($updated);
        $difference = $now - $updatedTime;
        $days = round ($difference / (60 * 60 * 24));
        if ($days < 7) {
          echo('&nbsp;&nbsp;<span class="badge badge-primary">Updated</span>');
        }
       ?>
      </td>
      <td><?= $version ?></td>
      <td><?= $description ?></td>
    </tr>
  </table>
</div>
