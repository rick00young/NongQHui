<?php
define('SMS_PLATFORM_SINA',1);//新浪
define('SMS_PLATFORM_QQ', 2);//QQ
define('SMS_PLATFORM_WEIXIN', 3);//微信

define('USER_GENDER_MAN', 1);//男
define('USER_GENDER_WOMAN', 2);//女
define('USER_GENDER_UNKNOWN',3);//


define('PICKING',1);//采摘
define('EGG', 2);//柴鸡蛋
define('AGRITAINMENT',3);//农家乐

define('REGISTER_MODEL_NORMAL',1);//普通用户
define('REGISTER_MODEL_BUSINESS',2);//商家
define('REGISTER_MODEL_UNKNOWN',0);


define('C_FE_GUEST_AVATAR_68x68', '/static/default/people68x68.jpg');

////  根域名
define('PERMIT_COOKIE','aapp');

//pin
define('PIN', 'aa_pin');

//code encrypt
define('ID_SIMPLE_KEY', 'abcdef');


/*****************************image******************************/
//图片相关
define('TYPE_NO_DEFORM', 1);
define('TYPE_NO_BLANK', 2);
define('TYPE_FIX_WIDTH', 3);
define('TYPE_SPECIAL', 4);

define('C_IMAGESERVER_FOLDER_HOME', 'home');

define('RAW_FOLDER', 'raw/');
//define('QR_FOLDER', 'qrcode/');
//define('META_FOLDER', 'meta/');//当前用处：如果图片在该目录存在，缩略图都不会打水印
//define('WATER_FOLDER', 'water/');

define('PRERAW_FOLDER', 'preraw/');
define('THUMB_FOLDER', 'thb/');
define('THUMB_NO_DEFORM_FOLDER', 'thb/');
define('THUMB_NO_BLANK_FOLDER', 'thb2/');
define('THUMB_FIX_WIDTH_FOLDER', 'thb3/');
define('TYPE_SPECIAL_FOLDER', 'thb4/');
define('IMAGE_FOLDER', 'images/');

define('IMAGE_SOURCE', 'source/images/');

/* 图片服务器 3000 - 3999*/
//error code
define('S_OK', 0);
define('E_FAIL', 3001);
//upload result code
define('E_NOT_SUPPORT_FORMAT', 3002);
define('E_FILE_TOO_LARGE', 3003);
define('E_FILE_IS_NOT_UPLOADED', 3004);
define('E_MOVE_FILE_FAILED', 3005);
define('E_UPDATE_FAILED', 3006);
define('E_INSERT_FAILED', 3007);
define('E_NO_FILE_RECEIVED', 3008);
define('E_MD5_INVALID', 3101);
define('E_FILE_NOT_EXIST', 3102);
define('E_PARAM_INVALID', 3103);
define('E_CONVERT_FAILED', 3104);
define('E_CREATE_TMP_FAILED', 3104);

define('MAX_FILE_SIZE', 20971520);//20M

/** 超级用户 id */
define('SUPERVISOR_UID', 1);

define('ENV_ONLINE', ! isset($_SERVER['ENVIRONMENT']) || 'online' == $_SERVER['ENVIRONMENT'] ? 1 : 0);

//这xie文件夹必须设置php的可写权限
define('IMG_PUBLIC', APPLICATION_PATH . '/../public/');
define('IMG_RAW', '/data/'.RAW_FOLDER);
define('IMG_ROOT', IMG_PUBLIC . IMAGE_FOLDER);//for imageserver
define('IMG_WATER', IMG_ROOT . 'water/');
define('META_FOLDER', 'meta/');//当前用处：如果图片在该目录存在，缩略图都不会打水印
//define('IMG_360', IMG_ROOT.W360_FOLDER);
//define('IMG_PRERAW', IMG_ROOT.PRERAW_FOLDER);
//define('IMG_THUMB', IMG_ROOT.THUMB_FOLDER);
//define('IMG_QRCODE', IMG_ROOT.QR_FOLDER);
define('IMG_META', IMG_ROOT.META_FOLDER);//当前用处：如果图片在该目录存在，缩略图都不会打水印
//define('IMG_DELETE', WEBROOT.'data/');

//image server
define('C_SET_WATER_2', 10002); //打水印的类型，只在左上和右下打水印，仅特殊时刻开启，平时俱关闭
define('C_SET_WATER', 1);//打水印的类型，左上、中间和右下都打水印
define('C_NOT_SET_WATER', 0);
/*****************************image******************************/

/** 微信支付 {{{ */
define('SSLCERT_ABS_PATH', APPLICATION_PATH . '/application/library/WxPayPubHelper/cacert/apiclient_cert.pem');
define('SSLKEY_ABS_PATH',  APPLICATION_PATH . '/application/library/WxPayPubHelper/cacert/apiclient_key.pem');
/** }}}*/
