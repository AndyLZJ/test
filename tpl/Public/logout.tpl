<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>退出登录</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <link rel="stylesheet" href="__PUBLIC__/Css/bootstrap/css/bootstrap.min.css">
        <style type="text/css">
            body {
                padding: 0;
                margin: 0;
            }
            
            .body_bg {
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: -1
            }
            
            .body_bg img {
                position: fixed;
                height: 100%;
                width: 100%;
            }
            .bianji_map{
                padding-top: 150px;
            }
            .bj_text{
                margin-top:30px;
                text-align: center;
                color: #928e8e;
                font-size: 16px;
            }
            .tiaozhuan{
                color: #01a1ff;
                margin: 0 5px;
                border-bottom: 1px solid #01a1ff;
                
            }
        </style>

    </head>

    <body>
        <!--背景图-->
        <div class="body_bg"> <img src="__PUBLIC__/Dist/img/bianjiokbj.jpg" /> </div>
        <div class="bianji_map">
            <div class="text-center">
                <img src="__PUBLIC__/Dist/img/outbj.png" />
            </div>
            <div class="bj_text">
                页面自动<span class="tiaozhuan"><a id="href" href="{$jumpUrl}">跳转</a></span>等待时间：<span style="color:#01a1ff;" class="countdown">3</span>
            </div>
        </div>
    </body>
    <script src="__PUBLIC__/plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="__PUBLIC__/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript">

    $(function(){
        var i=3;
        var countdown = null;
        function timeShow(){
            i--;
            $(".countdown").html(i);
            if(i<1){
                clearInterval(countdown);       
                window.location.href="{:U('Home/Index/index')}";
            }
        };
        countdown = setInterval(timeShow,1000);
    })
</script>

</html>