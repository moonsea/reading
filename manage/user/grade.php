<?php
require ('../include/init.inc.php');
$method = $grade_id = $page_no = '';
extract ( $_REQUEST, EXTR_IF_EXISTS );

if ($method == 'del' && ! empty ( $grade_id )) {
    $result = Grade::delGrade( $grade_id );
    // var_dump($result);
    // exit();
    if ($result>=0) {
        SysLog::addLog ( UserSession::getUserName(), 'DELETE',  'Grade' ,$grade_id ,'' );
        Common::exitWithSuccess ( '已删除','user/grade.php' );
    }else{
        OSAdmin::alert("error");
    }
}

//START 数据库查询及分页数据
$page_size = PAGE_SIZE;
$page_no=$page_no<1?1:$page_no;

if($search){
	$row_count = User::countSearch($user_group,$user_name);
	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
	$total_page=$total_page<1?1:$total_page;
	$page_no=$page_no>($total_page)?($total_page):$page_no;
	$start = ($page_no - 1) * $page_size;
	$user_infos = User::search($user_group,$user_name,$start , $page_size);

}else{
	$row_count = Grade::count ();
	$total_page=$row_count%$page_size==0?$row_count/$page_size:ceil($row_count/$page_size);
	$total_page=$total_page<1?1:$total_page;
	$page_no=$page_no>($total_page)?($total_page):$page_no;
	$start = ($page_no - 1) * $page_size;
	$grades = Grade::getAllGrades ( $start , $page_size );
}

$page_html=Pagination::showPager("grade.php?grade_name=$grade_name&search=$search",$page_no,$page_size,$row_count);

//追加操作的确认层
$confirm_html = OSAdmin::renderJsConfirm("icon-pause,icon-play,icon-remove");

// 设置模板变量
Template::assign('grades', $grades);
Template::assign ( '_GET', $_GET );
Template::assign ( 'page_no', $page_no );
Template::assign ( 'page_html', $page_html );
Template::assign ( 'osadmin_action_confirm' , $confirm_html);
Template::display('user/grade.tpl');
