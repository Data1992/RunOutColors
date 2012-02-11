<h2><a href="/gallery">Gallery</a> :: <a href="/gallery/<?php echo $result['directory']; ?>"><?php echo $result['name']; ?></a> :: Bild ansehen </h2>
<div id="gallery-image-full">
  <img src="/images/gallery/<?php echo $result['directory'] . DS . $result['file']; ?>" />
  <a href="/images/gallery/<?php echo $result['directory'] . DS . $result['file']; ?>">Download</a>
  (Rechtsklick &rarr; "Ziel speichern unter")
</div>
<div class="fb-comments" data-href="<?php echo $_SERVER['HTTP_HOST']; ?>/gallery/<?php echo $result['directory']; ?>/<?php echo $result['id']; ?>" data-num-posts="5" data-width="700" data-colorscheme="dark"></div>