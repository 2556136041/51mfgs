<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:42:"./app/apply/view/index/showmovingdata.html";i:1598786128;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>动态监控</title>

    <!-- <script src="https://cdn.staticfile.org/vue-resource/1.5.1/vue-resource.min.js"></script> -->

  <style>
     *{
        
     }

     .loading{
        width: 100%;
        height: 100%;
        position:fixed;
        top:0;
        left: 0;
        z-index: 100;
        background: white;
    }
    .pic{
        width:80px;
        height:80px;
        background-image:url(__img__/pro/load.gif);
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        margin:auto;
    }

    a{
        text-decoration: none;
    }
    html,body{
        width: 100%;
        height: 100%;
        padding: 0px;
        margin: 0px;
        background:white;
    }
    #app{
        width: 100%;
        height: 100%;
        background:white;
        /*margin:5px;
        box-shadow: 0 2px 8px rgba(20,20,20,.2),0 2px 8px rgba(20,20,20,.2),0 2px 8px rgba(20,20,20,.2),0 2px 8px rgba(20,20,20,.2);  /*四个方面都添加阴影*/*/
        box-sizing: border-box;
        position: relative;
        display: flex;
        flex-direction: column;
        
    }

    #box{
       width:100%;
      /* height: 550px;*/
       flex:1;
     /*  background:gray;*/
    }

    #btn{
        margin-top:20px;
        width:100%;
        height:20px;
        text-align: center;
        line-height: 20px;
        /*background: lavender;*/
    }
    .circle{
        width:16px;
        height: 16px;
        display: inline-block;
        border:1px solid rgba(75,193,252,1);
        border-radius: 50%;
        margin-right: 10px;
        box-sizing: border-box;

    }
    .selected{
        border:2px solid rgba(75,193,252,1);
        /*box-sizing: border-box;*/
    }

    #bot{
        width: 100%;
        height: auto;
        /*background: green;*/
        display: inline-block;
        margin-bottom:15px;
        
    }     

    h3{
        text-align: right;
        font-size: 16px;
       /* background:gray;*/
       /*background: lavender;*/

    }
    div#progress{
        width: 100%;
        height: 40px;
        background:rgba(75,193,252,.2);
        position: relative;
    }

    div#progress div#currentprogress{
        position: absolute;
        top:0;
        left:0;
        width:0%;
        height: 40px;
        background: rgba(75,193,252,1);
        text-align: right;
        line-height: 40px;
        color:white;

    }
    #rocketimg{
       position: absolute;
       left:0px;
       top:0px;
       animation: fly 3s ease infinite;

    }

    @keyframes fly{
         form{
             left:0px;
           
         }
         to{
            left:calc(100% - 89px);
            
         }
    }

    #span_showpercent{
       display:block;
    }



    div#progress div#currentprogress::after{
       content:"";
       position:absolute;
       top:0;
       left:0;
       width: 0%;
       height: 40px;
       background: white;
       animation: progress 3s ease infinite;
    }

    @keyframes progress{
         form{
            width:0px;
           /* background: rgba(75,193,252,1);*/
            opacity: 0;
         }
         to{
            width: 100%;
            background: rgba(75,193,252,1);
            opacity: 0.3;
         }
    }

    a#refresh{
        display: inline-block;
        width: 80px;
        height: 30px;
        line-height: 30px;
        font-size: 14px;
        color:black;
        background: rgba(200,200,200,.9);
        position: absolute;
        top:20px;
        right:50px;
        z-index: 100;
        border-radius:8px;
        text-align: center;
        text-decoration: nont;
    }

    a#refresh:hover{
        background:rgba(75,193,252,1);
        color:white;

    }


    
  </style>

</head>
<body>


<div id="app">
   <a @click="clearRefresh()" id="refresh" href="#">刷新数据</a>

   <div id="box"></div>
   <div id="btn">
        <div :class="['circle',selectedIndex===index?'selected':'']" v-for="(item,index) in circle" :key="index" @click="change(index)"></div>

   </div>  
   <div id="bot">
      <h3>已完成{{currentTotal}}篇, 目标<span style="color:#e6b600;"><b>{{total}}</b></span>篇&nbsp;</h2>
      <div id="progress">
           <div id="currentprogress" v-bind:style="{ width: percentage }"><span id="span_showpercent">{{percentage}}</span></div>
           <img id="rocketimg" src="__img__/icon/rocket.png" height="40px" alt="">
      </div>

   </div>
   
  
</div>

<script src="__js__/echartsplugin/echarts.min.js" ></script>
<script src="__js__/jquery-1.5.2.min.js" ></script>
<script src="__js__/jqueryplugin/jquery.cookie.js" ></script>
<script src="__js__/vue/vue2.6.min.js" ></script>
<script src="__js__/publicdatajs/axios.min.js"></script>
<script src="__js__/publicdatajs/vue-resource.min.js"></script>
<script src="__js__/publicdatajs/showmovingdata-vue-resource.js"></script>
<script src="__js__/publicdatajs/walden.js"></script>

<script>
    //打开网页预加载数据时显示，加载完后图标隐藏
    document.onreadystatechange=function(){
          var loading='<div class="loading"><div class="pic"></div></div>';
          $('body').append(loading);
          if(document.readyState=='complete'){
              $('.loading').fadeOut();
          };
    } 

     
    $(function(){
        //火箭动画 
        //var divW=$("#progress").width();
        var css = {opacity:"1"};
        setInterval(function(){
            $('#span_showpercent').animate(css,1500,rowBack);

        },1500);

        function rowBack(){
            if(css.opacity==='0'){
                css.opacity="1";
                // $('#span_showpercent').fadeIn();
            }else if(css.opacity==='1'){
                css.opacity="0";
                // $('#span_showpercent').fadeOut();
            }
            
        } 

    });
    
  

</script>

</body>
</html>