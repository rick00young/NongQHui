<?php
$dmp1 = array(
    /*
    'rUrl' => 'http://mp1.dev.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.dev.com/',
    'pwUrl' => 'http://mp1.dev.com/',//需要权限的写入接口
    */
    'rUrl' => 'http://agro.local.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://agro.local.com/',
    'pwUrl' => 'http://agro.local.com/',//需要权限的写入接口
);
$devConfig = array(
    '0' => $dmp1,
    '1' => $dmp1,
    '2' => $dmp1,
    '3' => $dmp1,
    '4' => $dmp1,
    '5' => $dmp1,
    '6' => $dmp1,
    '7' => $dmp1,
    '8' => $dmp1,
    '9' => $dmp1,
    'a' => $dmp1,
    'b' => $dmp1,
    'c' => $dmp1,
    'd' => $dmp1,
    'e' => $dmp1,
    'f' => $dmp1
);

$tmp1 = array(
    'rUrl' => 'http://mp1.qa.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.qa.com/',
    'pwUrl' => 'http://mp1.qa.com/',//需要权限的写入接口
);
$testConfig = array(
    '0' => $tmp1,
    '1' => $tmp1,
    '2' => $tmp1,
    '3' => $tmp1,
    '4' => $tmp1,
    '5' => $tmp1,
    '6' => $tmp1,
    '7' => $tmp1,
    '8' => $tmp1,
    '9' => $tmp1,
    'a' => $tmp1,
    'b' => $tmp1,
    'c' => $tmp1,
    'd' => $tmp1,
    'e' => $tmp1,
    'f' => $tmp1
);

$omp1 = array(
    'rUrl' => 'http://mp1.agrovips.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.agrovips.com/',
    'pwUrl' => 'http://mp1.agrovips.com/',//需要权限的写入接口
);
$omp2 = array(
    'rUrl' => 'http://mp2.agrovips.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.agrovips.com/',
    'pwUrl' => 'http://mp1.agrovips.com/',//需要权限的写入接口
);
$omp3 = array(
    'rUrl' => 'http://mp3.agrovips.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.agrovips.com/',
    'pwUrl' => 'http://mp1.agrovips.com/',//需要权限的写入接口
);
$omp4 = array(
    'rUrl' => 'http://mp4.agrovips.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.agrovips.com/',
    'pwUrl' => 'http://mp1.agrovips.com/',//需要权限的写入接口
);
$omp5 = array(
    'rUrl' => 'http://mp5.agrovips.com/'.IMAGE_SOURCE,
    'wUrl' => 'http://mp1.agrovips.com/',
    'pwUrl' => 'http://mp1.agrovips.com/',//需要权限的写入接口
);
$onlineConfig = array(
    '0' => $omp1,
    '1' => $omp1,
    '2' => $omp1,
    '3' => $omp1,
    '4' => $omp2,
    '5' => $omp2,
    '6' => $omp2,
    '7' => $omp3,
    '8' => $omp3,
    '9' => $omp3,
    'a' => $omp4,
    'b' => $omp4,
    'c' => $omp4,
    'd' => $omp5,
    'e' => $omp5,
    'f' => $omp5,
);

return array(
    'dev'=>$devConfig,
    'test'=>$testConfig,
    'online'=>$onlineConfig,
);
