/**
* 动态加载外部js或css文件
* 用法：$.include(['http://image.esunny.com/script/jquery.divbox.js','/css/pop_win.css']);
*/
$.extend({
    includePath: '',
    include: function(file)
    {
        var files = typeof file == "string" ? [file] : file;
        for (var i = 0; i < files.length; i++)
        {
            var name = files[i].replace(/^\s|\s$/g, "");
            var att = name.split('.');
            var ext = att[att.length - 1].toLowerCase();
            var isCSS = ext == "css";
            var tag = isCSS ? "link" : "script";
            var attr = isCSS ? " type='text/css' rel='stylesheet' " : " language='javascript' type='text/javascript' ";
            var link = (isCSS ? "href" : "src") + "='" + $.includePath + name + "'";
            if ($(tag + "[" + link + "]").length == 0) document.write("<" + tag + attr + link + "></" + tag + ">");
        }
    }
});

/*
* 异步加载图片
* ajaxImg(oid,imgSrc)  要插入图片的
*/
function ajaxImg(oid,imgSrc){
	var obj = new Image();  
    obj.src = imgSrc;
	if(client.browser.ie)
	{
		obj.onreadystatechange = function()  
		{  
			if ( this.readyState == "complete")  
			{  
			  $("#testid").html("<img src='"+imgSrc+"'/>");
			}   
		}
	}
	else
	{
		obj.onload = function()  
    	{  
			$("#testid").html("<img src='"+imgSrc+"'/>");
    	}  
	}
}


/*
*  开始加载页面
*/
if(!client.browser.ie){
	$.include(['/themes/default/css/m-chrome.css']);
}else{
	$.include(['/themes/default/css/m-ie.css']);
}

