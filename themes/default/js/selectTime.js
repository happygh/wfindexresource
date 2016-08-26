var nowDate, nowYear, nowMouth, nowDay, nowHours ,nowdaytime, nowhourmiao; //获取当前时间

	function selectTim(){
		var psTime = $("#cartPsTime").val();
		$.ajax({
			type: "POST",
			url: "flow_cart.php",
			dataType: 'json',
			data: "step=gettime",
			success: function(msg){
				nowDate = new Date(); //获取当前时间
				nowDay = msg.nowDay;
				nowHours = msg.nowHours;; //当前小时
				nowdaytime = msg.nowdaytime;
				erdaytime = msg.erdaytime;//第二天时间
				nowhourmiao = msg.nowhourmiao;
				var selTime = $("#sh_time").val();
				var str='';
				if(selTime == '' || selTime == "undefined")
				{
					alert("请先选择日期");
					return false;
				}
				if(selTime<nowdaytime)
				{
					alert("请先选择正确日期,必须大于当前时间");
					return false;
				}
				if(selTime==nowdaytime)
				{
					//viledh = Number(nowHours);
					var jgTime = $("#cartPsTime").val();
					//09-11 11-14 14-17 17-20 20-22
					if (jgTime == 8)
					{
						if('00:00'<=nowhourmiao && nowhourmiao<='08:59')
						{
							str= '<option value="14.15">14点--15点</option>'
								  +  '<option value="15.16">15点--16点</option>'
								  +  '<option value="16.17">16点--17点</option>'
								  +  '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}
						else if('09:00'<=nowhourmiao && nowhourmiao<='11:59')
						{
							str= '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}

						else if('12:00'<=nowhourmiao && nowhourmiao<='13:59')
						{
							str='<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}
						else
						{
							alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
							$("#sh_time").val('');
							$("#phpsj").val('');
							return false;
						}
					}
					else if (jgTime == 4)
					{
						if('00:00'<=nowhourmiao && nowhourmiao<='09:59')
						{
							str= '<option value="14.15">14点--15点</option>'
								  +  '<option value="15.16">15点--16点</option>'
								  +  '<option value="16.17">16点--17点</option>'
								  +  '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}
						else if('10:00'<=nowhourmiao && nowhourmiao<='12:59')
						{
							str= '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}
						else if('13:00'<=nowhourmiao && nowhourmiao<='14:59')
						{
							str='<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>';
						}
						else
						{
							alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
							$("#sh_time").val('');
							$("#phpsj").val('');
							return false;
						}
					}
                    else
                    {
                        if('00:00'<=nowhourmiao && nowhourmiao<='07:59')
                        {
                            str='<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else
                        {
                            alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
                            $("#sh_time").val('');
                            $("#phpsj").val('');
                            return false;
                        }
                    }
				}
				else if(selTime == erdaytime)
				{
                    var jgTime = $("#cartPsTime").val();
					//var tmpBeginTime = new Date(selTime.replace(/-/g, "\/")); //时间转换
					//var tmpEndTime = new Date(nowdaytime.replace(/-/g, "\/")); //时间转换
                    //var timecha = (tmpBeginTime-tmpEndTime)/(60*60*24*1000);
					//viledh = Number(nowHours);
					if(jgTime == 4)
					{
                        if ('00:00'<=nowhourmiao && nowhourmiao<='20:59')
                        {
                            str=
                                '<option value="09.10">9点--10点</option>'
                                +  '<option value="10.11">10点--11点</option>'
                                +  '<option value="11.12">11点--12点</option>'
                                +  '<option value="12.13">12点--13点</option>'
                                +  '<option value="13.14">13点--14点</option>'
                                +  '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else
                        {
                            str= '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
					}
					else if(jgTime == 8)
					{
                        if ('00:00'<=nowhourmiao && nowhourmiao<='19:59')
                        {
                            str=
                                '<option value="09.10">9点--10点</option>'
                                +  '<option value="10.11">10点--11点</option>'
                                +  '<option value="11.12">11点--12点</option>'
                                +  '<option value="12.13">12点--13点</option>'
                                +  '<option value="13.14">13点--14点</option>'
                                +  '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else
                        {
                            str= '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
					}
					else
					{
                        if ('00:00'<=nowhourmiao && nowhourmiao<='13:59')
                        {
                            str=
                                '<option value="09.10">9点--10点</option>'
                                +  '<option value="10.11">10点--11点</option>'
                                +  '<option value="11.12">11点--12点</option>'
                                +  '<option value="12.13">12点--13点</option>'
                                +  '<option value="13.14">13点--14点</option>'
                                +  '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else if ('14:00'<=nowhourmiao && nowhourmiao<='16:59')
                        {
                            str= '<option value="14.15">14点--15点</option>'
                                +  '<option value="15.16">15点--16点</option>'
                                +  '<option value="16.17">16点--17点</option>'
                                +  '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else if ('17:00'<=nowhourmiao && nowhourmiao<='20:59')
                        {
                            str= '<option value="17.18">17点--18点</option>'
                                +  '<option value="18.19">18点--19点</option>'
                                +  '<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else if ('21:00'<=nowhourmiao && nowhourmiao<='23:59')
                        {
                            str='<option value="19.20">19点--20点</option>'
                                +  '<option value="20.21">20点--21点</option>';
                        }
                        else
                        {
                            alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
                            $("#sh_time").val('');
                            $("#phpsj").val('');
                            return false;
                        }
					}
				}
				/*else if(selTime == erdaytime && nowhourmiao > '20:00')
				{
					var jgTime = $("#cartPsTime").val();
					//09-11 11-14 14-17 17-20 20-22
					if (jgTime == 8)
					{
						if('00:00'<=nowhourmiao && nowhourmiao<'07:59')
						{
							str= '<option value="13.14">13点--14点</option>'
								  +  '<option value="14.15">14点--15点</option>'
								  +  '<option value="15.16">15点--16点</option>'
								  +  '<option value="16.17">16点--17点</option>'
								  +  '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else if('08:00'<=nowhourmiao && nowhourmiao<'11:59')
						{

							str= '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else if('12:00'<=nowhourmiao && nowhourmiao<'13:59')
						{
							str='<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else
						{
							alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
							$("#sh_time").val('');
							$("#phpsj").val('');
							return false;
						}
					}
					else
					{
						if('00:00'<=nowhourmiao && nowhourmiao<'08:59')
						{
							str= '<option value="13.14">13点--14点</option>'
								  +  '<option value="14.15">14点--15点</option>'
								  +  '<option value="15.16">15点--16点</option>'
								  +  '<option value="16.17">16点--17点</option>'
								  +  '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else if('09:00'<=nowhourmiao && nowhourmiao<'12:59')
						{
							str= '<option value="17.18">17点--18点</option>'
								  +  '<option value="18.19">18点--19点</option>'
								  +  '<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else if('13:00'<=nowhourmiao && nowhourmiao<'14:59')
						{
							str='<option value="19.20">19点--20点</option>'
								  +  '<option value="20.21">20点--21点</option>'
								  +  '<option value="21.22">21点--22点</option>';
						}
						else
						{
							alert("温馨提示：亲爱的顾客，窝夫小子蛋糕是新鲜烘制，我们必须留出足够的制作时间。如您看到此提示说明该款蛋糕今天已不能配送，请您选择更晚一些的收货时间，比如明天。以便我们更好的为您服务！给您带来的不便，请谅解！");
							$("#sh_time").val('');
							$("#phpsj").val('');
							return false;
						}
					}
				}*/
				else if(selTime>erdaytime)
				{
                    str= '<option value="09.10">9点--10点</option>'
                          +  '<option value="10.11">10点--11点</option>'
                          +  '<option value="11.12">11点--12点</option>'
                          +  '<option value="12.13">12点--13点</option>'
                          +  '<option value="13.14">13点--14点</option>'
                          +  '<option value="14.15">14点--15点</option>'
                          +  '<option value="15.16">15点--16点</option>'
                          +  '<option value="16.17">16点--17点</option>'
                          +  '<option value="17.18">17点--18点</option>'
                          +  '<option value="18.19">18点--19点</option>'
                          +  '<option value="19.20">19点--20点</option>'
                          +  '<option value="20.21">20点--21点</option>';
				}
				$("#s_time").html(str);
			}
		});
	}