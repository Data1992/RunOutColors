<h2>Contact</h2>
<?php if($done !== true): ?>
<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
  if (field.value.length > maxlimit) // if too long...trim it!
    field.value = field.value.substring(0, maxlimit);
  // otherwise, update 'characters left' counter
  else
    cntfield.value = maxlimit - field.value.length;
}
</script>
<form method="post" id="contact-form">
<?php if(isset($error)): ?>
  <div class="error-box">
    <?php echo $error; ?>
  </div>
<?php endif; ?>

  <label for="from-name"><i>Name:</i> <span class="required">*</span></label><br />
  <input type="text" name="from-name" value="<?php echo (isset($data['from-name']) ? $data['from-name'] : ''); ?>" />
  <label for="from-email"><i>Email:</i> <span class="required">*</span></label><br />
  <input type="text" name="from-email" value="<?php echo (isset($data['from-email']) ? $data['from-email'] : ''); ?>" />
  <label for="message"><i>Text:</i></label> <span class="required">*</span><br />
  <textarea id="message" name="message" 
    onKeyUp="textCounter(this.form.message, this.form.chars, 500);"
    onKeyDown="textCounter(this.form.message, this.form.chars, 500);"></textarea>
  <input type="text" readonly="readonly" value="500" name="chars" size="10" /> Zeichen verbleibend<br />
  <i>Sicherheitscode:</i> <span class="required">*</span><br />
  <?php echo recaptcha_get_html($recaptcha_key); ?>
  <input type="submit" name="send-form" value="Absenden" />
</form>
<?php else: ?>
<p style="margin: 10px 15px;">
  Nachricht erfolgreich versendet. Vielen Dank!
</p>
<?php endif; ?>