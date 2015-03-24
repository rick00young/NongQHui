<?php
//GenerateEncrypt
//字符串加解密
class GenerateEncrypt
{
    protected static function keyED($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';

        $txt_len = strlen($txt);
        $key_len = strlen($encrypt_key);

        for ($i = 0; $i < $txt_len; $i++)
        {
            if ($ctr == $key_len) $ctr = 0;

            $tmp .= substr($txt, $i, 1) ^ substr($encrypt_key, $ctr, 1);
            $ctr++;
        }

        return $tmp;
    }

    public static function encrypt($txt, $key)
    {
        if (defined('ENV_ONLINE') && ! ENV_ONLINE)
        {
            return $txt;
        }

        $encrypt_key = md5(((float) date('YmdisH') + rand(100000, 999999)) . rand(10000, 99999));
        $ctr = 0;
        $tmp = '';

        $txt_len = strlen($txt);
        $key_len = $encrypt_key;

        for ($i = 0; $i < $txt_len; $i++)
        {
            if ($ctr == $key_len) $ctr = 0;

            $tmp .= substr($encrypt_key, $ctr, 1) . (substr($txt, $i,1) ^ substr($encrypt_key, $ctr, 1));
            $ctr++;
        }

        return self::base64encode(self::keyED($tmp, $key));
    }

    public static function decrypt($txt, $key)
    {
        if (defined('ENV_ONLINE') && ! ENV_ONLINE)
        {
            return $txt;
        }

        $txt = self::keyED(self::base64decode($txt), $key);
        $tmp = '';

        $txt_len = strlen($txt);

        for ($i = 0; $i < $txt_len; $i++)
        {
            $md5 = substr($txt, $i, 1);
            $i++;
            $tmp .= (substr($txt, $i, 1) ^ $md5);
        }

        return $tmp;
    }

    public static function base64decode($str)
    {
        return base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
    }

    public static function base64encode($str)
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }
}

// debug

/***
$key = 'abc';
$str = '12345678';

$encrypt = GenerateEncrypt::encrypt($str, $key);
echo $encrypt . PHP_EOL;

echo GenerateEncrypt::decrypt($encrypt, $key) . PHP_EOL;
*/
