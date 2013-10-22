<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="toolbar">
<head>
<title><?php echo $title.' - '.PN_SITE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="<?php echo base_url();?>application/views/admin/styles/init.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo base_url();?>/asset/js/jquery.js"></script>
<?php if (isset($css_files)) $this->css($css_files);?>
</head>
<body>
<?php
if($this->session->flashdata('error'))
{
	$error	= $this->session->flashdata('error');
}

if($this->session->flashdata('success'))
{
	$success = $this->session->flashdata('success');
}
