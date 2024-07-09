<?php include('config.php'); ?>
<?php include('header.php') ?>

<?php
  $url = isset($_GET['url']) ? $_GET['url'] : 'home';
  if (file_exists('pages/'.$url.'.php')) {
    include('pages/'.$url.'.php');
  } else {
    include('pages/home.php');
  }
?>

<?php include('footer.php') ?>