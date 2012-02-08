<script>$(document).ready(function() { $('.lb').lightBox(); });</script>
<h2><a href="/gallery">Gallery</a> :: <?php echo $category['name']; ?></h2>
<table id="gallery-images">
<?php $image_count = count($images); ?>
<?php for($i=0; $i<$image_count;$i++): ?>
<?php $image = $images[$i]; ?>
<?php if($i % 3 == 0): ?><tr><?php endif; ?>
<td align="center"><a href="/images/gallery/<?php echo $category['directory']; ?>/<?php echo $image['file']; ?>" class="lb">
  <img src="/images/gallery/<?php echo $directory; ?>/small/<?php echo $image['file']; ?>" alt="<?php echo $image['file']; ?>" />
</a></td>
<?php if($i % 3 == 2 || $i == $image_count): ?></tr><?php endif; ?>
<?php endfor; ?>
</table>