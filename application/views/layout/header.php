<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Zoho Data Migrator</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/my_style.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.wysiwyg.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/facebox.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/visualize.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/date_input.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>additional_library/jquery-ui/jquery-ui.css" type="text/css"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>additional_library/select2/select2.css" type="text/css"/>
    <!--[if lt IE 8]>
    <link rel="stylesheet" href="<?php echo base_url(); ?>css/ie.css" type="text/css"/><![endif]-->

    <!--[if IE]>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/excanvas.js"></script><![endif]-->
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery_latest.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-migrate-1.2.1.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.filestyle.mini.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.date_input.pack.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.visualize.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.visualize.tooltip.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.select_skin.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/ajaxupload.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.pngfix.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>additional_library/jquery-ui/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>additional_library/select2/select2.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>js/myHelperJS.js"></script>
</head>
<body>
<script type="text/javascript">
    $(function() {
        $('#loadingDiv').slideToggle();
    });
</script>
<div id="hld">
    <div id="loadingDiv">
        <div id="loading">
        </div>
        <div id="loadingImg">
            <img src="<?php echo base_url() ?>images/loading.gif" />
        </div>
    </div>
    <input type="hidden" id="base_url" value="<?php echo base_url() ?>" />
    <div class="wrapper">        <!-- wrapper begins -->
