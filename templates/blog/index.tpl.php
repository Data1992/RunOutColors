<h2>Blog</h2>
<table id="pagination">
<tr>
  <td align="left" width="40%">
<?php if($prev_page != null): ?>
    <a href="/blog/page/<?php echo $prev_page; ?>">prev</a>
<?php else: ?>&nbsp;<?php endif; ?>
  </td>
  <td align="center">- <span><?php echo $current_page; ?></span> -</td>
  <td align="right" width="40%">
<?php if($next_page != null): ?>
  <a href="/blog/page/<?php echo $next_page; ?>">next</a>
<?php else: ?>&nbsp;<?php endif; ?>
  </td>
</tr>
</table>

<?php foreach($posts as $post): ?>
<div class="entry">
  <h3><a href="/blog/article/<?php echo $post['id']; ?>"><?php echo $post['caption']; ?></a></h2>
  <div class="content">
<?php if($post['image_id'] != null): ?>
    <img class="preview" src="/images/gallery/snapshots/small/<?php echo $post['image_file']; ?>" />
<?php endif; ?>
    <?php echo $post['text']; ?>
  </div>
  <span><i><?php echo date('d.m.Y H:i', strtotime($post['edited'])); ?></i></span>
</div>
<?php endforeach; ?>

<table id="pagination">
<tr>
  <td align="left" width="40%">
<?php if($prev_page != null): ?>
    <a href="/blog/page/<?php echo $prev_page; ?>">prev</a>
<?php else: ?>&nbsp;<?php endif; ?>
  </td>
  <td align="center">- <span><?php echo $current_page; ?></span> -</td>
  <td align="right" width="40%">
<?php if($next_page != null): ?>
  <a href="/blog/page/<?php echo $next_page; ?>">next</a>
<?php else: ?>&nbsp;<?php endif; ?>
  </td>
</tr>
</table>