var tt0,tt,tt1,tt2,tt3;
var imgNum=0,imgNum2=0;
$(document).ready(function(){
  
  //商品分类
  $(".cate-a").mouseover(function(){
		$(this).next(".cate_list").slideDown('300','swing')
	});
  $(".cate-a").mouseout(function(){
	  	obj = this;
	  	tt1 = setTimeout('$(obj).next(".cate_list").slideUp("300","swing")',300);
		
	});
  $(".cate_list").mouseout(function(){
	  	obj2 = this;
		tt2 = setTimeout('$(obj2).slideUp("300","swing")',300);
	});
  $(".cate_list").mouseover(function(){
	  	clearTimeout(tt1);
		clearTimeout(tt2);
	});
	
  //搜索框
  $("#keyword").click(
  		function(){
			clearTimeout(tt3);
			$("#selectbox").css("display","block");
			$("#selectbox").animate({
				display: 'block',
				height: '200px'
			  }, 500, function() {
				// Animation complete.
			  });
		}
  	);
  $("#keyword").blur(
  		function(){
			tt3 = setTimeout('$("#selectbox").animate({display: "none",height: "0px"}, 500, function() {$("#selectbox").css("display","none");});',500);
		}
  );
  $("#selectbox").click(
  		function(){
			clearTimeout(tt3);
		}
  );
  $("#sclose").click(
  		function(){
			clearTimeout(tt3);
			$("#selectbox").animate({display: "none",height: "0px"}, 500, function() {$("#selectbox").css("display","none");});
		}
  );
  //历史搜索点击
  $(".s_l_h a").click(
  		function(){
			var v = $(this).text();
			$("#keyword").val(v);
		}
  );
  //搜索条件点击
  $(".s_l a").click(
  		function(){
			var v = $(this).text();
			var v_name = $(this).attr("name");
			
			var filter = ".s_l a[name='"+v_name+"']";
			var t_id = "#"+v_name;
			
			$(filter).removeAttr("class");
			$(this).attr("class","sel");
			
			if(v_name=='s_l_price')
			{
				$("#top_price").val($(this).attr("value"));
			}
			else if(v_name=='s_l_kw')
			{
				$("#top_taste").val($(this).attr("value"));
			}
			else if(v_name=='s_l_yt')
			{
				$("#top_festival").val($(this).attr("value"));
			}
			else if(v_name=='s_l_yt')
			{
				$("#top_festival").val($(this).attr("value"));
			}
			
			if(!client.browser.ie || (client.browser.ie && client.browser.ver>7.0))
	  		{
				var cj_a = 20;
				if(client.browser.ie)
				{
					cj_a=20;
				}
				else
				{
					cj_a=16;
				}
				if($(t_id).css("display")=="inline")
				{
					$(t_id).text(v);
					var inp_w = 380;
					var w_1 = ($("#s_l_price").css("display")=="inline")?(parseInt($("#s_l_price").width())+cj_a):0;
					var w_2 = ($("#s_l_kw").css("display")=="inline")?(parseInt($("#s_l_kw").width())+cj_a):0;
					var w_3 = ($("#s_l_dx").css("display")=="inline")?(parseInt($("#s_l_dx").width())+cj_a):0;
					var w_4 = ($("#s_l_yt").css("display")=="inline")?(parseInt($("#s_l_yt").width())+cj_a):0;
					var my_w = (inp_w-(w_1+w_2+w_3+w_4))+"px"
					$("#keyword").css("width",my_w);
				}
				else
				{
					$(t_id).css("display",'inline')
					$(t_id).text(v);
					var inp_w = 380;
					
					var w_1 = ($("#s_l_price").css("display")=="inline")?(parseInt($("#s_l_price").width())+cj_a):0;
					var w_2 = ($("#s_l_kw").css("display")=="inline")?(parseInt($("#s_l_kw").width())+cj_a):0;
					var w_3 = ($("#s_l_dx").css("display")=="inline")?(parseInt($("#s_l_dx").width())+cj_a):0;
					var w_4 = ($("#s_l_yt").css("display")=="inline")?(parseInt($("#s_l_yt").width())+cj_a):0;
					var my_w = (inp_w-(w_1+w_2+w_3+w_4))+"px"
					$("#keyword").css("width",my_w);
				}
			}
		}
  );
  $(".searchBox .sinp_rg").click(
  	function(){
		$('form[name="top_serach"]').submit();
	}
  );
  $("#sqingkong").click(
  		function(){
	  		$(".s_s_tags").text('');
			$(".s_s_tags").css('display','none');
			$("#keyword").css("width","380px");
			
			$(".s_l a").removeAttr("class");
		});
  
  //异步加载图片
  $("img.lazy").lazyload();
  
  if(client.browser.ie){
	  $("#m_box").before("<div id='fl_yy'></div>");
	  
	  if(client.browser.ver<7.0)
	  {
		  $("#i_hd").before("<div style='background:#df9ed1; height:30px; line-height:30px;'><div style='width:985px; margin:0 auto; font-size:14px; text-align:center; color:#666'>亲~您还在使用十几年前的浏览器IE6，享受更棒的购物体验建议您更新至<a href='http://windows.microsoft.com/zh-CN/windows/upgrade-your-browser' target='_blank' style='font-weight:blod'>IE8浏览器</a></div></div>")
	  }
	}
	initFocus();
	//自动轮换事件
	gotoNext();
	gotoLeft()
	
	$(".o_b_lf").click(function(){ 
		clearTimeout(tt0);
		gotoLeft();
	});
	$(".o_b_rg").click(function(){ 
		clearTimeout(tt0);
		gotoRight();
	});
	
	
	/*商品列表页*/
	$(".cakelist li").mouseover(
		function(){
			if($(this).attr('class')=='three')
			{
				$(this).attr('class','three msover');
			}
			else
			{
				$(this).attr('class','msover');
			}
		});
	$(".cakelist li").mouseout(
		function(){
			var mycla
			if($(this).attr('class')=='msover')
			{
				$(this).attr('class','');
			}
			else
			{
				$(this).attr('class','three');
			}
		});
		
	//左侧菜单
	$(".big_cate_l li").mouseover(
		function(){
			var na = $(this).attr('name');
			$(".big_cate_l li").children("a").css("color","#FFF");
			$(this).children("a").css("color","#df9ed1");
			
			var oid = "#"+na;
			$(".min_cate_l").css('display','none');
			$(oid).css('display','block');
		}
	);
	
	/*商品详情页*/
	$(".desc_tags div").click(
		function(){
			$(".desc_tags div").attr('class','tagsoff');
			$(this).attr('class','tags');
			var onam = $(this).attr('name');
			var ocls = "."+onam;
			
			$(".good_desc").css('display','none');
			$(".cjwt_desc").css('display','none');
			$(".sppl_desc").css('display','none');
			$(ocls).css('display','block');
		}
	);
	$(".minimgList ul li img").click(
		function(){
			var osrc = $(this).attr('src');
			$(".bigimg img").attr('src',osrc);
		}
	);
	
	/*订单信息页面*/
	//配送方式切换--门店自取
	$("#rd_self").click(
		function(){
			if(document.getElementById("rd_self").checked)
			{
				$("#div_self").css("display","block");
				$("#waff_ps").css("display","none");
			}
		}
	);
	$("#ps_waff").click(
		function(){
			if(document.getElementById("ps_waff").checked)
			{
				$("#div_self").css("display","none");
				$("#waff_ps").css("display","block");
			}
		}
	);
	//配送方式切换--填写新地址
	$("#female").click(
		function(){
			if(this.checked)
			{
				is_defalut_add = 0;
				$("#div_self").css("display","none");
				$("#waff_ps").css("display","block");
				$("#div_peisong").css("display","none");
				$("#add_peisong_add").css("display","block");
				$("#yiyou_address").css("display","inline");
			}
		}
	);
	//是否开具发票切换
	$("#needfapiao").click(
		function(){
			if(this.checked)
			{
				$("#fapiaoCon").css("display","block");
			}
			else 
			{
				$("#fapiaoCon").css("display","none");
			}
		}
	);
	//选择支付方式
	$("#other_pay4").click(
		function(){
			if(this.checked)
			{
				$("#is_bouns").val('0');
				$("#wbizhifu").css("display","block");
				$("#youhuiquan").css("display","none");
				$("#czkzhifu").css("display","none");
				$("#srchkje").val(0);
				$("#czkpwd").val('');
				$("#input_sn").val(0);
			}
		}
	);
	$("#other_pay2").click(
		function(){
			if(this.checked)
			{
				$("#is_bouns").val(1);
				$("#youhuiquan").css("display","block");
				$("#wbizhifu").css("display","none");
				$("#czkzhifu").css("display","none");
				$("#input_sn").val(0);
				$("#wbinum").val(0);
				$("#czkpwd").val('');
			}
		}
	);
	$("#other_pay3").click(
		function(){
			if(this.checked)
			{
				$("#is_bouns").val(0);
				$("#czkzhifu").css("display","block");
				$("#wbizhifu").css("display","none");
				$("#youhuiquan").css("display","none");
				$("#srchkje").val(0);
				$("#czkpwd").val('');
				$("#wbinum").val(0);
			}
		}
	);
	
	//网上支付/货到付款切换v
	$("#male3").click(
		function(){
			if(this.checked)
			{
				$("#hdfkbox").css("display","none");
				$("#wszfbox").css("display","block");
			}
		}
	);
	$("#male1").click(
		function(){
			if(this.checked)
			{
				$("#hdfkbox").css("display","block");
				$("#wszfbox").css("display","none");
			}
		}
	);
	
	//判断是刷还是现金
	$("#paym").click(
		function(){
			if(this.checked)
			{
				$("#cardlist").css("display","none");
			}
		}
	);
	$("#paym2").click(
		function(){
			if(this.checked)
			{
				$("#cardlist").css("display","block");
			}
		}
	);
	
	/*判断时间*/
	$("#s_time").change(
		function(){
			var v = $(this).find("option:selected").val();
			var vt = v.split(".")
			$("#startTimeInput").val(vt['0']+":00");
			$("#endTimeInput").val(vt['1']+":00");
		}
	);
	
});


