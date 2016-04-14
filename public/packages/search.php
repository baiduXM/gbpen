<?php

header("Content-type:text/html;charset=utf-8");
if ($_GET['type']) {
	$type = $_GET['type'];
} else {
	if ($_SERVER['HTTP_REFERER']) {
		$pageUrl = $_SERVER['HTTP_REFERER'];
		$pageUrl = str_replace('http://', '', $pageUrl);
		$pageUrl = explode('/', $pageUrl);
		$type = $pageUrl[1];
	} else {
		jump('不存在此链接');
	}
}

$search = $_GET['s'];

if ($type == 'mobile') {
	if (file_exists('./mobile_article_data.json') && file_exists('./mobile/search.html')) {
		$article_content = file_get_contents('./mobile_article_data.json');
		$search_content = file_get_contents('./mobile/search.html');
	} else
		jump('不存在信息内容');
}else {
	if (file_exists('./pc_article_data.json') && file_exists('./search.html')) {
		$article_content = file_get_contents('./pc_article_data.json');
		$search_content = file_get_contents('./search.html');
	} else
		jump('不存在信息内容');
}

preg_match('/<!--search_content_start-->([\s\S]*)<!--search_content_end-->/', $search_content, $search_exchange);
$search_exchange = $search_exchange[1];

$article_id = array();
//搜索数据
$article_content = json_decode($article_content, TRUE);
foreach ($article_content as $value) {
	if (stripos($value['title'], $search) !== false)
		$article_id[] = $value['id'];
}

$per_page = $article_content['count'];

if (count($article_id)) {
	$page_count = ceil(count($article_id) / $per_page);
	if (!$page_count) {
		$page_count = 1;
	}

	if ($_GET['page']) {
		if ($_GET['page'] > 1)
			if ($_GET['page'] <= $page_count)
				$current_page = floor($_GET['page']);
			else
				$current_page = $page_count;
		else
			$current_page = 1;
	}else {
		$current_page = 1;
	}

	//分页链接处理
	if ($current_page == 1) {
		$first_link = 'javascript:;';
		$prev_link = 'javascript:;';
		if ($current_page < $page_count) {
			$next_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=' . ($current_page + 1);
			$last_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=' . $page_count;
		} else {
			$next_link = 'javascript:;';
			$last_link = 'javascript:;';
		}
	} else {
		$first_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=1';
		$prev_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=' . ($current_page - 1);
		if ($current_page < $page_count) {
			$next_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=' . ($current_page + 1);
			$last_link = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?type=' . $type . '&s=' . $search . '&page=' . $page_count;
		} else {
			$next_link = 'javascript:;';
			$last_link = 'javascript:;';
		}
	}

	$string = '';
	$i = 1;
	foreach ($article_id as $v) {
		if (($current_page - 1) * $per_page < $i && $i <= $current_page * $per_page) {
			$linshi = $search_exchange;
			$linshi = str_replace('search_$title', $article_content[$v]['title'], $linshi);
			$linshi = str_replace('search_$image', $article_content[$v]['img'], $linshi);
			$linshi = str_replace('search_$description', $article_content[$v]['introduction'], $linshi);
			$linshi = str_replace('search_$pubdate', date("Y-m-d H:i:s", $article_content[$v]['created_at']), $linshi);
			$linshi = str_replace('search_$link', $article_content[$v]['link'], $linshi);
			$string .= $linshi;
			$i +=1;
		} elseif (($current_page - 1) * $per_page >= $i)
			continue;
		else
			break;
	}

	$search_content = str_replace($search_exchange, $string, $search_content);
	$search_content = str_replace('-1000_search', count($article_id), $search_content);
}else {
	$search_content = str_replace($search_exchange, '没有查找到相关的数据', $search_content);
	$search_content = str_replace('-1000_search', 0, $search_content);
	$current_page = 1;
	$per_page = $article_content['count'];
	$page_count = 1;
	$first_link = 'javascript:;';
	$prev_link = 'javascript:;';
	$next_link = 'javascript:;';
	$last_link = 'javascript:;';
}
$search_content = str_replace('100-1_search', $current_page, $search_content);
$search_content = str_replace('100-2_search', $per_page, $search_content);
$search_content = str_replace('100-3_search', $page_count, $search_content);
$search_content = str_replace('100-4_search', $first_link, $search_content);
$search_content = str_replace('100-5_search', $prev_link, $search_content);
$search_content = str_replace('100-6_search', $next_link, $search_content);
$search_content = str_replace('100-7_search', $last_link, $search_content);
$search_content = str_replace('100-8_search', $current_page, $search_content);
$search_content = str_replace('100-9_search', 'javascript:;', $search_content);
$search_content = str_replace('search_$keyword', $search, $search_content);
echo $search_content;

//错误信息跳转函数
function jump($content = '') {
	echo '404 , 错误的页面---' . $content . ' , 当前页面将在3s后自动跳转';
	ob_flush();
	flush();
	sleep(3);
	echo '<script language="javascript">
                document.location = "http://' . $_SERVER['HTTP_HOST'] . '";
            </script>';
	exit;
}

?>