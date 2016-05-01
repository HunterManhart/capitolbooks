<?php
  $year = getdate()['year'];
  $mon = getdate()['mon'];
  $day = getdate()['mday'];
?>

<!DOCTYPE html>
<html>
<head>
  <title>WebSys Assignment 2 Team 4</title>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
  <script type='text/javascript'>
    var year = <?php echo $year ?>;
    var month = <?php echo $mon ?>;
    var day = <?php echo $day ?>;
  </script>
  <script src="../../js/jquery-1.11.3.min.js"></script>
  <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
  <script type='text/javascript' src="whenisgood.js"></script>
  <link type='text/css' rel="stylesheet" href="whenisgood.css">
</head>
<body>
  <?php
  
    if(isset($_POST['times'])) {
      echo 'Good';
    }

  ?>
  <h1>Choose any times below that you are available</h1>
  <div id="timegrid"></div>
  <button id="submit">Submit</button>
</body>
</html>
