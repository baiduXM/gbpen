<?php
$zip = new ZipArchive;//新建一个ZipArchive的对象
if ($zip->open('site.zip') === TRUE)
{
$zip->extractTo('./');//假设解压缩到在当前路径下images文件夹的子文件夹php
}
$zip->close();//关闭处理的zip文件
@unlink('./site.zip');