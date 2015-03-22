<?php

class HelperHttp
{

    public static function post($url, $data)
    {
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($curlHandle, CURLOPT_POST, true);
        if (is_array($data)) {
            $postData = http_build_query($data);
        } else {
            $postData = $data;
        }
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $postData);
        $result = curl_exec($curlHandle);
        curl_close($curlHandle);
        return $result;
    }

    public static function request($url, $mode, $params = '', $needHeader = false, $timeout = 10)
    {
        $begin = microtime(true);
        $curlHandle = curl_init();
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        if ($needHeader) {
            curl_setopt($curlHandle, CURLOPT_HEADER, true);
        }

        if ($mode == 'POST') {
            curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Expect:'));
            curl_setopt($curlHandle, CURLOPT_POST, true);
            if (is_array($params)) {
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, http_build_query($params));
            } else {
                curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $params);
            }
        } else {
            if (is_array($params)) {
                $url .= (strpos($url, '?') === false ? '?' : '&') . http_build_query($params);
            } else {
                $url .= (strpos($url, '?') === false ? '?' : '&') . $params;
            }
        }
        curl_setopt($curlHandle, CURLOPT_URL, $url);
        if (substr($url, 0, 5) == 'https') {
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($curlHandle);

        if ($needHeader) {
            $tmp = $result;
            $result = array();
            $info = curl_getinfo($curlHandle);
            $result['header'] = substr($tmp, 0, $info['header_size']);
            $result['body'] = trim(substr($tmp, $info['header_size']));  //直接从header之后开始截取，因为 1.body可能为空   2.下载可能不全
            //$info['download_content_length'] > 0 ? substr($tmp, -$info['download_content_length']) : '';
        }
        $errno = curl_errno($curlHandle);
        if ($errno) {
            $record = array('url' => $url, 'request_data' => $params, 'errno' => $errno, 'server' => $_SERVER, 'request' => $_REQUEST);
        }
        curl_close($curlHandle);
        return $result;
    }

    public static function getRequestMethod()
    {
        if (!array_key_exists('REQUEST_METHOD', $_SERVER)) {
            return 'HEAD';
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function setCookieFromHeader($header, $expire = 0, $path, $domain, $secure = false, $httponly = false)
    {
        $matches = array();
        $sum = preg_match_all('/Set-Cookie: ([^;=]+)=([^;=]+);/', $header, $matches);
        for ($i = 0; $i < $sum; $i++) {
            setcookie(urldecode($matches[1][$i]), urldecode($matches[2][$i]), $expire, $path, $domain);
        }
        return $sum;
    }

    public static function getClientIp()
    {
        $onlineIp = '';
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineIp = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineIp = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineIp = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineIp = $_SERVER['REMOTE_ADDR'];
        }
        list($finalIp) = explode(',', $onlineIp);
        return trim($onlineIp);
    }
    
    public static function uploadFileByHttp($url, $filePath) {
            $maxRetry = 3;
            $retry = 0;
            $result = false;
            //$file = array("file"=>"@".$filePath);//文件路径，前面要加@，表明是文件上传.
            if (!function_exists('curl_file_create')) {
                $file = array('file' => '@' . $filePath);
            }
            else {
                $cfile = curl_file_create($filePath);
                $file = array(
                    'file' => $cfile,
                );
            }

            while ($retry < $maxRetry && !$result) {
                $retry++;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch,CURLOPT_POST,true);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$file);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                $result = curl_exec($ch);
                curl_close($ch);
            }
            return $result;
    }
    
    public static function getHttps($url) {
        $maxRetry = 3;
        $retry = 0;
        $result = false;
        while ($retry < $maxRetry && !$result) {
            $retry++;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return $result;
    }
}
