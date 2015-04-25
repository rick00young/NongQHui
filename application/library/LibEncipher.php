<?php
/**
 * zq-encipher双向加密解密
 * 密钥在config的global.authkey
 * @version 2014.04.16
 */
class LibEncipher
{
    private static $expire = 0;
    private static $key = '';
    private static $delimiter = '~#~';
    private static $sublen = 8;
    private static $prelen = 6;
    private static $md5len = 32;
    private static $chrArr = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
                                    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
                                    '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                             );
    static public  function encryption($tex, $key = null, $expire = 0)
    {
        $key = $key ? $key : Config::configForKeyPath('passport.authkey');
        $expire = $expire ? $expire : self::$expire;

        $keyb = '';
        $length = count(self::$chrArr);
        for($i = 0; $i < self::$prelen; $i ++)
            $keyb .= self::$chrArr[rand() % $length];
        $randKey = md5($keyb . $key);

        $reslutstr = "";
        $tex .= self::$delimiter . sprintf('%010d', $expire ? $expire + time() : 0) . self::$delimiter;
        $texlen = strlen($tex);
        for ($i = 0; $i < $texlen; $i++)
            $reslutstr .= $tex{$i} ^ $randKey{$i % self::$md5len};

        $reslutstr = trim($keyb . base64_encode($reslutstr), "==");
        $reslutstr = substr(md5($reslutstr), 0, self::$sublen) . $reslutstr;
        return $reslutstr;
    }

    static public function decryption($tex, $key = null)
    {
        $key = $key ? $key : Config::configForKeyPath('passport.authkey');
        if (strlen($tex) === 0)
            return false;

        $verify = substr($tex, 0, self::$sublen);
        $tex = substr($tex, self::$sublen);

        //完整性验证失败
        if ($verify != substr(md5($tex), 0, self::$sublen))
            return false;

        // md5($keyb . $key)
        $randKey = md5(substr($tex, 0, self::$prelen) . $key);

        $tex = base64_decode(substr($tex, self::$prelen));
        $texlen = strlen($tex);
        $reslutstr = "";
        for ($i = 0; $i < $texlen; $i++)
            $reslutstr .= $tex{$i} ^ $randKey{$i % self::$md5len};

        $expiryArr = array();
        preg_match('/^(.*)'. self::$delimiter .'(\d{10})'. self::$delimiter .'$/', $reslutstr, $expiryArr);
        if (count($expiryArr) != 3)
        {
            //过期时间完整性验证失败
            return false;
        }
        else
        {
            //验证码过期
            $texTime = intval($expiryArr[2]);
            if ($texTime > 0 && $texTime - time() <= 0)
                return false;
            else
                $reslutstr = $expiryArr[1];
        }
        return $reslutstr;
    }

    /** 
     * 解密token，用于防止跨域脚本攻击
     gfs23s1423711736OBFG
     +--------+--------+---------------+
     | nonce  | time   | extra of hash |
     +--------+--------+---------------+
     * @param entryptStr 
     * 
     * @return 
     */
    public static function csrfDecrypt($entryptStr = '')
    {
        $entryptStr = trim($entryptStr);

        if (! $entryptStr) {
            return '';
        }

        $hashes = array('O','c','D','q','+','Z','n','%','X','*');
        $timespan = substr($entryptStr, 0, 10);
        $nonce = substr($entryptStr, 10, 6);
        $extraStr = substr($entryptStr, 16);
        if (empty($nonce) || empty($timespan) || empty($extraStr)) {
            return '';
        }
        $time  = str_replace(array_values($hashes), array_keys($hashes), $timespan);

        if (date('Y', $time) == '1970') {
            return '';
        }
        $extraStr = str_replace(array_values($hashes), array_keys($hashes), $extraStr);
        return $extraStr;
    }

}
