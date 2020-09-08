<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:34:"./app/apply/view/test/testmap.html";i:1593686105;}*/ ?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<title>testgemap</title>
<style type="text/css">
body{
  padding-top:80px
}
#search-btn{
  margin-top: 20px;
}   

</style>	

</head>
<body>

<div class="container">
  <div class="row">
    <div class="col-md-3 col-md-offset-3" style="margin-bottom: 20px;">
      <form class="form-horizontal">
        <input id="place-input" type="text" class="form-control " value="趵突泉" placeholder="请输入地址">
        <button type="button" id="search-btn" class="btn btn-success">
          查询
        </button>
      </form>

    </div>
    <div class="col-md-6">
      <div id="main" style="height:500px;"></div>
    </div>
  </div>
</div> 

<script src="__js__/jquery-1.5.2.min.js" ></script>
<script src='__js__/echarts.min.js'></script>
<script src='__js__/echartsplugin/china.js'></script>
<script>  


window.onload=function(){
    //getdata();
}
// 百度地图的开发者秘钥
var token = 'Ru8ILQsBheGiiEzeZ7U9SHDtooFS5Hf2';
var url = 'http://api.map.baidu.com/geocoding/v3/?output=json&ak=' + token + '&address=';
var ePlaceInput = $('#place-input');
var eSearchBtn = $('#search-btn');
var myChart = echarts.init(document.getElementById('main'));
var chartData = [];
eSearchBtn.click(function() {
     getdata();
})

function getdata(){
   // $(function(){
        var place = ePlaceInput.val();
        if (place) {
          $.getJSON(url + place + '&callback=?', function(res) {
            var loc;
            if (res.status === 0) {
              loc = res.result.location;
              chartData.push({
                name: name,
                value: [loc.lng, loc.lat]
              })
              drawMap(place);
            }else{
              alert('百度没有找到地址信息');
            }
          })
        }

   // })
}

function drawMap(name) {

  var option = {
    backgroundColor: '#404a59',
    title: {
      text: '2020要去的地方',
      left: 'center',
      textStyle: {
        color: '#fff'
      }
    },
    tooltip: {
      trigger: 'item'
    },
    toolbox: {
        show: true,
        feature: {
            saveAsImage: {
                show: true
            }
        }
    },
    geo: {
      map: 'china',
      label: {
        emphasis: {
          show: false
        }
      },
      roam: true,
      itemStyle: {
        normal: {
          areaColor: '#323c48',
          borderColor: '#111'
        },
        emphasis: {
          areaColor: '#2a333d'
        }
      }
    },
    series: [{
      name: '地址',
      type: 'scatter',
      coordinateSystem: 'geo',
      data: chartData,
      symbolSize: function(val) {
        return 10;
      },
    }]
  }
  myChart.setOption(option);
}

       
</script>
		
		
</body>
</html>
