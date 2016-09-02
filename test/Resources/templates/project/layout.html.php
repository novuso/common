<?php $this->extends('master.html.php'); ?>

<?php $this->setContent('title', 'Project '.$this->getContent('title')); ?>

<?php $this->startBlock('body'); ?>
<?php $this->startBlock('openWrapper'); ?>
<div id="wrapper" class="wrapper">
<?php $this->endBlock(); ?>
<?php $this->startBlock('navigation'); ?>
<nav id="navigation" class="navigation" role="navigation">
    <ul>
        <li><a href="/home">Home</a></li>
    </ul>
</nav><!-- #navigation -->
<?php $this->endBlock(); ?>
<?php $this->startBlock('header'); ?>
<header id="header" class="header" role="banner">
    <h1>Project</h1>
</header><!-- #header -->
<?php $this->endBlock(); ?>
<?php $this->startBlock('content'); ?>
<?php $this->endBlock(); ?>
<?php $this->startBlock('footer'); ?>
<footer id="footer" class="footer" role="contentinfo">
    <p>&copy; 2016 Novuso.</p>
</footer><!-- #footer -->
<?php $this->endBlock(); ?>
<?php $this->startBlock('closeWrapper'); ?>
</div><!-- #wrapper -->
<?php $this->endBlock(); ?>
<?php $this->endBlock(); ?>

<?php $this->startBlock('headLink'); ?>
<link rel="stylesheet" type="text/css" href="/app_assets/css/project.css">
<?php $this->endBlock(); ?>

<?php $this->startBlock('footScript'); ?>
<script type="text/javascript" src="/app_assets/js/project.js"></script>
<?php $this->endBlock(); ?>
