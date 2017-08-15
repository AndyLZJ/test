<?php
return array(


//局域网数据库
    /*'DB_TYPE'               =>  'mysqli',                 //数据库类型
    'DB_HOST'               =>  '192.168.1.201',     // 服务器地址
    'DB_NAME'               =>  'think',     // 数据库名称
    'DB_USER'               =>  'phplnmp',     // 用户名
    'DB_PWD'                =>  '111111',      // 密码
    'DB_PORT'               =>  '3306',     // 端口号
    'DB_PREFIX'             =>  'think_',   // 表前缀*/


//外网数据库
    /*'DB_TYPE'               =>  'mysqli',                 // 数据库类型
    'DB_HOST'               =>  '120.77.57.26',     // 服务器地址
    'DB_NAME'               =>  'think',     // 数据库名称
    'DB_USER'               =>  'php',     // 用户名
    'DB_PWD'                =>  'Dysoft@@',      // 密码
    'DB_PORT'               =>  '3306',     // 端口号
    'DB_PREFIX'             =>  'think_',   // 表前缀*/
    
 'DB_TYPE' => 'Oracle', // 数据库类型
'DB_HOST' => '120.77.57.26', // 服务器地址
             'DB_NAME' => 'orcl', // 数据库名
//'DB_NAME' => '(DEscriptION=(ADDRESS=(PROTOCOL=TCP)(HOST=120.77.57.26)(PORT=1521))(CONNECT_DATA=(SID=orcl)))',//
	'DB_USER' => 'scott', // 用户名
	'DB_PWD' => 'Dysoft123', // 密码
	'DB_PORT' => '1521', // 端口
	'DB_CASE_LOWER'=>true,//把oracle的结果字段名都转换成小写
	/*'DB_PARAMS'          => array(  
//  	'persist' => true, //注意，这一个必须写  
    	\PDO::ATTR_CASE  => \PDO::CASE_LOWER,
	),*/
	'DB_PREFIX' => 'think_',
	'DB_SEQUENCE_PREFIX' => 'seq_',//序列名前缀，每个表对应的序列应为: 序列名前缀+表名
 	'DB_TRIGGER_PREFIX' => 'tig_',//触发器名前缀
 	
);