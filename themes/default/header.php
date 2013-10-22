<!DOCTYPE HTML>
<html>
<title><?php echo isset($title) ? $title : '首页';?> | ProNoz</title>
<meta charset='utf-8'>
<?php include_once("analyticstracking.php");?>
<script type="text/javascript" src="<?php echo base_url();?>/asset/js/jquery.js"></script>
<?php if (isset($css_files)) $this->css($css_files);?>
<body>
<div class="wrapper">
    <div class="header">
        <h1>
            <a href="/">ProNoz</a>
        </h1>
        <h2>不妒人有，不笑人无</h2>
    </div>