/**自定义函数*/
function initFocus()
{
	var imglist = jQuery("#imglist .imglistBox .bannerImg img");
	var imgWidth = imglist.attr("width");
	var imgBox = jQuery("#imglist .imglistBox");
	var imgboxWidth = imgWidth*imglist.length;
	imgBox.css("width",imgboxWidth+"px");
	jQuery("#imgBtnWrapper span").eq(0).css("background","#a50182")
	//鼠标点击事件
	jQuery("#imgBtnWrapper span").click(
		function(){
			var index = $(this).attr("index");
			imgBox.animate({left:-(980*index)},"slow");
			jQuery("#imgBtnWrapper span").css('background','#eee');
			jQuery("#imgBtnWrapper span").eq(index).css('background','#a50182');	
		}
	);
	
}


//自动轮换图片
function gotoNext()
{
	//clearTimeout(tt);
	var imglist = jQuery("#imglist .imglistBox .bannerImg img");
	var imgBox = jQuery("#imglist .imglistBox");

	if(imgNum<imglist.length)
	{
		imgBox.animate({left:-(980*imgNum)},"slow");
		jQuery("#imgBtnWrapper span").css('background','#eee');
		jQuery("#imgBtnWrapper span").eq(imgNum).css('background','#a50182');	
		imgNum++
	}
	if(imgNum>=imglist.length)
	{
		imgNum=0;
	}
	tt = setTimeout("gotoNext()",8000);
}

//自动轮换图片
function gotoRight()
{
	//clearTimeout(tt);
	var imglist = jQuery("#o_b_box .o_b_li");
	var imgBox = jQuery("#o_b_box .o_b_inner");
	//alert(imglist.length);
	var now_p = parseInt(imgBox.css('left'))+195;
	if(now_p<=0)
	{
		imgBox.animate({left:now_p},"slow");
	}
	tt0 = setTimeout("gotoLeft()",3000);
}
//自动轮换图片
function gotoLeft()
{
	//clearTimeout(tt);
	var imglist = jQuery("#o_b_box .o_b_li");
	var imgBox = jQuery("#o_b_box .o_b_inner");
	//alert(imglist.length);
	if(imgNum2<imglist.length)
	{
		imgBox.animate({left:-(195*imgNum2)},"slow");
		imgNum2++
	}

	if(imgNum2>=(5*Math.floor(imglist.length/5)+(imglist.length%5)-5))
	{
		imgNum2=0;
	}
	tt0 = setTimeout("gotoLeft()",3000);
}

