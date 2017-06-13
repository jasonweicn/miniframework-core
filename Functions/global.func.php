<?php
// +------------------------------------------------------------
// | Mini Framework
// +------------------------------------------------------------
// | Source: https://github.com/jasonweicn/MiniFramework
// +------------------------------------------------------------
// | Author: Jason.wei <jasonwei06@hotmail.com>
// +------------------------------------------------------------

/**
 * 获取客户端IP地址
 * @return NULL|string|unknown
 */
function getClientIp ()
{
    $ip = null;

    if ($ip !== null) return $ip;
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $ip = $ip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CDN_SRC_IP'])) {
            $ip = $_SERVER['HTTP_CDN_SRC_IP'];
        } else {
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            } else {
                $ip = '0.0.0.0';
            }
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_CDN_SRC_IP')) {
            $ip = getenv('HTTP_CDN_SRC_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }
    preg_match("/[\d\.]{7,15}/", $ip, $matches);
    $ip = !empty($matches[0]) ? $matches[0] : '0.0.0.0';
    
    return $ip;
}

/**
 * 改变数组KEY
 * 
 * @param array $array
 * @param mixed $field
 * @return array
 */
function chgArrayKey ($array, $field)
{
    $tmp = array();
    if (is_array($array)) {
        foreach ($array as $value) {
            $tmp[$value[$field]] = $value;
        }
    } else {
        return false;
    }
    
    return $tmp;
}

/**
 * 获取一个指定长度的随机字符串
 * 
 * @param int $len
 * @return string
 */
function getRandomString ($len = 8)
{
    $str = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $strLen = strlen($str);
    $randomString = '';
    if (!is_int($len) || $len <= 0) $len = 8;
    for ($i=0; $i<$len; $i++) {
        $randomString .= substr($str, rand(0, $strLen-1), 1);
    }
    
    return $randomString;
}

/**
 * 对图片进行base64编码转换
 * 
 * @param string $image_file
 * @return string
 */
function base64EncodeImage ($image_file)
{
    $base64_image = '';
    if (is_file($image_file)) {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
    } else {
        return false;
    }
    
    return $base64_image;
}

/**
 * 输出JSON并终止程序
 * 
 * @param mixed $data
 */
function pushJson ($data)
{
    if (version_compare(PHP_VERSION, '5.4.0', '>=')) {
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    } else {
        echo json_encode($data);
    }
    die();
}

/**
 * 校验日期格式是否正确
 *
 * @param string $date 日期
 * @param string $formats 需要检验的格式数组
 * @return boolean
 */
function isDate($date, $formats = array('Y-m-d', 'Y/m/d'))
{
    $timestamp = strtotime($date);
    if (!$timestamp) {
        return false;
    }
    foreach ($formats as $format) {
        if (date($format, $timestamp) == $date) {
            return true;
        }
    }

    return false;
}

/**
 * 变量输出
 * @param unknown $var
 * @param string $label
 * @param bool $echo
 */
function dump($var, $label = null, $echo = true)
{
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
    
    $cli = preg_match("/cli/i", PHP_SAPI) ? true : false;

    if ($cli === true) {
        $output = PHP_EOL . $label . PHP_EOL . $output. PHP_EOL;
    } else {
        $output = '<pre>' . PHP_EOL . $label . PHP_EOL . $output . '</pre>' . PHP_EOL;
    }
    
    if ($echo) {
        echo $output;
    }
    
    return $output;
}
