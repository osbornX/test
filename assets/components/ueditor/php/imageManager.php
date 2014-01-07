<?php
    /**
     * Created by JetBrains PhpStorm.
     * User: taoqili
     * Date: 12-1-16
     * Time: 上午11:44
     * To change this template use File | Settings | File Templates.
     */
    header("Content-Type: text/html; charset=utf-8");
    error_reporting( E_ERROR | E_WARNING );

    //需要遍历的目录列表，最好使用缩略图地址，否则当网速慢时可能会造成严重的延时
    $globalConfig = include( "config.php" );
    $paths = $globalConfig['imageSavePath'];

    $action = htmlspecialchars( $_POST[ "action" ] );
    if ( $action == "get") {
        $files = array();
        foreach ( $paths as $path){
            $tmp = getfiles( $path );
            if($tmp){
                $files = array_merge($files,$tmp);
            }
        }
        if ( !count($files) ) return;
        rsort($files,SORT_STRING);
        $str = "";
        foreach ( $files as $file ) {
            $str .= $file . "ue_separate_ue";
        }
        echo $str;
    }

    /**
     * 遍历获取目录下的指定类型的文件
     * @param $path
     * @param array $files
     * @return array
     */
    function getfiles( $path , &$files = array() )
    {
        if ( !is_dir( $path ) ) return null;
        $handle = opendir( $path );
        while ( false !== ( $file = readdir( $handle ) ) ) {
            if ( $file != '.' && $file != '..' ) {
                $path2 = $path . '/' . $file;
                if ( is_dir( $path2 ) ) {
                    getfiles( $path2 , $files );
                } else {
                    if ( preg_match( "/\.(gif|jpeg|jpg|png|bmp)$/i" , $file ) ) {
                        $files[] = normalize($path2);
                    }
                }
            }
        }
        return $files;
    }

 function normalize($path) {
    if (!strlen($path)) {
        return ".";
    }

    $isAbsolute    = $path[0];
    $trailingSlash = $path[strlen($path) - 1];

    $up     = 0;
    $peices = array_values(array_filter(explode("/", $path), function($n) {
        return !!$n;
    }));
    for ($i = count($peices) - 1; $i >= 0; $i--) {
        $last = $peices[$i];
        if ($last == ".") {
            array_splice($peices, $i, 1);
        } else if ($last == "..") {
            array_splice($peices, $i, 1);
            $up++;
        } else if ($up) {
            array_splice($peices, $i, 1);
            $up--;
        }
    }

    $path = implode("/", $peices);

    if (!$path && !$isAbsolute) {
        $path = ".";
    }

    if ($path && $trailingSlash == "/") {
        $path .= "/";
    }

    return ($isAbsolute == "/" ? "/" : "") . $path;
}
