<?php
/**
 * Created by PhpStorm.
 * User: pantao
 * Date: 2018/1/2
 * Time: 13:15
 */
header("Content-Type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: X_Requested_With');

//$result = "[";
$result = "";
$prefix = "upload/" . date("Ymd") . "/";
foreach ($_FILES as $upload_file) {
    if ($upload_file["error"] > 0) {
        $result .= "{\"error\" : \"$upload_file[error]\"}";
    } else if (strpos($upload_file["name"], ".php") > -1) {
        $result .= "{\"error\" : \"$upload_file[name] is not allowed\"}";
    } else if (file_exists("$prefix" . $upload_file["name"])) {
        $result .= "{\"error\" : \"$upload_file[name] is already exists\"}";
    } else {
        $folder = substr($prefix, 0, strlen($prefix) - 1);
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        move_uploaded_file($upload_file["tmp_name"],
            "$prefix" . $upload_file["name"]);
        $path = substr($_SERVER["PHP_SELF"], 0, strrpos($_SERVER["PHP_SELF"], "/"));
        $result .= "{\"url\" : \"https://$_SERVER[HTTP_HOST]$path/$prefix$upload_file[name]\"}";
    }
//    $result .= ",";
    break;
}
//$len = strlen($result) - 1;
//$result = substr($result, 0, $len < 1 ? 1 : $len) . "]";
echo $result;