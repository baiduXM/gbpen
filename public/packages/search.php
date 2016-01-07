<?php
    header("Content-type:text/html;charset=utf-8");
    
    $search = $_GET['s'];
    
    if(file_exists('./article_data.json') && file_exists('./search.html')){
        $article_content = file_get_contents('./article_data.json');
        $search_content = file_get_contents('./search.html');
    }else
        jump('不存在信息内容');
    
    preg_match('/<!--search_content_start-->([\s\S]*)<!--search_content_end-->/', $search_content,$search_exchange);
    preg_match('/<!--page_corrent_link_start-->([\s\S]*)<!--page_corrent_link_end-->/', $search_content,$page_corrent_exchange);
    preg_match('/<!--page_uncorrent_link_start-->([\s\S]*)<!--page_uncorrent_link_end-->/', $search_content,$page_uncorrent_exchange);
    
    $article_id = array();
    //搜索数据
    $article_content = json_decode($article_content,TRUE);
    foreach ($article_content as $value){
        if(stripos($value['title'],$search) !== false)
            $article_id[] = $value['id'];
    }
    
    //每页文章显示个数
    $per_page = $article_content['count'];
    //每页分页链接个数
    $page_links = $article_content['page_link'];
    
    if(count($article_id)){
        $page_count = ceil(count($article_id)/$per_page);
        if(!$page_count){
            $page_count = 1;
        }

        if($_GET['page']){
            if($_GET['page']>1)
                if($_GET['page']<=$page_count)
                    $current_page = floor($_GET['page']);
                else
                    $current_page = $page_count;
            else
                $current_page = 1;
        }else{
            $current_page = 1;
        }
        
        //分页链接处理
        if($current_page==1){
            $first_link = 'javascript:;';
            $prev_link = 'javascript:;';
            if($current_page<$page_count){
                $next_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.($current_page+1);
                $last_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.$page_count;
            }else{
                $next_link = 'javascript:;';
                $last_link = 'javascript:;';
            }
        }else{
            $first_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page=1';
            $prev_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.($current_page-1);
            if($current_page<$page_count){
                $next_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.($current_page+1);
                $last_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.$page_count;
            }else{
                $next_link = 'javascript:;';
                $last_link = 'javascript:;';
            }
        }
        
        $string = '';
        $i = 1;
        foreach($article_id as $v){
            if(($current_page-1)*$per_page < $i && $i <= $current_page*$per_page){
                $linshi = $search_exchange[1];
                $linshi = str_replace('search_$title',$article_content[$v]['title'],$linshi);
                $linshi = str_replace('search_$image',$article_content[$v]['img'],$linshi);
                $linshi = str_replace('search_$description',$article_content[$v]['introduction'],$linshi);
                $linshi = str_replace('search_$pubdate',date("Y-m-d H:i:s",$article_content[$v]['created_at']),$linshi);
                $linshi = str_replace('search_$link',$article_content[$v]['link'],$linshi);
                $string .= $linshi;
                $i +=1;
            }elseif(($current_page-1)*$per_page >= $i){
                $i +=1;
                continue;
            }
            else
                break;
        }
        
        $search_content = str_replace($search_exchange[0], $string, $search_content);
        $search_content = str_replace('-1000_search', count($article_id), $search_content);
        
        //分页链接个数处理
        if($page_corrent_exchange && $page_uncorrent_exchange){
            $string = '';
            if($page_links < $page_count){
                $page_num = pagearray($page_links, $current_page, $page_count, true);
                foreach($page_num as $num){
                    if($num == $current_page){
                        $page_corrent_linshi = $page_corrent_exchange[1];
                        $page_corrent_linshi = str_replace('100-8_search', $num, $page_corrent_linshi);
                        $page_corrent_linshi = str_replace('100-9_search', 'javascript:;', $page_corrent_linshi);
                        $string .= $page_corrent_linshi;
                    }else{
                        $page_uncorrent_linshi = $page_uncorrent_exchange[1];
                        $page_uncorrent_linshi = str_replace('100-8_search', $num, $page_uncorrent_linshi);
                        $page_uncorrent_linshi = str_replace('100-9_search', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.$num, $page_uncorrent_linshi);
                        $string .= $page_uncorrent_linshi;
                    }
                }
            }else{
                $page_num = pagearray($page_count);
                foreach($page_num as $num){
                    if($num == $current_page){
                        $page_corrent_linshi = $page_corrent_exchange[1];
                        $page_corrent_linshi = str_replace('100-8_search', $num, $page_corrent_linshi);
                        $page_corrent_linshi = str_replace('100-9_search', 'javascript:;', $page_corrent_linshi);
                        $string .= $page_corrent_linshi;
                    }else{
                        $page_uncorrent_linshi = $page_uncorrent_exchange[1];
                        $page_uncorrent_linshi = str_replace('100-8_search', $num, $page_uncorrent_linshi);
                        $page_uncorrent_linshi = str_replace('100-9_search', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?s='.$search.'&page='.$num, $page_uncorrent_linshi);
                        $string .= $page_uncorrent_linshi;
                    }
                }
            }
            $search_content = str_replace($page_corrent_exchange[0], $string, $search_content);
            $search_content = str_replace($page_uncorrent_exchange[0], '', $search_content);
        }else{
            $search_content = str_replace('100-8_search', $current_page, $search_content);
            $search_content = str_replace('100-9_search', 'javascript:;', $search_content);
        }
    }else{
        $search_content = str_replace($search_exchange[0], '没有查找到相关的数据', $search_content);
        $search_content = str_replace('-1000_search', 0 , $search_content);
        $current_page = 1;
        $per_page = $article_content['count'];
        $page_count = 1;
        $first_link = 'javascript:;';
        $prev_link = 'javascript:;';
        $next_link = 'javascript:;';
        $last_link = 'javascript:;';
        if($page_corrent_exchange && $page_uncorrent_exchange){
            $search_content = str_replace($page_corrent_exchange[0], '', $search_content);
        }
        $search_content = str_replace('100-8_search', $current_page, $search_content);
        $search_content = str_replace('100-9_search', 'javascript:;', $search_content);
    }
    $search_content = str_replace('100-1_search', $current_page, $search_content);
    $search_content = str_replace('100-2_search', $per_page, $search_content);
    $search_content = str_replace('100-3_search', $page_count, $search_content);
    $search_content = str_replace('100-4_search', $first_link, $search_content);
    $search_content = str_replace('100-5_search', $prev_link, $search_content);
    $search_content = str_replace('100-6_search', $next_link, $search_content);
    $search_content = str_replace('100-7_search', $last_link, $search_content);
    $search_content = str_replace('search_$keyword', $search, $search_content);
    echo $search_content;
    
    
    //分页链接个数处理函数，返回分页编码数组
    function pagearray($count,$page=1,$max=1,$desc = false){
        $page_num = array();
        if($desc){
            if($count == 1){
                $page_num[] = $page;
            }else{
                $next_count = ceil(($count-1)/2);
                $pre_count = $count - $next_count - 1;
                if(($page-$pre_count)<=0){
                    $next_count += $pre_count - $page + 1;
                    $pre_count = $page -1;
                }
                if(($max-$page)<$next_count){
                    $pre_count += $next_count + $page - $max;
                    $next_count = $max - $page;
                }
                for($i=0;$i<$pre_count;$i++){
                    $page_num[] = $page - $i - 1;
                }
                $page_num[] = $page;
                for($i=0;$i<$next_count;$i++){
                    $page_num[] = $page + $i + 1;
                }
            }
        }else{
            while ($count>0) {
                $page_num[] = $count;
                $count--;
            }
        }
        asort($page_num);
        return $page_num;
    }
    
    //错误信息跳转函数
    function jump($content=''){
        echo '404 , 错误的页面---'.$content.' , 当前页面将在3s后自动跳转';
        ob_flush(); 
        flush(); 
        sleep(3);
        echo '<script language="javascript">
                document.location = "http://'.$_SERVER['HTTP_HOST'].'";
            </script>';
        exit;
    }
?>