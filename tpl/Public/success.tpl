<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>操作成功</title>
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
            .zhuangtaiok{
				color: #01a1ff;
				font-size: 30px;
			}
			
        </style>

    </head>

    <body>
        <!--背景图-->
        <div class="body_bg"> <img src="__PUBLIC__/Dist/img/bianjiokbj.jpg" /> </div>
        <div class="bianji_map">
            <div class="text-center">
                <img src="__PUBLIC__/Dist/img/bianjioktext.png" />
                <div class="zhuangtaiok">
					<b>{$message}{$error}</b>
				</div>
            </div>
            <div class="bj_text">
                页面自动<span class="tiaozhuan"><a id="href" href="{$jumpUrl}">跳转</a></span>等待时间：<span style="color:#01a1ff;" class="countdown"><b id="wait">{$waitSecond}</b></span>
            </div>
        </div>
    </body>
    <bootstrapjs />
    <script type="text/javascript">
        (function(){
            var wait = document.getElementById('wait'),href = document.getElementById('href').href;
            var interval = setInterval(function(){
                var time = --wait.innerHTML;
                if(time <= 0) {
                    location.href = href;
                    clearInterval(interval);
                };
            }, 1000);
        })();
    </script>

</html>