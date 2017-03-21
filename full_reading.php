<?php
  session_start();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, height=device-height">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/index.css" media="screen">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <title>乐智悦读-全本阅读</title>
    <style media="screen">
      .label{border-radius: 20px; padding: 4px 12px;}
      .purple{color:#824399;}
    </style>
  </head>
  <body>
    <!-- top nav start-->
      <?php
        include_once("top.php");
        include_once("class/common.php");
        if(!isLogin())//如果没有登录则跳转到首页
        {
          header("Location:login.php");
        }
        else
        {
          $user = $GLOBALS['user'];
          $common = new Common();
          $role = $user->get_user_info()->role;
          $user_id = $user->get_user_id();
        }
      ?>
    <!-- top nav end -->
    <!-- main nav start -->
      <div class="container main-nav">
        <div class="brand">
            <img src="img/nav-brand.png" alt="">
        </div>
        <ul class="navigator">
          <li><a href="index.php">首页</a></li>
          <li><a href="full_reading.php" class="active">全本阅读</a></li>
          <li><a href="ing.php">语音朗读</a></li>
          <li><a href="report.php">测评中心</a></li>
        </ul>
      </div>
    <!-- main nav end -->


<!--

  学生开始

-->

    <?php
      if($role == "学生")
      {
    ?>

    <!-- division panel start -->
      <div class="w100 forget">
        <div class="forget_cover">
          全本阅读
          <div class="float_right" style="margin-right:5.8em;">
            <button class="btn btn-success active" onclick="location.href='full_reading.php'">全部书单</button>
            <button class="btn btn-success" onclick="location.href='book_shelf.php'">我的任务</button>
          </div>
        </div>
      </div>
    <!-- division panel end -->
    <!-- filter panel start -->
    <br>
    <div class="row">
      <div class="container">
        <div class="col-lg-8">
          选择书单类型:&nbsp;&nbsp;&nbsp;&nbsp;
          <div class="btn-group">
              <button type="button" class="btn btn-default" id="list_type">书单类型</button>
              <button type="button" class="btn btn-default dropdown-toggle"
                  data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">选择</span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0);" onclick="type_change(0)">全部类型</a></li>
                <?php
                  $types = $common->get_all_book_type();
                  foreach ($types as $type)
                  {
                ?>
                    <li>
                      <a href="javascript:void(0);" onclick="type_change(<?php echo $type->id?>)">
                        <?php echo $type->name?>
                      </a>
                      <?php
                            if(isset($_GET['type']))
                            {
                              if($type->id==intval($_GET['type']))
                              {
                                  echo '<script>$("#list_type").html("'.$type->name.'");</script>';
                              }
                            }
                      ?>
                    </li>
                <?php
                  }
                ?>
              </ul>
          </div>
          &nbsp;&nbsp;
          <div class="btn-group">
              <button type="button" class="btn btn-default" id="grade_type">学段</button>
              <button type="button" class="btn btn-default dropdown-toggle"
                  data-toggle="dropdown">
                  <span class="caret"></span>
                  <span class="sr-only">选择</span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="javascript:void(0);" onclick="grade_change(0)">全部年级</a></li>
                <?php
                  $grades = $common->get_grade();
                  foreach ($grades as $grade)
                  {
                ?>
                    <li><a href="javascript:void(0);" onclick="grade_change(<?php echo $grade->id?>)"><?php echo $grade->grade_name?></a></li>
                    <?php
                          if(isset($_GET['grade']))
                          {
                            if($grade->id==intval($_GET['grade']))
                            {
                                echo '<script>$("#grade_type").html("'.$grade->grade_name.'");</script>';
                            }
                          }
                    ?>
                <?php
                  }
                ?>
              </ul>
          </div>

        </div>
        <div class="col-lg-4">
          <form action="search.php" method="get" target="_blank" name="search" id="search" onsubmit="return check_search()">
              <div class="input-group">
                <input type="text" class="form-control" name="s" id="search_keywords">
                <span class="input-group-addon">
                  <i class="glyphicon glyphicon-search" style="cursor:pointer;" onclick="$('#search').submit()"></i>
                </span>
              </div>
          </form>
        </div>
      </div>
      <br>
    <!-- filter panel end -->
    <!-- booklist panel start -->
    <div class="container mt20">
      <div class="col-lg-12">
        <?php
          $grade = isset($_GET['grade'])?intval($_GET['grade']):0;
          $type = isset($_GET['type'])?intval($_GET['type']):0;
          $page =isset($_GET['page'])?intval($_GET['page']):1;
          $books = $common->get_read_list_2($page,$user_id,$type,$grade);
          if($books)
          {
            foreach($books as $book)
            {
        ?>
          <div class="col-lg-4 mb20">
            <div class="col-lg-6 book_img">
              <a href="book_list.php?list_id=<?php echo $book->id;?>" target="_blank">
                <img src="<?php echo $book->coverimg;?>" width="100%"/>
              </a>
            </div>
            <div class="col-lg-6 book_info" style="display:table;">
              <div style="display:table-cell; vertical-align:middle;">
                <p>名字：<?php echo $book->name;?></p>
                <p class="gray f12">作者：<?php echo $book->author;?></p>
                <p class="gray f12">学段：<?php echo $book->grade;?></p>
                <p class="gray f12">类型：<?php echo $book->type;?></p>
                <?php
                  if($book->status == 1)
                  {
                    echo "<label class=\"label label-info\">在书架上</label>";
                  }
                  else
                  {
                    echo "<label class=\"label label-success\" style='cursor:pointer;' onclick='add2_book_shelf($book->id)'>加入书架</label>";
                  }
                ?>

              </div>
            </div>
          </div>
        <?php
            }
          }
          else
          {
        ?>
          <center>
            <img src="img/gongchengshi.jpeg" style="margin-top:20px;"/>
            <br>
            <p class="gray">
              暂时没有符合条件的书籍...
            </p>
          </center>
        <?php
          }
        ?>

      </div>
      <center>
        <ul class="pagination">
            <?php
              $url = '';
              if($grade != 0)
              {
                if($type !=0)
                {
                  $url = "grade=$grade&type=$type";
                }
                else
                {
                  $url = "grade=$grade";
                }
              }
              else
              {
                if($type != 0)
                {
                  $url = "type=$type";
                }
              }
            ?>
            <li><a href="full_reading.php?page=<?php echo $page-1>0?$page-1:1; echo '&'; echo $url;  ?>">上一页</a></li>
            <?php
              $pages = $common->get_read_list_pages();
              // echo $pages;
              $index = 1;
              while($index <= $pages)
              {
                if($index == $page)
                {
                  echo "<li class=\"active\"><a href=\"full_reading.php?page=$index&$url\">$index</a></li>";
                }
                else
                {
                  echo "<li><a href=\"full_reading.php?page=$index&$url\">$index</a></li>";
                }
                $index++;
              }
            ?>
            <li><a href="full_reading.php?page=<?php echo $page+1>$pages?$pages:$page+1; echo '&'; echo $url;  ?>">下一页</a></li>
        </ul>
      </center>
    </div>
    <!-- booklist panel end -->
    <script type="text/javascript">
      var grade = <?php echo isset($_GET['grade'])?intval($_GET['grade']):0 ?>;
      var type = <?php echo isset($_GET['type'])?intval($_GET['type']):0 ?>;
      function grade_change(id)
      {
        if(type == 0)
        {
          location.href = "full_reading.php?grade="+id;
        }
        else
        {
          location.href = "full_reading.php?grade="+id+"&type="+type;
        }
      }

      function type_change(id)
      {
        if(grade == 0)
        {
          location.href = "full_reading.php?type="+id;
        }
        else
        {
          location.href = "full_reading.php?type="+id+"&grade="+grade;
        }
      }

      function add2_book_shelf(book)
      {
        location.href = "controller/book_shelf.php?action=add2shelf&book="+book;
      }

    </script>
    <?php
      }
    ?>
<!--

  学生结束

-->






<?php
  include_once("footer.php");
?>
</body>
</html>
