<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php if (have_active_user()) {?>
      <meta name="token" content="<?php echo base64_encode(json_encode(active_user())); ?>">
    <?php } ?>
    <title><?php echo $title; ?> - User Admin</title>
<link rel="icon" href="<?php echo base_url()?>assets/img/civue.png">
<link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/style.css">

<script src="<?php echo base_url()?>assets/js/vue.min.js"></script>
<script src="<?php echo base_url()?>assets/js/axios.min.js"></script>
<script src="<?php echo base_url()?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url()?>assets/js/uiv.min.js"></script>
<script src="<?php echo base_url()?>assets/js/base64.js"></script>
<script src="<?php echo base_url()?>assets/js/app/utils.js"></script>
  
</head>
<body class="bg-light">
  <div class="container">
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a href="<?php echo base_url();?>" class="navbar-brand navbar-link">User Admin</a>
        <button data-toggle="collapse" data-target="#navcol-1" class="navbar-toggle collapsed">
          <span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
          <span class="icon-bar"></span><span class="icon-bar"></span></button>
      </div>
      <div class="collapse navbar-collapse" id="navcol-1">
        <ul class="nav navbar-nav">
          <li role="presentation"><a href="<?php echo base_url()?>">Users </a></li>
          <li role="presentation"><a href="<?php echo base_url()?>group">Groups </a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li role="presentation"><a href="<?php echo base_url().'auth/logout'; ?>">Logout</a></li>
        </ul>
      </div>
    </div>
</nav>