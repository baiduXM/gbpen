<?php
$zip = new ZipArchive;//�½�һ��ZipArchive�Ķ���
if ($zip->open('site.zip') === TRUE)
{
$zip->extractTo('./');//�����ѹ�����ڵ�ǰ·����images�ļ��е����ļ���php
}
$zip->close();//�رմ����zip�ļ�
@unlink('./site.zip');