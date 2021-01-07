<?php 
session_start();
if (!isset($_SESSION['id']) AND empty($_SESSION['id'])) {
  header("Location: login.php");
}

?>
<?php include 'view/pages/head.php'; ?>

<div class="wrapper">

  <?php include 'view/pages/header.php'; ?>
  <!-- Left side column. contains the logo and sidebar -->
  <?php include 'view/pages/aside.php'; ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php include 'view/pages/principal.php';?>

    <!-- Main content -->
    <!-- <section class="content">
      
    </section> -->
  </div>
  <!-- /.content-wrapper -->
  <?php include 'view/pages/footer.php'; ?>
</div>
<!-- ./wrapper -->

<?php include 'view/pages/scripts.php'; ?>