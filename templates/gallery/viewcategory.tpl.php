<h2><a href="/gallery">Gallery</a> :: <?php echo $category['name']; ?></h2>
<table id="gallery-images">
<?php $image_count = count($images); ?>
<?php for($i=0; $i<$image_count;$i++): ?>
<?php $image = $images[$i]; ?>
<?php $hash = md5($image['file']); ?>
<?php if($i % 3 == 0): ?><tr><?php endif; ?>
<td align="center">
  <a href="/gallery/<?php echo $category['directory']; ?>/<?php echo $image['id']; ?>" class="lightbox" rel="gallery" id="image-<?php echo $hash; ?>">
    <img src="/images/gallery/<?php echo $directory; ?>/small/<?php echo $image['file']; ?>" alt="<?php echo $image['file']; ?>" />
  </a>
  <script>
    $('.lightbox#image-<?php echo $hash; ?>').click(function() {
      var html = $("<img src='/images/gallery/<?php echo $directory; ?>/big/<?php echo $image['file']; ?>' />");
<?php $info = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/gallery/' . $directory . '/big/' . $image['file']); ?>
      $.lightbox(html, { width: <?php echo $info[0]; ?>, height: <?php echo $info[1]+3; ?> });
      return false;
    });
  </script>
</td>
<?php if($i % 3 == 2 || $i == $image_count): ?></tr><?php endif; ?>
<?php endfor; ?>
</table>