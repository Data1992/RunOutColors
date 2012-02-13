<h2><a href="/gallery">Gallery</a> :: <a href="/gallery/<?php echo $result['directory']; ?>"><?php echo $result['name']; ?></a> :: Bild ansehen </h2>
<div id="gallery-image-full">
  <center><img src="/images/gallery/<?php echo $result['directory'] . DS . 'big' . DS . $result['file']; ?>" /></center>
<?php if($result['downloadable'] === true): ?>
  <a href="/gallery/<?php echo $result['directory'] . DS . 'download' . DS . $result['id']; ?>">Download</a>
<?php endif; ?><br />
  <center><div class="fb-comments" data-href="<?php echo $_SERVER['HTTP_HOST']; ?>/gallery/<?php echo $result['directory']; ?>/<?php echo $result['id']; ?>" data-num-posts="5" data-width="700" data-colorscheme="dark"></div></center>
</div>