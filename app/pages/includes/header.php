<?php
/**
 * @author Bilal Cinarli
 * @link http://bcinarli.com
 **/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php html::load_meta(); ?>

	<link rel="stylesheet" href="<?php echo url::styles('styles.css'); ?>">

</head>
<body<?php echo html::$id != '' ? ' id="' . html::$id . '"' : ''; ?><?php echo html::$class != '' ? ' class="' . html::$class . '"' : ''; ?>>
	<div id="wrapper" class="page-wrap">
		<div class="document-main">
			<header class="page-header">
				<h1 class="page-heading"><?php echo html::$title; ?></h1>
			</header>