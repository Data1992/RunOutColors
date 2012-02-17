<div class="entry">
  <h2><a href="/blog">Blog</a> :: <?php echo $post['caption']; ?></h2>
  <div class="content">
    <?php echo $post['text']; ?>
    <div class="attachments">
      <u>Attachments</u>
      <table id="gallery-images">
<?php $image_count = count($attachments); ?>
<?php for($i=0; $i<$image_count;$i++): ?>
<?php $image = $attachments[$i]; ?>
<?php $hash = md5($image['file']); ?>
<?php if($i % 3 == 0): ?><tr><?php endif; ?>
        <td align="center">
          <a href="/gallery/snapshots/<?php echo $image['id']; ?>" class="lightbox" rel="gallery" id="image-<?php echo $hash; ?>">
            <img src="/images/gallery/snapshots/small/<?php echo $image['file']; ?>" alt="<?php echo $image['file']; ?>" />
          </a>
          <script>
<?php $info = getimagesize($_SERVER['DOCUMENT_ROOT'] . '/images/gallery/snapshots/big/' . $image['file']); ?>
<?php $width = $info[0] < 800 ? 800 : $info[0]; ?>
            $('.lightbox#image-<?php echo $hash; ?>').click(function() {
              var html = $('<div id="lb-content"><center><img src="/images/gallery/snapshots/big/<?php echo $image['file']; ?>" /></center><?php echo ($image['downloadable'] === true ? '<a href="/gallery/snapshots/download/' . $image['id'].'">Download</a>' : ''); ?></div><div class="fb-comments" data-href="<?php echo $_SERVER['HTTP_HOST']; ?>/gallery/snapshots/<?php echo $image['id']; ?>" data-num-posts="5" data-width="<?php echo $width; ?>" data-colorscheme="light"></div>');
              $.lightbox(html, { 
                width: <?php echo $width+20; ?>, 
                height: <?php echo $info[1]+150; ?>,
                onOpen: function() { FB.XFBML.parse(); }
              });
              return false;
            });
          </script>
        </td>
<?php if($i % 3 == 2 || $i == $image_count): ?></tr><?php endif; ?>
<?php endfor; ?>
      </table>
    </div>
  </div>
  <span><i><?php echo date('d.m.Y H:i', strtotime($post['edited'])); ?></i></span>
</div>