//加入收藏
var weburl="http://www.waffleboy.com.com";
function addCookie() {　 // 加入收藏
if (document.all) {
window.external.addFavorite(weburl, '窝夫小子');
}
else if (window.sidebar) {
window.sidebar.addPanel('窝夫小子',weburl, "");
}
else {
alert("加入收藏失败，请使用Ctrl+D进行添加");	
}
}

//计算商品价格-商品详情页
function jisuan(discount,cat_id)
{
	var num=1;
	var cid=get_rd_cake();
	if(cid==0)
	{
		alert("{$lang.plasechosebuycake}");
	}else
	{
		var shop_price=document.getElementById(cid).value;
		if(cat_id=='AA')
		{
			var money=num*shop_price;
		}
		else
		{
			var money=num*shop_price*discount;
		}
		if (cat_id=='AA')
		{
			document.getElementById("zongjine").innerHTML="¥"+money;
		}
		else
		{
			document.getElementById("zongjine").innerHTML="¥"+(Math.floor(money));
		}
	}
}
//获得所选商品型号
function get_rd_cake()
{
	var rd=document.getElementsByName("rd_cake");
	for(var i=0;i<rd.length;i++)
        {
            if(rd[i].checked)
            {
                return rd[i].value;
            }
        }
	return 0;
}
//购买动作
function buy()
{
	  var num = 1;
	  var cid=get_rd_cake();
	  if(cid==0)
	  {
	  	alert("{$lang.plasechosebuycake}");
	  }else
	  {
		  if(num<1) 
		  {
			num = 1;
		  }
		  window.location.href="cake.php?cake_id="+cid+"&act=buy&num="+num;
	  }
}

//购物车加号
function jianBtn(vid,goods_id,goods_price,cat_id)
{
	var obj = document.getElementById(vid);
	obj.value = Number(obj.value) - Number(1);
	if(obj.value<=0)
	{
		obj.value=1;
	}
	if(obj.value>100)
	{
		obj.value=100;
	}
	var aaa = (obj.value*goods_price)+"";
	//alert();
	if(aaa.indexOf(".")!=-1)
	{
	var aaa_s = aaa.substring(0,(aaa.indexOf(".")+3));
	}
	else
	{
		aaa_s = aaa;
	}
	if(cat_id=="AA")
	{
		document.getElementById('goods_total_'+goods_id+'_s').innerHTML=getFormatedPrice(aaa_s);
		document.getElementById('goods_total_'+goods_id).value=aaa_s;
	}
	else
	{
		document.getElementById('goods_total_'+goods_id+'_s').innerHTML=getFormatedPrice(Math.floor(aaa_s));
		document.getElementById('goods_total_'+goods_id).value=Math.floor(aaa_s);
	}
	totalPrice();
}

