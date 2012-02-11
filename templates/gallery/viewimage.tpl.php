<h2><a href="/gallery">Gallery</a> :: <a href="/gallery/<?php echo $result['directory']; ?>"><?php echo $result['name']; ?></a> :: Bild ansehen </h2>
<div id="gallery-image-full">
  <img src="/images/gallery/<?php echo $result['directory'] . DS . $result['file']; ?>" />
  <a href="/images/gallery/<?php echo $result['directory'] . DS . $result['file']; ?>">Download</a>
  (Rechtsklick &rarr; "Ziel speichern unter")
</div>