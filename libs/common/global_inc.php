<?php
/**************************************************************************
 * global_inc.php -  One Line summary description
 * Copyright (C) 2008 by Aaron Mondelblatt All Right Reserved
 *
 *
 *
 *Revision History
 *
 *  Date        	Version     BY     			Purpose of Revision
 * ---------	    --------	--------       	--------
 * Feb 28, 2008		    1.01a 		aaron			Initial Draft
 *
 **************************************************************************/
ini_set('display_errors','on');
ini_set('display_startup_errors','on');
//ini_set('include_path',get_include_path() . ':/hsphere/local/home/A878499/weightloss.mondelblatt.com/' );
ini_set('include_path',get_include_path() . ';D:/weightloss/weightloss' );
ini_set('memory_limit','64M');
define('WEB_BASE_COMMON','http://'.$_SERVER['SERVER_NAME'] . '/');
//define('FUNCTION_LIBRARY_POSTGRES_DB_NAME', 'A878499_weightloss');
define('FUNCTION_LIBRARY_POSTGRES_DB_NAME', 'A878499_weightlistdev');
define('FUNCTION_LIBRARY_POSTGRES_USER', 'A878499_wed');
define('FUNCTION_LIBRARY_POSTGRESS_PASSWORD', 'Splatt66');
define('FUNCTION_LIBRARY_POSTGRESS_HOST', 'pgsql1101.ixwebhosting.com');
session_start();
require_once('libs/functions/html_functions.php');
require_once('libs/functions/login_funcs.php');
?>