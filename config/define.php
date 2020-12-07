<?php
	define('SCHEME'				, (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http') . '://');
	// Local host
	define('HOST_NAME'		    , $_SERVER['HTTP_HOST'] . '/LEP');    		//
	define('BASE_NAME'			, SCHEME . HOST_NAME. DS);               		    // http://localhost
	define('PATH_URL'			, SCHEME. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	
	define('PATH_APPLICATION'	, PATH_ROOT . DS . 'mvc' . DS);     	// Đường dẫn đến thư mục application
	define('PATH_CONFIG'		, PATH_ROOT . DS . 'configs' . DS);     	    // Đường dẫn đến thư mục configs
	define('PATH_VENDOR'		, PATH_ROOT . DS . 'vendor' . DS . 'autoload.php'); 	// Đường dẫn đến thư mục Vendor
	define('PATH_PUBLIC'		, BASE_NAME . 'public' . DS); 		    // Đường dẫn đến thư mục public
	define('PATH_VIEW'			, PATH_APPLICATION . 'views' . DS); 		    // Đường dẫn đến thư mục public

	define('TB_PREFIX'     		, '');                                   		// Tiền tố của bảng
	define('RD_LIMIT'     		, 24);											// Số dòng sql lấy ra tối đa
	define('DB_HOST'	        ,'127.0.0.1');									// Đường dẫn host sql
	define('DB_PORT'	        ,'3306');									// Đường dẫn host sql
	define('DB_USER'	        ,'root');										// User sql
	define('DB_PWD'		        ,'');											// Password sql
	define('DB_NAME'	        ,'lep');							// Tên database

	define('FILE_EXTENSION'	    , 'jpeg,jpg,png,gif,csv,txt,pdf');							// Tên database
	
?>