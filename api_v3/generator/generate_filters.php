<?php 
error_reporting(E_ALL);
ini_set( "memory_limit","512M" );

chdir(__DIR__);

//bootstrap connects the generator to the rest of Borhan system
require_once(__DIR__ . "/bootstrap.php");

BorhanLog::info("Generating API filters");
$xmlGenerator = new FiltersGenerator();
$xmlGenerator->generate();

BorhanLog::info("Filters generated");
