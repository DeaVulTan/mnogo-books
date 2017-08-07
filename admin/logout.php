<?php
  session_start();
  session_destroy();
//  header("Location: index.php");
?>

<script>
  location.replace('index.php'); 
</script>