//加号
//增加-加号
function pulsBtn(vid,goods_id,goods_price,cat_id)
{
	var obj = document.getElementById(vid);
	obj.value = Number(obj.value) + Number(1);
	if(obj.value<0)
	{
		obj.value=0;
	}
	if(obj.value>100)
	{
		obj.value=100;
	}
	var aaa = (obj.value*goods_price)+"";
	//alert();
	if(aaa.indexOf(".")!=-1)
	{
		var aaa_s = aaa.substring(0,(aaa.indexOf(".")+3));
	}
	else
	{
		aaa_s = aaa;
	}
	if(cat_id=="AA")
	{
		document.getElementById('goods_total_'+goods_id+'_s').innerHTML=getFormatedPrice(aaa_s);
		document.getElementById('goods_total_'+goods_id).value=aaa_s;
	}
	else
	{
		document.getElementById('goods_total_'+goods_id+'_s').innerHTML=getFormatedPrice(Math.floor(aaa_s));
		document.getElementById('goods_total_'+goods_id).value=Math.floor(aaa_s);
	}
	totalPrice();
}
//flow 页面计算总金额
function totalPrice()
{
	var ttp=0;
	var ttptwo=0;
	var obj = document.getElementsByName('goods_price_one');
	var objtwo = document.getElementsByName('pound_price_one');
	for (var i=0;i<obj.length; i++)
	{
		ttp = parseFloat(obj[i].value)+parseFloat(ttp);
	}
	for (var j=0;j<objtwo.length;j++)
	{
		ttptwo = parseFloat(objtwo[j].value)+parseFloat(ttptwo);
	}
	document.getElementById('totalPrice').innerHTML=(parseFloat(ttp)+parseFloat(ttptwo))+' 元(含'+parseFloat(ttptwo)+'元附件费用+0元运费)';
}
var flag=true;
//显示隐藏附件
function showNews(){
 var obj=document.getElementById("newsList");
 if(flag){
  obj.style.display="block";
  flag=false;
 }else{
  obj.style.display="none";
  flag=true;
 }
}
function checkinputapp(obj)
{
	var re = /^[1-9]\d*$/;
	if(!re.test(obj.value))
	{
		obj.value=0;
		//obj.select();
	}
}
//计算附件价钱
function sumappend(obj,goods_id,fj_id,append_price)
{
	document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value = (document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value)
	if(document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value<0)
	{
		document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value = 0;
	}
	checkinputapp(obj);
	document.getElementById('append_total_'+goods_id+'_'+fj_id+'_s').innerHTML=getFormatedPrice((parseInt(obj.value)).mul(append_price));
	document.getElementById('append_total_'+goods_id+'_'+fj_id).value=obj.value*append_price;
	totalPrice();
}
function jianBtn_fj(obj,goods_id,fj_id,append_price)
{
	obj.value = parseInt(obj.value)-1;
	if(document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value<0)
	{
		document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value = 0;
	}
	checkinputapp(obj);
	document.getElementById('append_total_'+goods_id+'_'+fj_id+'_s').innerHTML=getFormatedPrice((parseInt(obj.value)).mul(append_price));
	document.getElementById('append_total_'+goods_id+'_'+fj_id).value=obj.value*append_price;
	totalPrice();
}
function pulsBtn_fj(obj,goods_id,fj_id,append_price)
{
	obj.value = parseInt(obj.value)+1;
	if(document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value<0)
	{
		document.getElementById('appendnumber_'+goods_id+'_'+fj_id).value = 0;
	}
	checkinputapp(obj);
	document.getElementById('append_total_'+goods_id+'_'+fj_id+'_s').innerHTML=getFormatedPrice((parseInt(obj.value)).mul(append_price));
	document.getElementById('append_total_'+goods_id+'_'+fj_id).value=obj.value*append_price;
	totalPrice();
}

/*QQ联合登陆*/
function toQzoneLogin()
{
	childWindow = window.open("oauth/qq_login.php","TencentLogin","width=450,height=320,menubar=0,scrollbars=1, resizable=1,status=1,titlebar=0,toolbar=0,location=1");
} 

/*城市区域异步刷新*/
function region_change_jq(regionID,target)
{
	$.ajax({
			type: "POST",
			url: "flow_order.php",
			dataType: 'json',
			data: "step=select_region&value="+ regionID +"&target=" + target,
			success: function(msg){
				region_response_jq(msg)
			}
		});	
}
function region_response_jq(result)
{
  if (result.error)
  {
    alert(result.error);
    location.href = './';
  }

  try
  {
    var sel = document.getElementById(result.target);

    if (document.all)
    {
        sel.fireEvent("onchange");
    }
    else
    {
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent('change', true, true);
        sel.dispatchEvent(evt);
    }
    sel.options.length = 0;
    if (result.content)
    {
        for (i = 0; i < result.content.length; i ++ )
        {
          var opt = document.createElement("OPTION");
          opt.value = result.content[i].region_id;
          opt.text  = result.content[i].region_name;

          sel.options.add(opt);
        }
    }
  }
  catch (ex) { }
}

var username_ok = 0;
/*判断用户名是否已经存在*/
function is_registered( username )
{
    var submit_disabled = false;
	var unlen = username.replace(/[^\x00-\xff]/g, "**").length;

    if ( username == '' )
    {
        document.getElementById('username_notice').innerHTML = '<div class="error"><font>*</font>用户名不能为空</div>';
        var submit_disabled = true;
		username_ok = 0;
    }

    if ( !chkstr( username ) )
    {
        document.getElementById('username_notice').innerHTML = '<div class="error"><font>*</font>用户名含有非法字符</div>';
        var submit_disabled = true;
		username_ok = 0;
    }
    if ( unlen < 3 )
    {
        document.getElementById('username_notice').innerHTML = '<div class="error"><font>*</font>用户名长度不能少于 3 个字符。</div>';
        var submit_disabled = true;
		username_ok = 0;
    }
    if ( unlen > 14 )
    {
        document.getElementById('username_notice').innerHTML = '<div class="error"><font>*</font>用户名最长不得超过7个汉字</div>';
        var submit_disabled = true;
		username_ok = 0;
    }
    if ( submit_disabled )
    {
		username_ok = 0;
        //document.forms['formUser'].elements['Submit'].disabled = 'disabled';
        return false;
    }
	$.ajax({
			type: "GET",
			url: "user.php",
			dataType: 'text',
			data: "act=is_registered&username="+ username,
			success: function(msg){
				registed_callback(msg)
			}
		});	
}
function registed_callback(result)
{
  if ( result == "true" )
  {
    document.getElementById('username_notice').innerHTML = '<div class="success"><font>*</font>恭喜您，此用户名可以注册！</div>';
    username_ok = 1;
  }
  else
  {
    document.getElementById('username_notice').innerHTML = '<div class="error"><font>*</font>用户名已经存在,请重新输入</div>';
    username_ok = 0;
  }
}

