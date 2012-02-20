<h2>Gallery</h2>
<table id="gallery-categories">
<?php $gallery_count = count($galleries); ?>
<?php for($i=0; $i<$gallery_count;$i++): ?>
<?php $gallery = $galleries[$i]; ?>
<?php if($i % 3 == 0): ?><tr><?php endif; ?>
<td align="center" width="25%">
<?php
if(basename($gallery['front_image']) == $gallery['front_image']) {
  $gallery_thumb = (empty($gallery['front_image']) || 
                   !file_exists(GALLERY_PATH.'/'.$gallery['directory'].'/small/'.$gallery['front_image'])
                   ? 'images/camera.gif' 
                   : 'images/gallery/'.$gallery['directory'].'/small/'.$gallery['front_image']);
} else $gallery_thumb = $gallery['front_image'];
?>            
  <a href="./gallery/<?php echo $gallery['directory']; ?>">
    <div class="title"><?php echo $gallery['name']; ?></div>
    <img src="<?php echo $gallery_thumb; ?>" width="120" height="100" />
  </a>
  <div class="descr">
    <?php echo substr($gallery['description'], 0, 100).(strlen($gallery['description']) > 100 ? '...' : ''); ?>
  </div>
</td>
<?php if($i % 3 == 2 || $i == $gallery_count): ?></tr><?php endif; ?>
<?php endfor; ?>
</table>