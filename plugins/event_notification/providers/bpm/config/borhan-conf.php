<?php
// This file generated by Propel  convert-conf target
// from XML runtime conf file C:\opt\borhan\app\alpha\config\runtime-conf.xml
return array (
  'datasources' => 
  array (
    'borhan' => 
    array (
      'adapter' => 'mysql',
      'connection' => 
      array (
        'phptype' => 'mysql',
        'database' => 'borhan',
        'hostspec' => 'localhost',
        'username' => 'root',
        'password' => 'root',
      ),
    ),
    'default' => 'borhan',
  ),
  'log' => 
  array (
    'ident' => 'borhan',
    'level' => '7',
  ),
  'generator_version' => '1.4.2',
  'classmap' => 
  array (
    'BusinessProcessServerTableMap' => 'lib/model/map/BusinessProcessServerTableMap.php',
    'BusinessProcessServerPeer' => 'lib/model/BusinessProcessServerPeer.php',
    'BusinessProcessServer' => 'lib/model/BusinessProcessServer.php',
  ),
);