function chkstr(str)
{
  for (var i = 0; i < str.length; i++)
  {
    if (str.charCodeAt(i) < 127 && !str.substr(i,1).match(/^\w+$/ig))
    {
      return false;
    }
  }
  return true;
}
var email_ok=0;
/*检查邮箱*/
function checkEmail(email)
{
  var submit_disabled = false;

  if (email == '')
  {
    document.getElementById('email_notice').innerHTML = '<div class="error"><font>*</font>邮件地址不能为空</div>';
    submit_disabled = true;
	email_ok = 0;
  }
  else if (!Utils.isEmail(email))
  {
    document.getElementById('email_notice').innerHTML = '<div class="error"><font>*</font>邮件地址不合法</div>';
    submit_disabled = true;
	email_ok = 0;
  }

  if( submit_disabled )
  {
	  email_ok = 0;
    //document.forms['formUser'].elements['Submit'].disabled = 'disabled';
    return false;
  }
  $.ajax({
			type: "GET",
			url: "user.php",
			dataType: 'text',
			data: "act=check_email&email="+ email,
			success: function(msg){
				check_email_callback(msg)
			}
		});	
}
function check_email_callback(result)
{
  if ( result == 'true' )
  {
    document.getElementById('email_notice').innerHTML = '<div class="success"><font>*</font>此邮箱地址可以注册此邮箱将作为您的登录邮箱</div>';
    email_ok = 1;
  }
  else
  {
    document.getElementById('email_notice').innerHTML = '<div class="error"><font>*</font>邮箱已存在,请重新输入</div>';
    email_ok = 0;
  }
}
/*检查密码*/
var password_ok = 0;
function check_password( password )
{
    if ( password.length < 6 || password.length >12)
    {
        document.getElementById('password_notice').innerHTML = '<div class="error"><font>*</font>密码长度须在6到12个字符</div>';
		password_ok = 0;
    }
    else
    {
        document.getElementById('password_notice').innerHTML = '<div class="success"><font>*</font></div>';
		password_ok = 1;
    }
}
function check_conform_password( conform_password )
{
    password = document.getElementById('password1').value;

    if ( conform_password.length < 6 || conform_password.length > 12 )
    {
        document.getElementById('conform_password_notice').innerHTML = '<div class="error"><font>*</font>密码长度须在6到12个字符</div>';
		password_ok = 0;
        return false;
    }
    if ( conform_password != password )
    {
        document.getElementById('conform_password_notice').innerHTML = '<div class="error"><font>*</font>两次密码输入不同</div>';
		password_ok = 0;
    }
    else
    {
        document.getElementById('conform_password_notice').innerHTML = '<div class="success"><font>*</font>可以注册</div>';
		password_ok = 1;
    }
}
var mobile_ok = 0;
/*判断手机号码*/
function check_mobile(moblie)
{
	if(moblie == '')
	{
		document.getElementById('check_mobile_notice').innerHTML = '<div class="error"><font>*</font>请填写手机号码</div>';
		mobile_ok = 0;
		return false;
	}
	else if(!Utils.isTel(moblie))
	{
		document.getElementById('check_mobile_notice').innerHTML = '<div class="error"><font>*</font>手机只能是11位的数字</div>';
		mobile_ok = 0;
		return false;
	}
	$.ajax({
			type: "GET",
			url: "user.php",
			dataType: 'text',
			data: "act=check_moblie&moblie="+ moblie,
			success: function(msg){
				check_moblie_callback(msg)
			}
		});	
}
function check_moblie_callback(result)
{
	if ( result == 'true' )
	{
	    document.getElementById('check_mobile_notice').innerHTML = '<div class="success"><font>*</font>此手机可以注册</div>';
	    mobile_ok = 1;
	}
	else
	{
	   document.getElementById('check_mobile_notice').innerHTML = '<div class="error"><font>*</font>手机号已存在,请重新输入</div>';
	   mobile_ok = 0;
	}
}

/* *
 * 处理注册用户
 */
