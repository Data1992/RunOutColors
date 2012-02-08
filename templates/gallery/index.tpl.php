<h2>Gallery</h2>
<?php foreach($galleries as $gallery) : ?>
<div class="gallery-category">
<?php $gallery_thumb = (empty($gallery['front_image']) 
                       ? 'images/camera.gif' 
                       : 'images/gallery/'.$gallery['directory'].'/smallest/'.$gallery['front_image']); ?>
  <img src="<?php echo $gallery_thumb; ?>" />
  <div class="title"><a href="./gallery/<?php echo $gallery['directory']; ?>"><?php echo $gallery['name']; ?></a></div>
  <div class="descr">
    <?php echo $gallery['description']; ?>
  </div>
</div>
<?php endforeach; ?>

