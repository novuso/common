<?php $this->extends("project:layout.html.php"); ?>

<?php $this->setContent("title", "Index") ?>

<?php $this->startBlock("content"); ?>
<div id="content" class="content">
    <h2>Index</h2>
    <p><?= $this->escape($content); ?></p>
</div><!-- #content -->
<?php $this->endBlock(); ?>
