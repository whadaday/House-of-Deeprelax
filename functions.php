<?php
  
  foreach (glob( __DIR__ . '/functions/theme/*') as $filename):
    require_once($filename);
  endforeach;