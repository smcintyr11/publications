<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>autocomplete demo</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.12.4.js"></script>
  <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>


  <?php

  $url = 'http://localhost:8080/publications/edit/1/1';
  $t = parse_url($url);
  print_r($t);
  echo ("<br />");
  $newUrl = $t['scheme'] . "://" . $t['host'] . ':' . $t['port'] . '/';
  echo ($newUrl);
  ?>

</body>
</html>
