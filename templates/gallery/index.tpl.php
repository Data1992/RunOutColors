<h2>Gallery</h2>
<?php foreach($galleries as $gallery) : ?>
<div class="gallery-category">
  <img src="<?php echo (empty($gallery['front_image']) ? 'images/camera.gif' : $gallery['front_image']); ?>" />
  <div class="title"><a href="./gallery/<?php echo $gallery['directory']; ?>"><?php echo $gallery['name']; ?></a></div>
  <div class="descr">
    <?php echo $gallery['description']; ?>
  </div>
</div>
<?php endforeach; ?>