function register()
{
  var frm  = document.forms['formUser'];
  var username  = Utils.trim(frm.elements['username'].value);
  var email  = frm.elements['email'].value;
  var password  = Utils.trim(frm.elements['password'].value);
  var confirm_password = Utils.trim(frm.elements['confirm_password'].value);
  var checked_agreement = frm.elements['agreement'].checked;
  var msn = frm.elements['extend_field1'] ? Utils.trim(frm.elements['extend_field1'].value) : '';
  var qq = frm.elements['extend_field2'] ? Utils.trim(frm.elements['extend_field2'].value) : '';
  var home_phone = frm.elements['extend_field4'] ? Utils.trim(frm.elements['extend_field4'].value) : '';
  var office_phone = frm.elements['extend_field3'] ? Utils.trim(frm.elements['extend_field3'].value) : '';
  var mobile_phone = frm.elements['extend_field5'] ? Utils.trim(frm.elements['extend_field5'].value) : '';
  var passwd_answer = frm.elements['passwd_answer'] ? Utils.trim(frm.elements['passwd_answer'].value) : '';
  var sel_question =  frm.elements['sel_question'] ? Utils.trim(frm.elements['sel_question'].value) : '';


  var msg = "";
  // 检查输入
  var msg = '';
  if (username.length == 0)
  {
    msg += username_empty + '\n';
  }
  else if (username.match(/^\s*$|^c:\\con\\con$|[%,\'\*\"\s\t\<\>\&\\]/))
  {
    msg += username_invalid + '\n';
  }
  else if (username.length < 3)
  {
    //msg += username_shorter + '\n';
  }

  if (email.length == 0)
  {
    msg += email_empty + '\n';
  }
  else
  {
    if ( ! (Utils.isEmail(email)))
    {
      msg += email_invalid + '\n';
    }
  }
  if (password.length == 0)
  {
    msg += password_empty + '\n';
  }
  else if (password.length < 6)
  {
    msg += password_shorter + '\n';
  }
  if (/ /.test(password) == true)
  {
	msg += passwd_balnk + '\n';
  }
  if (confirm_password != password )
  {
    msg += confirm_password_invalid + '\n';
  }
  if(checked_agreement != true)
  {
    msg += agreement + '\n';
  }

  if (msn.length > 0 && (!Utils.isEmail(msn)))
  {
    msg += msn_invalid + '\n';
  }

  if (qq.length > 0 && (!Utils.isNumber(qq)))
  {
    msg += qq_invalid + '\n';
  }

  if (office_phone.length>0)
  {
    var reg = /^[\d|\-|\s]+$/;
    if (!reg.test(office_phone))
    {
      msg += office_phone_invalid + '\n';
    }
  }
  if (home_phone.length>0)
  {
    var reg = /^[\d|\-|\s]+$/;

    if (!reg.test(home_phone))
    {
      msg += home_phone_invalid + '\n';
    }
  }
  if (mobile_phone.length>0)
  {
    var reg = /^[\d|\-|\s]+$/;
    if (!reg.test(mobile_phone))
    {
      msg += mobile_phone_invalid + '\n';
    }
  }
  if (passwd_answer.length > 0 && sel_question == 0 || document.getElementById('passwd_quesetion') && passwd_answer.length == 0)
  {
    msg += no_select_question + '\n';
  }

  for (i = 4; i < frm.elements.length - 4; i++)	// 从第五项开始循环检查是否为必填项
  {
	needinput = document.getElementById(frm.elements[i].name + 'i') ? document.getElementById(frm.elements[i].name + 'i') : '';

	if (needinput != '' && frm.elements[i].value.length == 0)
	{
	  msg += '- ' + needinput.innerHTML + msg_blank + '\n';
	}
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
}

/*提交注册订单*/
function formUser()
{
	if(mobile_ok==1&&password_ok==1&&email_ok==1&&username_ok==1)
	{
		document.formUser.submit();	
	}
	else
	{
		alert("填写有误,请完善后重新提交");
	}
}
/*提交购物车*/
function GoToPay()
{
    document.form3.action= "flow.php?step=checkout"; 
    document.form3.submit(); 
}
function GoIndex()
{
	window.location="index.php";
}


//表单检查
function checkform(num)
{
  var czk=document.getElementById("srchkje").value;
  var czkpwd=document.getElementById("czkpwd").value;
  if(czk!='' && czk>0)
  {
	  if(czkpwd=='')
	  {
		  alert("请填写储值卡密码");
		  return false;
	  }
	  else 
	  {
	    $.ajax({
		url:'flow_confirm.php',
		data:'checkCzkPwd='+czkpwd,
		type:'POST',
		success:function(result){
			if(result=='success')
			{
				document.form1.submit(); 
			}
			else
			{
				alert(result);
				return false;
			}
		},
		error:function()
		{
			alert('储值卡密码校验失败,请稍后再试')
		}
	});
	  }
  }
  else
  {
	document.form1.submit();  
  }
}

/**检验储值卡密码*/
function checkCZKpwd()
{
	var czk=document.getElementById("srchkje").value;
    var czkpwd=document.getElementById("czkpwd").value;
	$.ajax({
		url:'flow_confirm.php',
		data:'checkCzkPwd='+czkpwd,
		type:'POST',
		success:function(result){
			if(result=='success')
			{
				document.getElementById("czkzhifuResult").innerHTML='';
			}
			else
			{
				document.getElementById("czkzhifuResult").innerHTML='储值卡密码不正确';
			}
		},
		error:function()
		{
			document.getElementById("czkzhifuResult").innerHTML='储值卡密码校验失败,请稍后再试';
		}
	})
}

//校验优惠券
function checkbonus()
{
	var sn=document.getElementById("input_sn").value;
	document.getElementById("checkResult").innerHTML="<img src='/themes/default/images/loading19.gif'/>"
	$.ajax({
		url:'flow_confirm.php',
		data:'sn='+sn,
		type:'POST',
		dataType:"json",
		success:function(result){
			if(result[1]=="")
			{
				if(result[0]!="")
				{
					document.getElementById("hi_result").value=result[0];
					document.getElementById("input_sn_VoucherAmount").value=result[0];
				}else
				{
					document.getElementById("result").innerHTML=result[1];
					document.getElementById("input_sn_VoucherAmount").value=0;
				}
				if(result[2]!="")
				{
					document.getElementById("input_sn_VoucherType").value=result[2];
				}
				if(result[3]!="")
				{
					document.getElementById("input_sn_bonus_id").value=result[3];
				}
				document.getElementById("checkResult").innerText=result[2];
			}
			else
			{
				document.getElementById("checkResult").innerText=result[1];
			}
			document.getElementById("srchkje").value=0;
			document.getElementById("wbinum").value=0;
		},
		error:function(){alert('优惠券验证失败!')}
	});
}
//校验储值卡的值
function checkczk()
{
	var czk=document.getElementById("srchkje").value;
	var shichangjia=Number(document.getElementById("shichangjia").value);
	var chkzje=Number(document.getElementById("chkzje").innerHTML);
	if(czk!='')
	{
		var re = /^[0-9]\d*$/;
		if(!re.test(czk))
		{
			alert('储值卡填写必需是数字');
			document.getElementById("srchkje").value='';
			document.getElementById("srchkje").focus();
			return false;
		}
		else if(czk>chkzje)
		{
			alert('使用储值卡金额不能大于储值卡现有金额');
			document.getElementById("srchkje").value='';
			document.getElementById("srchkje").focus();
			return false;
		}else if(czk>shichangjia)
		{
			alert('使用储值卡的金额不能大于商品市场价');
			document.getElementById("srchkje").value='';
			document.getElementById("srchkje").focus();
			return false;
	
		}
		//wbinum input_sn
		document.getElementById("wbinum").value=0;
		document.getElementById("input_sn").value=0;
	}
}

function chloveImg(cake_id)
{
			var lbox2 = document.getElementById("love_box_"+cake_id);
			lbox2.parentNode.style.background = "url(/themes/default/images/jia1.png) center right no-repeat";
			//lbox2.style.background="url(/themes/default/images/love_it_2.png) 0px -100px no-repeat"
}
function chloveImg2(cake_id)
{
			var lbox2 = document.getElementById("love_box_"+cake_id);
			lbox2.parentNode.style.background = "none";
			//lbox2.style.background="url(/themes/default/images/love_it_2.png) 0px -50px no-repeat"
}
function myloveBtn(cake_id)
{
		$.ajax({
				type: "POST",
				url: "cake.php",
				dataType: 'json',
				data: "act=like_num_ajax&cake_id="+cake_id,
				success: function(msg){
					if(msg['error']==0)
					{
						alert("喜欢不成功");
					}
					else
					{
						var oid = "#love_num_"+cake_id+" span";
						$(oid).text(msg['message']);;
					}
				}
			});
}

function myloveBtn2(cake_id)
{
		$.ajax({
				type: "POST",
				url: "cake.php",
				dataType: 'json',
				data: "act=like_ajax&cake_id="+cake_id,
				success: function(msg){
					if(msg['error']==0)
					{
						alert("喜欢不成功");
					}
					else
					{
						var oida = "#love_box_"+cake_id;
						$(oida).css("background","url(/themes/default/images/love_it_2.png) 0px -50px no-repeat");
						var oid = "#love_num_"+cake_id+" span";
						var num = $(oid).text();
						$(oid).text(parseInt(num)+1);
						$("#love_num_"+cake_id).css("color","#d20064");
					}
				}
			});
}