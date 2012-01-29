<h2><?php echo $post['caption']; ?></h2>
<div style="word-wrap: normal">
  <?php echo $post['text']; ?>
</div>
<i><?php echo date('d.m.Y H:i', strtotime($post['edited'])); ?></i>