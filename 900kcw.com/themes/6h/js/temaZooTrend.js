function shengxiaoTab(){$(".shengxiao").on("click","ul>li",function(){$(this).addClass("checked").siblings().removeClass("checked")})}function drawZXT(g,f,e,a,c){if(c==""){c=15}var d=echarts.init(document.getElementById("style_hot"));var b={grid:{top:"20px",left:"30px",width:"1130px",},color:["#00A1E9","#e47700"],tooltip:{trigger:"axis",background:"rgba(255,217,175,0.3)",axisPointer:{type:"line",lineStyle:{color:"rgba(192,192,192,0.8)"}},formatter:function(h){var j=a[h[0].dataIndex]+" "+f[h[0].value];var i="<div style='font-size:10px;'>"+j+"</div>";return i}},xAxis:[{type:"category",data:g,position:"bottom",offset:20,axisLine:{show:true,onZero:true,lineStyle:{color:"#999999"}},background:"green",axisTick:{show:true,alignWithLabel:true,inside:true,}}],textStyle:{color:"#666666"},yAxis:[{type:"category",data:f,nameLocation:"start",axisLine:{show:false,onZero:true,lineStyle:{color:"#9A9A9A",fontSize:12}},axisTick:{show:true,alignWithLabel:true,inside:true,length:"1160",lineStyle:{color:"rgba(216,216,216,0.5)"}},splitLine:{show:false,lineStyle:{type:"doted",width:1,color:"#dbdbdb"}}}],series:[{lineStyle:{type:"doted",width:1,color:"#dbdbdb"},type:"line",symbolSize:10,symbol:"circle",barWidth:c,data:e}]};d.setOption(b)}function drawFun(a,b){$.ajax({type:"get",url:url.config140+"findCZSpecialTrend.php",data:{periods:a},dataType:"json",success:function(d){console.log(d);var g=d.result.data.axisX;var f=d.result.data.axisY;var e=[];var c=[];$.each(d.result.data.items,function(j,k){var h=[];c.push(k.numbers);e.push(k.y)});console.log(e);drawZXT(g,f,e,c)}})}drawFun(30,50);