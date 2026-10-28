<?php

/** O nome do banco de dados*/
// define('DB_NAME', 'wda_crud');
const DB_NAME = "BD";   

define('DB_USER', 'root');

define('DB_PASSWORD', '');

define('DB_HOST', 'localhost');

/** caminho absoluto para a pasta do sistema **/
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/'); // não pode const aqui dentro
	
/** caminho no server para o sistema **/
if ( !defined('BASEURL') )
	define('BASEURL', '/controle_de_acesso1/'); //raiz do projeto. trocar para "/" quando hospedado
	
/** caminho do arquivo de banco de dados **/
if ( !defined('DBAPI') )
	define("DBAPI", ABSPATH . 'database.php');

	
/** Template do cabeçaho e do rodapé das páginas **/ 
const HEADER_TEMPLATE = ABSPATH . 'inc/header.php';
const FOOTER_TEMPLATE = ABSPATH . 'inc/footer.php';