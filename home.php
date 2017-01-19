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
    <title>乐智悦读-个人中心</title>
  </head>
  <body>
    <!-- top nav start-->
      <?php
        include_once("top.php");
        if(!isLogin())//如果没有登录则跳转到首页
        {
          header("Location:index.php");
        }
        else
        {
          $user = $GLOBALS['user'];
          $role = $user->get_user_info()->role;
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
          <li><a href="＃">全本阅读</a></li>
          <li><a href="＃">语音朗读</a></li>
          <li><a href="＃">测评中心</a></li>
        </ul>
      </div>
    <!-- main nav end -->
    <!-- forget panel start -->
      <div class="w100 forget">
        <div class="forget_cover">
        </div>
      </div>
      <br>
      <div class="container">
        <?php
          if($role == "学生")
          {
            echo '<iframe src="template/student_left.php" id="menu" width="20%" height="auto" style="float:left;" frameborder="0"></iframe>';
            echo '<iframe src="template/student_right.php" width="80%" height="auto" id="main" name="main" style="float:left; padding-left:10px; padding-top:2em;" frameborder="0"></iframe>';
          }
          else
          {
            echo '<iframe src="template/teacher_left.php" width="20%" height="auto" style="float:left;" frameborder="0"></iframe>';
            echo '<iframe src="template/teacher_right.php" width="80%" height="auto" id="main" name="main" style="float:left; padding-left:10px; padding-top:2em;" frameborder="0"></iframe>';
          }
        ?>
      </div>
      <br>
    <!-- forget panel end -->
    <!-- footer start -->
      <div class="footer">
        <table width="90%" height="160" align="center">
          <tr>
            <td width="65%" align="left" height="160" valign="middle">
              Copyright (c) 2016 北京乐智起航文化发展有限公司 All Rights Reserved.
            </td>
            <td width="35%" align="left" height="160" valign="middle">
                <p>
                  <i class="glyphicon glyphicon-map-marker"></i>
                  地址：北京市海淀区首都师范大学出版社
                </p>
                <p>
                  <i class="glyphicon glyphicon-earphone"></i>
                  电话：123-456-7890
                </p>
                <p>
                  <i class="glyphicon glyphicon-envelope"></i>
                  邮箱：helloworld@gmail.com
                </p>
            </td>
          </tr>
        </table>
      </div>
    <!-- footer end -->
  </body>
  <script type="text/javascript">
    $().ready(function(){
      $("#menu").load(function () {
          var mainheight = $(this).contents().find("body").height() + 30;
          $(this).height(mainheight);
      });
      $("#main").load(function () {
          var mainheight = $(this).contents().find("body").height() + 30;
          $(this).height(mainheight);
      });
    });
  </script>
</html>
