<?php
/**
 * Run Out Colors Project Page
 * (c)2012 Marc Dannemann
 */
require LIBRARY_DIR . DS . 'tmhTwitter' . DS . 'tmhOAuth.php';
require LIBRARY_DIR . DS . 'tmhTwitter' . DS . 'tmhUtilities.php';
 
function printDbg($message, $newline = true) {
  if(defined('DEBUG') && DEBUG === true)
    echo $message.($newline === true ? '<br />' : '')."\n";
}

function handleException($exception) {
  $exceptionType = get_class($exception);
  echo <<<EOF
<div style="margin: 20px; padding: 5px; border: 3px solid red; background: #FAA">
  <b>{$exceptionType}: </b><br />
  &nbsp;&nbsp;&nbsp;<i>Message: </i>{$exception->getMessage()}<br />
  &nbsp;&nbsp;&nbsp;(thrown in {$exception->getFile()}:{$exception->getLine()})<br /><br />
  <pre style="margin: 0; padding-left: 15px;">
{$exception->getTraceAsString()}
  </pre>
  <i>Execution stopped.</i>
</div>
EOF;
  die;
}
