<?php
/**
 * Created by PhpStorm.
 * User: pantao
 * Date: 2018/1/2
 * Time: 20:58
 */

$result = "[";

//获取某目录下所有文件、目录名（不包括子目录下文件、目录名）
$handler = opendir("upload");
//务必使用!==，防止目录下出现类似文件名“0”等情况
while (($filename = readdir($handler)) !== false) {
    if ($filename != "." && $filename != "..") {
        $dir = opendir("upload/" . $filename);
        while (($file = readdir($dir)) !== false) {
            if ($file !== "." && $file !== "..") {
                $path = substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"));
                $result .= get_file_info("upload/$filename/" . $file) . "\"url\":\"https://$_SERVER[HTTP_HOST]$path/upload/$filename/$file\"},";
            }
        }
    }
}

closedir($handler);

function get_file_info($file)
{
    $res = "{\"name\":\"" . basename($file) . "\",";
    $res .= "\"size\":\"" . round(filesize($file) / 1024, 2) . " kb\",";
    $res .= "\"lvtime\":\"" . date("Y-m-d h:i:s", fileatime($file)) . "\",";
    $res .= "\"lmtime\":\"" . date("Y-m-d h:i:s", filemtime($file)) . "\",";
    return $res;
}

$len = strlen($result) - 1;
echo substr($result, 0, $len < 1 ? 1 : $len) . "]";