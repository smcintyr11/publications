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
    <?php
      foreach ($versions as $version) {
        echo ('<tr>');
        echo ('<td>' . $version['Updated']);
        $now = time();
        $updatedTime = strtotime($version['Updated']);
        $difference = $now - $updatedTime;
        $days = round ($difference / (60 * 60 * 24));
        if ($days == 0) {
          echo('&nbsp;&nbsp;<span class="badge badge-primary">Today</span>');
        } elseif ($days == 1) {
          echo('&nbsp;&nbsp;<span class="badge badge-primary">Yesterday</span>');
        } elseif ($days < 7) {
          echo('&nbsp;&nbsp;<span class="badge badge-primary">' . $days . ' days ago</span>');
        }
        echo ('</td>');
        echo ('<td>' . $version['Version'] . '</td>');
        echo ('<td>' . $version['Description'] . '</td>');
        echo ('</tr>');
      }
    ?>
  </table>
</div>
