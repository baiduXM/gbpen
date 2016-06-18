<?php
/**
 * 推送队列
 *
 * @author 小陈
 */
class PushQueue extends Eloquent{
    protected $table = 'push_queue';
    public  $timestamps = false;
}