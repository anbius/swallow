
/**
 * Created by PhpStorm
 * author : wky<772708673@qq.com>
 * Date: 2016/8/29
 * Time: 14:10
 */

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=7">
<title>采用升序分页测试实现最新信息首页显示（无bug）</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link href="./public/css/inner.css" rel="stylesheet" type="text/css">
<link href="./public/css/common.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="./public/js/jquery.min.js"></script>
<script type="text/javascript" src="./public/js/functions.js"></script>
<script type="text/javascript" src="./public/js/iepngfix_tilebg.js"></script>
</head>
<body>
<div id="wrapper">
<!--head start页面头部-->
<div id="head">
    <!--top start头部 -->
    <div class="top">
        <div class="TopInfo">
            <div class="link"><a  href="#">设为首页</a> | <a href="#">加入收藏</a> | <a href="#">网站地图</a></div>
        </div>
        <div class="clearfix"></div>
        <div class="TopLogo">
            <div class="logo"><a href="./"><img src="#" alt="加载公司LOGO"></a></div>
            <div class="tel">
                <p class="telW">全国招生热线</p>
                <p class="telN">13520319616</p>
            </div>
        </div>
    </div>
    <!--top end-->

    <!--nav start页面导航栏-->
    <div id="NavLink">
        <div class="NavBG">
        <!--Head Menu Start-->
            <ul id="sddm">
                <li class="CurrentLi"><a href="index.php">网站首页</a></li>
                <li><a href="#" onmouseover="mopen(&#39;m2&#39;)" onmouseout="mclosetime()">关于公司</a>
                    <div id="m2" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        <a href="#">公司介绍</a>
                        <a href="#">业务介绍</a>
                    </div>
                </li>
                <li><a href="page.php" onmouseover="mopen(&#39;m3&#39;)" onmouseout="mclosetime()">新闻动态</a>
                    <div id="m3" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        <a href="page.php">新闻展示</a>
                    </div>
                </li>
                <li><a href="./Product/" onmouseover="mopen(&#39;m4&#39;)" onmouseout="mclosetime()">公司产品</a>
                    <div id="m4" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        <a href="#">路由器</a>
                        <a href="#">交换机</a>
                        <a href="#">防火墙</a>
                        <a href="#">无线产品</a>
                        <a href="#">服务器</a>
                    </div>
                </li>
                <li><a href="./Support/" onmouseover="mopen(&#39;m5&#39;)" onmouseout="mclosetime()">技术支持</a>
                    <div id="m5" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                        <a href="#">售后服务</a>
                        <a href="#">下载中心</a>
                        <a href="#">常见问题</a>
                    </div>
                </li>
                <li><a href="#">人才招聘</a></li>
                <li><a href="#">联系我们</a></li>
                <li><a href="#">访客留言</a></li>
            </ul>
        <!--Head Menu End-->
        </div>
        <div class="clearfix"></div>
    </div>
    <!--nav end-->
