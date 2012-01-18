<div class="entry">
  <?php echo html_entity_decode($message); ?>
</div>
<span>zuletzt bearbeitet: <i><?php echo strftime('%d. %B %Y, %H:%M', strtotime($timestamp)); ?></i></span>
<?php echo getenv('LANG'); ?>