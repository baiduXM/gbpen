<?php

if (file_exists('img.zip')) {
    copy("img.zip", 'mobile/img.zip');
    $zip = new ZipArchive; //新建一个ZipArchive的对象
    if ($zip->open('mobile/img.zip') === TRUE) {
        $zip->extractTo('./mobile/'); //假设解压缩到在当前路径下images文件夹的子文件夹php
    }
    $zip->close(); //关闭处理的zip文件
    @unlink('./mobile/img.zip');
    $zip = new ZipArchive; //新建一个ZipArchive的对象
    if ($zip->open('img.zip') === TRUE) {
        $zip->extractTo('./'); //假设解压缩到在当前路径下images文件夹的子文件夹php
    }
    $zip->close(); //关闭处理的zip文件
    @unlink('./img.zip');
}