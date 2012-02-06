<h2>Gallery :: <?php echo $category; ?></h2>
<table id="gallery-images">
<?php $image_count = count($images); ?>
<?php for($i=0; $i<$image_count;$i++): ?>
<?php $image = $images[$i]; ?>
<?php if($i % 3 == 0): ?><tr><?php endif; ?>
<td><img src="/images/gallery/<?php echo $directory . DS . $image['file']; ?>" /></td>
<?php if($i % 3 == 2 || $i == $image_count): ?></tr><?php endif; ?>
<?php endfor; ?>
</table>