</div>
<!--新闻内容区-->
<!--head end-->
<!--body start-->
<div id="body">
<!--focus start-->
    <div id="InnerBanner">

    </div>
    <!--foncus end-->
    <div class="HeightTab clearfix"></div>
    <!--inner start -->
    <div class="inner">
        <!--left start-->
        <div class="left">
            <div class="Sbox">
                <div class="topic">新闻动态&nbsp;&nbsp;&nbsp;News</div>
                <div class="blank"><ul><li><a href="./News/CompanyNews">公司新闻</a></li>
                <li><a href="./News/IndustryNews">行业新闻</a></li> </ul></div>
            </div>
            <div class="HeightTab clearfix"></div>
            <div class="Sbox">
                <div class="topic">搜索&nbsp;&nbsp;&nbsp;Search</div>
                <div class="SearchBar">

                </div>
            </div>
        </div>
        <!--left end-->
        <!--right start-->
        <div class="right">
            <div class="Position"><span>你的位置：<a href="./">首页</a> &gt; <a href="./News/">新闻动态</a></span></div>
            <div class="HeightTab clearfix"></div>
            <!--main start-->
            <div class="main">
                <div class="ArticleList">
                      <ul></ul>
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tbody>
                        <?php
                        date_default_timezone_set("PRC");
                        //2. 连接数据库并判断
                        $link = @mysqli_connect("localhost","root","","mypage");
                        if(!$link){
                            die("数据库连接失败！原因：".mysqli_connect_error());
                        }
                        //3. 设置字符编码
                        mysqli_set_charset($link,"utf8");
                    //==========================================================
                    //分页处理
                        //1. 初始化信息
                        $page = isset($_GET["p"])?$_GET['p']:1; //当前页
                        $pageSize = 5; //页大小
                        $maxRows =0; //总条数
                        $maxPage =0; //总页数
                        $maxtime = $_GET['maxtime']+0; //尝试获取当前数据的最大日期
                        //2.获取总计条数（根据日期统计时间）
                        if($maxtime>0){
                            $sql = "select count(*),max(create_time) from ms_article where create_time<={$maxtime}";
                        }else{
                            $sql = "select count(*),max(create_time) from ms_article";
                        }
                        $result = mysqli_query($link,$sql);
                        $v = mysqli_fetch_row($result);
                        $maxRows = $v[0]; //获取条数
                        $maxtime=$v[1];   //获取当前数据的最大时间

                        //3. 计算页数
                        $maxPage = ceil($maxRows/$pageSize); //采用进一取整法计算
                        //4. 校验当前页
                        if($page>$maxPage){
                            $page = $maxPage;
                        }
                        if($page<1){
                            $page=1;
                        }
                        //5. 拼装limit子句
                        $limit = " limit ".max(0,($maxRows-($page*$pageSize))).","
                            .min($pageSize,$maxRows-(($page-1)*$pageSize));
                        //==========================================================

                        //4. 定义sql语句，并发送执行
                        $sql = "select * from ms_article order by create_time asc ".$limit;
                        $result = mysqli_query($link,$sql);

                        //5. 解析结果集，遍历输出
                        $list = mysqli_fetch_all($result,MYSQL_ASSOC);
                        $str="";
                        foreach($list as $v){
                            $str = "<tr><td width=\"80%\" class=\"fw_t\">.
                                    <a href=\"./html/8453713734.html\" target=\"_blank\">{$v['id']}-{$v['title']}</a>
                                    </td><td width=\"20%\" class=\"fw_s\">[".date("Y-m-d H:i:s",$v['create_time'])."]
                                    </td></tr>".$str;
                        }
                        echo $str;
                        //6. 释放结果集，关闭数据库
                        mysqli_free_result($result);
                        mysqli_close($link);
                    ?>

<tr>
    <td colspan="3" height="10"></td>
</tr>
</tbody></table>
<div class="clearfix"></div>
<div class="t_page ColorLink"><?php
    //输出页码信息
    echo "当前第{$page}/{$maxPage}页，共计{$maxRows}条 ";
    echo " <a href='page2.php?p=1'>首页</a> ";
    echo " <a href='page2.php?p=".($page-1)."&maxtime={$maxtime}'>上一页</a> ";
    echo " <a href='page2.php?p=".($page+1)."&maxtime={$maxtime}'>下一页</a> ";
    echo " <a href='page2.php?p={$maxPage}&maxtime={$maxtime}'>末页</a> ";


    ?>
</div>
</div>
</div>
<!--main end-->
</div>
<!--right end-->
</div>
<!--inner end-->
</div>
<!--body end-->
<div class="HeightTab clearfix"></div>
<!--footer start-->
<!--页面底部-->
<div id="footer">
    <div class="inner">
        <p>Copyright   2010-2018 兄弟连培训 技术支持 All rights reserved <br>
        </p>
    </div>
</div>
<!--footer end -->
<script type="text/javascript">
    window.onerror=function(){return true;}
</script>
<!--Powered By HuiguerCMS ASP V3.6-->
</body>
</html>