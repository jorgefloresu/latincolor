<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="<?php echo base_url('materialize/admin/css/materialize.min.css'); ?>" media="screen, projection" />
    <!-- <link rel="stylesheet" href="<?php echo base_url('materialize/css/materialize.min.css'); ?>" media="screen, projection"/> -->
    <link rel="stylesheet" href="<?php echo base_url('css/materialize-plus.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('css/perfect-scrollbar.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('css/admin.css'); ?>" />
    <link rel="stylesheet" href="<?php echo base_url('css/datatables.min.css'); ?>" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- <link href="http://cdn.datatables.net/1.10.6/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="screen,projection"> -->


    <title>Latincol Panel</title>

</head>

<body>
    <header id="header" class="page-topbar">
        <!-- start header nav-->
        <?php $this->load->view('templates/admin_header')?>
        <!-- end header nav-->
    </header>


    <div id="main">
        <!-- START WRAPPER -->
        <div class="wrapper">

            <?php $this->load->view('templates/admin_sidebar')?>

            <section id="content">

                <!--breadcrumbs start-->
                <!--<div id="breadcrumbs-wrapper" class=" grey lighten-3">
		          <div class="container">
		            <div class="row">
		              <div class="col s12 m12 l12">
		                <h5 class="breadcrumbs-title">Basic Tables</h5>
		                <ol class="breadcrumb">
		                    <li><a href="index.html">Dashboard</a></li>
		                    <li><a href="#">Tables</a></li>
		                    <li class="active">Basic Tables</li>
		                </ol>
		              </div>
		            </div>
		          </div>
		        </div> -->
                <!--breadcrumbs end-->

                <div class="container">
                    <div class="section">

                        <?php echo $main_content?>

                    </div>
                </div>
            </section>
        </div>
        
        <?php $this->load->view('pages/plan_form')?>

    </div>

    <script type="text/javascript">
    var userData = JSON.parse('<?php echo $user_info?>');
    const ROOT = '<?php echo base_url()?>';
    </script>

    <script src="<?php echo base_url('js/jquery-3.3.1.min.js');?>"></script>
    <script src="<?php echo base_url('materialize/js/materialize.min.js');?>"></script>
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
			<script src="http://localhost:8888/CI/materialize/js/materialize.min.js"></script> -->
    <script src="<?php echo base_url('js/js.storage.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/perfect-scrollbar.min.js'); ?>"></script>
    <!-- <script src="<?php echo base_url('js/jquery.dataTables.min.js'); ?>"></script> -->
    <script src="<?php echo base_url('js/moment.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/datatables.min.js'); ?>"></script>
    <script src="<?php echo base_url('js/datetime-moment.js'); ?>"></script>
    <script src="<?php echo base_url('js/jquery.validate.js'); ?>"></script>
    <script src="<?php echo base_url('js/lib.functions.js');?>"></script>
    <script src="<?php echo base_url('js/auth.functions.js');?>"></script>
    <script src="<?php echo base_url('js/login.functions.js');?>"></script>
    <script src="<?php echo base_url('js/admin.functions.js'); ?>"></script>
</body>

</html>