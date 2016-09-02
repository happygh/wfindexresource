<?php
/**
 * 购物流程
 */
define('IN_ECS', true);
//define('INIT_NO_USERS', true);
require(dirname(__FILE__) . '/includes/init.php');
require(ROOT_PATH . 'includes/lib_order.php');
require (ROOT_PATH . 'includes/cps_tools.php');
require_once(dirname(__FILE__)  . '/includes/lib_passport.php');
require_once(dirname(__FILE__) . '/includes/no_vip_price_goods.php');
include_once(dirname(__FILE__) . '/API/weibo/saetv2.ex.class.php' );

/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/shopping_flow.php');
/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

if (!isset($_REQUEST['step']))
{
    $_REQUEST['step'] = "cart";
    
}

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

$smarty->assign("current_user_name", getCurrentUser());

$position = assign_ur_here(0, $_LANG['shopping_flow']);
$smarty->assign('page_title',       $position['title']);    // 页面标题
$smarty->assign('ur_here',          $position['ur_here']);  // 当前位置

$smarty->assign('lang',             $_LANG);
$smarty->assign('show_marketprice', $_CFG['show_marketprice']);
$smarty->assign('data_dir',    DATA_DIR);       // 数据目录

/**
 * 如果是全国送的产品，则不能使用代金卡xie
 */
if ($_REQUEST['step'] == 'dajincart')
{
	
	$data1 = $_REQUEST['mydata'];
	if ($data1 == '123')
	{
		//获取购物车
		$cart_goods = get_cart_goods();
		$goods_list=$cart_goods['goods_list'];
		
		foreach ($goods_list as $v)
		{
			$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id]) and goods_id !='33368' and goods_id !='33367' and goods_id != '33352' ";
			$rst[] = $GLOBALS['db']->getAll($dajinsql);
			$a = '';
			foreach ($rst as $vv)
			{
				foreach ($vv as $vvv)
				{
					if ($vvv['cat_id'] == 'AJ')
					{
						$a++;
					}
				}
			}
			
		}
		//获取购物车商品数量与$a的数量比较，如果两数相等则表示购物车中都是全国送的则不能使用代金卡
		$sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
		" WHERE session_id = '" . SESS_ID . "' " .
		"AND rec_type = '$flow_type'";
		
		$cartnum = $GLOBALS['db']->getOne($sql);
		
		if ($cartnum == $a)
		{
			echo '123123';exit;
		}
	}
}

/**
 * 光大银行5元购买牛轧糖该优惠券不能购买其他的商品
 */
if ($_REQUEST['step'] == 'guangdcart')
{
	//判断该优惠券的类型如果是光大的类型则不许购买其他商品
	$bouns_sn = $_GET['bouns_sn'];
	$sqla = "select bonus_end from user_bonus where bonus_sn=".$bouns_sn;
	$arst = $GLOBALS['db']->getOne($sqla);
	if ($arst == '光大促销')
	{
		//获取购物车
		$cart_goods = get_cart_goods();
		$goods_list=$cart_goods['goods_list'];
		foreach($goods_list as $va)
		{
			if ($va['goods_id'] == '33352')
			{
				$abc = 'isallow';
			}
		}
		
		if ($abc != 'isallow')
		{
			echo "1231";exit;
		}
	}
}


if ($_REQUEST['step'] == 'add_to_cart')
{
    /*------------------------------------------------------ */
    //-- 添加商品到购物车
    /*------------------------------------------------------ */
}
elseif($_REQUEST['step']=='gettime')
{
	date_default_timezone_set('Asia/Shanghai');
	$time = time();
	
	require_once (ROOT_PATH.'includes/cls_json.php');
	$json = new JSON();
	
	$result['nowdaytime']=date('Y-m-d',$time);
	//第二天的时间
	$result['erdaytime']=date('Y-m-d',$time+86400);
	$result['nowHours']=date('H',$time);
	$result['nowDay']=date('d',$time);
	$result['nowhourmiao'] = date( 'H:i', $time );
	
	//$cc = date('Y,m,d,H,i,s',$result);
	
	die($json->encode($result));
}
elseif($_REQUEST['step'] == 'get_freePlygon')
{
	$city = $_REQUEST['city'];
	
	$sql = "select * from map_quyu where quyu_name like '%$city%'";
	$res = $GLOBALS['db']->getAll($sql);
	
	foreach ($res as $k => $v)
	{
		$res[$k]['points'] = (unserialize($v['points']));
	}
	
	require_once (ROOT_PATH.'includes/cls_json.php');
	$json = new JSON();
	$result = array("error"=>0,"message"=>NUll);
	$result['error']=1;
	$result['message']=$res;
	die($json->encode($result));
}
elseif ($_REQUEST['step'] == 'pd_mbyklf') 
{
    /*------------------------------------------------------ */
    //-- //判断只有面包时才弹出快递选择。
    /*------------------------------------------------------ */
    $mdxzdzid = $_POST['mdpsdzid'];
    $cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	static $bjmds = 0;
    foreach ($goods_list as $dyav)
	{
	    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	    {
	    	$bjmds += $dyav['goods_number'];
	    }
	}
	//获取购物车商品数量
	$sql = "SELECT goods_number FROM " . $ecs->table('cart') .
	" WHERE session_id = '" . SESS_ID . "' " .
	"AND rec_type = '$flow_type'";
	
	$bjbcartnum = $GLOBALS['db']->getAll($sql);
	foreach ($bjbcartnum as $xxxnum)
	{
		$bjbcartnumshow += $xxxnum['goods_number'];
	}
	if ($bjbcartnumshow == $bjmds)
	{
		if ($mdxzdzid != 1 && $mdxzdzid != 2)
	    {
	    	echo 123123;
	    }
	    else
	    {
	    	echo 456456;
	    }
    exit;
	}
}
elseif ($_REQUEST['step'] == 'select_region')
{
    /*------------------------------------------------------ */
    //-- 选择城市对应的的区
    /*------------------------------------------------------ */

    include_once('includes/cls_json.php');
    $json = new JSON;
    $result = array('error' => '', 'content' => '', 'target' => $_REQUEST['target'], 'need_insure' => 0);

    $result['content'] = get_regions($_REQUEST['value']);

    echo $json->encode($result);
    exit;
}else if ($_REQUEST['step'] == 'save_order')
{
	//获取购物车
	$cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	$smarty->assign ('goods_list', $goods_list);
	/*-------------------------------------------------------*/
	//-- 检查购物车中是否有商品
	/*-------------------------------------------------------*/
    $sql = "SELECT COUNT(*) FROM " . $ecs->table('cart') .
        " WHERE session_id = '" . SESS_ID . "' " .
        "AND rec_type = '$flow_type'";

    if ($db->getOne($sql) == 0)
    {
        show_message($_LANG['no_goods_in_cart'], '', '/index.php', 'warning');
    }
    /*---------------------------------------------------------*/
    //-- 获取收货人信息
    /*---------------------------------------------------------*/
	$peisongfei=0;  //配送费用
	$dh_name=$_REQUEST['dh_name'];  //订货人名称
	
	$dmobile=$_REQUEST['dmobile'];  //订货人移动电话
	$dgyt = $_REQUEST['dgyt'];
	$Deliver_Fee = $_REQUEST['Deliver_Fee']; //配送费用
	
	$dfamily_phone=$dfamily_qh."-".$dfamily_dh; //订货人固定电话
    /*---------------------------------------------------------*/
    //-- 配送方式及收货人信息
    /*---------------------------------------------------------*/
	$is_ship_self=$_REQUEST['rd_self'];  //配送方式
	//自取
	if($is_ship_self==1)  
	{
		$self_dizhi=$_REQUEST['self_dizhi'];
		$sh_name=$_REQUEST['dh_name'];
		$sd=$self_dizhi;  //收货人城市代码
		//$scity_name = get_region($_REQUEST['dhr_nomarch']);  //收货人城市名称
		if($self_dizhi=='010')
		{
			$scity_name="北京市";
			$sxxaddress='北京市东城区朝阳门北大街8号富华大厦A座B1';//收货人详细信息
		}
		else if($self_dizhi=='021') {
			$scity_name="上海市";
			$sxxaddress='上海市普陀区泸定路730号（近怒江北路）';//收货人详细信息
		}
		else {
			$scity_name="其它省市";
			$sxxaddress='北京市东城区朝阳门北大街8号富华大厦A座B1';//收货人详细信息
		}
		//$sq_id=$_REQUEST['dhr_area'];  //收货人区ID
		//$sq_name=get_region($sq_id);  //收货人区名称
		//是否填写了收货人邮编
		if($_REQUEST['dpost']!="")
		{
			$spost=$_REQUEST['dpost'];
		}else
		{
			$spost=0;
		}
		
		$smobile=trim($_REQUEST['dmobile']);//收货人移动电话
		$sfamily_qh=$_REQUEST['dfamily_qh'];//收货人电话区号
		$sfamily_dh=$_REQUEST['dfamily_dh'];//收货人电话号码
		$sfamily_phone=$sfamily_qh."-".$sfamily_dh;
	}
	//配送
	else {
		$is_ship_self=0;
		if($_REQUEST['ps_address']>0)
		{
			$_SESSION['peisongfeiyong']='0';
			//说明用户进行了登录并且用户选择了之前使用的地址,无须记录用户的收货信息
			$ps_address=$_REQUEST['ps_address'];
			$sql="select * from user_address where address_id=".$ps_address." and user_id=".$_SESSION["user_id"];
			$user_address=$GLOBALS['db']->getRow($sql);
			$scity_id=$user_address['city'];
			if(cate_in_list('AM',$goods_list))
			{
				   $sd='010';
				   $scity_name = '北京市';
				   $user_city=get_region($scity_id);
				   $user_qu=get_region($user_address['district']);
				   $sxxaddress=$user_address['address'];
				   $smobile=$user_address['mobile'];
				   $sfamily_phone=$user_address['tel'];
				   $sh_name=$user_address['consignee'];
			}
			else
			{
				if($scity_id != '1' && $scity_id != '2')
				{
					$sd='010';
					$scity_name = get_region($scity_id);;
					$user_city=get_region($scity_id);
					$user_qu=get_region($user_address['district']);
					$sxxaddress=$user_address['address'];
					$smobile=$user_address['mobile'];
					$sfamily_phone=$user_address['tel'];
					$sh_name=$user_address['consignee'];
				}
				else
				{
					$sd=get_region_id($scity_id);
					$scity_name =get_region($scity_id);
					$user_city=get_region($scity_id);
					$user_qu=get_region($user_address['district']);
					$sxxaddress=$user_address['address'];
					$smobile=$user_address['mobile'];
					$sfamily_phone=$user_address['tel'];
					$sh_name=$user_address['consignee'];
				}
			}
			
			//是否有配送费用
			if($_REQUEST['wuhuan']=='1')
			{
				$peisongfei=0;
				
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='2')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='3')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='4')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}
			
			//如果全国送的产品订单总价超过179元则免运费
			$cart_goods = get_cart_goods();
			$goods_list=$cart_goods['goods_list'];
			
			/*全国送的产品同时购买一件运费8元两件4元3件或以上0元*/
			foreach ($goods_list as $kk=>$v)
			{
				$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
				$rst[] = $GLOBALS['db']->getAll($dajinsql);
				
				foreach ($rst as $vv)
				{
					foreach ($vv as $vvv)
					{
						if ($vvv['cat_id'] == 'AJ')
						{
							$ajnum[$kk] = $v['goods_number'];
                            $ajprice[$kk] = $v['shop_price'] * $v['goods_number'];
						}
					}
				}
					
			}

            if (is_array($ajprice))
            {
                $ajpriceyun = array_sum($ajprice);
            }
            if ($ajpriceyun >= '179')
            {
                $peisongfei = 0;
            }
            else
            {
                if (is_array($ajnum))
                {
                    $yunfeinum = array_sum($ajnum);
                }
                if ( ! empty($yunfeinum))
                {
                    if ($yunfeinum < 2)
                    {
                        $peisongfei = 8;
                    }
                    else if($yunfeinum == 2)
                    {
                        $peisongfei = 4;
                    }
                    else if($yunfeinum > 2)
                    {
                        $peisongfei = 0;
                    }
                    else
                    {
                        $peisongfei = 0;
                    }
                }
            }

            //如果购买一盒甜品北京免运费，其他一盒25元，二盒免运费
            if ($scity_id == 1)
            {
                foreach ($goods_list as $yav)
                {
                    if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
                    {
                        $peisongfei = 0;
                    }
                }
            }
            else
            {
                static $yhtznum = 0;
                foreach ($goods_list as $yav)
                {
                    if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
                    {
                        $yhtznum += $yav['goods_number'];
                    }
                }

                if ($yhtznum == 1)
                {
                    $peisongfei = 25;
                }
                else if ($yhtznum >= 2)
                {
                    $peisongfei = 0;
                }


            }

            /*
			 * 面包运费
			 * 北京上海1个面包10元，2个面包5元，3个面包及以上0元运费。
			 * 北上以外地区，河北，天津1个面包（顺丰14元，普通10元）2个面包（顺丰20元，普通10元）3个面包（顺丰26元，普通22元），4个面包（顺丰26元，普通22元）
			 * 其他地区1个面包（顺丰23元，普通10元）2个面包（顺丰33元，普通16元）3个面包（顺丰43元，普通22元），4个面包（顺丰43元，普通22元））
            */
            $pdbjmb = array('33796','33806','33807','33808');
            foreach ($goods_list as $dyav)
            {
            	if(in_array($dyav['goods_id'], $pdbjmb))
				{
					$pdmdsbsky = 1;
					static $bjmds = 0;
				}    		
            }
            if ( $pdmdsbsky == 1 )
            {
            	if ($scity_id == 1 || $scity_id == 2)
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                if ($bjmds == 1)
	                {
	                	$peisongfei = 10;
	                }
	                else if ($bjmds == 2)
	                {
	                	$peisongfei = 5;
	                }
	                else 
	                {
	                	$peisongfei = 0;
	                }
	            }
	            else if ($scity_id == 80 || $scity_id == 96 )
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                //接收用户选择的快递方式
	                $user_md_kd = $_POST['maxzkd'];
	                if (empty($user_md_kd))
	                {
	                	$user_md_kd = 'mdptkd';
	                }
	                if ($user_md_kd == 'mdsfkd')
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 14;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 20;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 26;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 26;
	                	}
	                	else 
	                	{
	                		$peisongfei = 26;
	                	}
	                }
	                else 
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 10;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 16;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else 
	                	{
	                		$peisongfei = 22;
	                	}
	                }
	            }
	            else 
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                //接收用户选择的快递方式
	                $user_md_kd = $_POST['maxzkd'];
	                if (empty($user_md_kd))
	                {
	                	$user_md_kd = 'mdptkd';
	                }
	                if ($user_md_kd == 'mdsfkd')
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 23;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 33;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 43;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 43;
	                	}
	                	else 
	                	{
	                		$peisongfei = 43;
	                	}
	                }
	                else 
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 10;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 16;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else 
	                	{
	                		$peisongfei = 22;
	                	}
	                }
	            }
            }
            
			foreach ($goods_list as $kk=>$v)
			{
				$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
				$rst[] = $GLOBALS['db']->getAll($dajinsql);
			
				foreach ($rst as $vv)
				{
					foreach ($vv as $vvv)
					{
						if ($vvv['cat_id'] == 'AJ')
						{
							$abcdd = '1';
						}
						else if ($vvv['cat_id'] == 'ZD')
						{
							$xxxajaj = '1';
						}
					}
				}
					
			}
			if ( ! empty($abcdd) && ! empty($xxxajaj))
			{
				$peisongfei = 0;
			}
			
		}
		else {
			$ps_address=0;
			$_SESSION['peisongfeiyong']='0';
			//用户选择了使用新地址
			//判断用户是否进行了登录,如登录记录新的收货信息,否则不进行记录
			$sh_name=$_REQUEST['sh_name'];
			$scity_id = $_REQUEST['shr_nomarch'];//收货人城市ID
			
			if(cate_in_list('AM',$goods_list))
			{
				$sd='010';
				$scity_name = '北京市';//收货人城市名称
			}
			else 
			{
				if($scity_id != '1' && $scity_id != '2')
				{
					$sd='010';
				}
				else
				{
					$sd=get_region_id($scity_id);
				}
				$scity_name = get_region($scity_id);//收货人城市名称
			}
			$sq_id=$_REQUEST['shr_area'];//收货人区ID
			$sq_name=get_region($sq_id);//收货人区名称
			//是否有配送费用
			if($_REQUEST['wuhuan']=='1')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='2')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='3')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}else if($_REQUEST['wuhuan']=='4')
			{
				$peisongfei=0;
				$_SESSION['peisongfeiyong']='0';
			}
			
			$cart_goods = get_cart_goods();
			$goods_list=$cart_goods['goods_list'];
			
			/*全国送的产品同时购买一件运费8元两件4元3件或以上0元*/
			foreach ($goods_list as $kk=>$v)
			{
				$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
				$rst[] = $GLOBALS['db']->getAll($dajinsql);
				foreach ($rst as $vv)
				{
					foreach ($vv as $vvv)
					{
						if ($vvv['cat_id'] == 'AJ')
						{
							$ajnum[$kk] = $v['goods_number'];
                            $ajprice[$kk] = $v['shop_price'] * $v['goods_number'];
						}
					}
				}
					
			}
//            echo '<pre>';
//            print_r($ajnum);
//            echo '<br/>';
//            print_r($ajprice);
//            exit;

            if (is_array($ajprice))
            {
                $ajpriceyun = array_sum($ajprice);
            }
            if ($ajpriceyun >= '179')
            {
                $peisongfei = 0;
            }
            else
            {
                if (is_array($ajnum))
                {
                    $yunfeinum = array_sum($ajnum);
                }
                if ( ! empty($yunfeinum))
                {
                    if ($yunfeinum < 2)
                    {
                        $peisongfei = 8;
                    }
                    else if($yunfeinum == 2)
                    {
                        $peisongfei = 4;
                    }
                    else if($yunfeinum > 2)
                    {
                        $peisongfei = 0;
                    }
                    else
                    {
                        $peisongfei = 0;
                    }
                }
            }

            //如果购买一盒甜品北京免运费，其他一盒25元，二盒免运费
            if ($scity_id == 1)
            {
                foreach ($goods_list as $yav)
                {
                    if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
                    {
                        $peisongfei = 0;
                    }
                }
            }
            else
            {
                static $yhtznum = 0;
                foreach ($goods_list as $yav)
                {
                    if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
                    {
                        $yhtznum += $yav['goods_number'];
                    }
                }

                if ($yhtznum == 1)
                {
                    $peisongfei = 25;
                }
                else if ($yhtznum >= 2)
                {
                    $peisongfei = 0;
                }

            }

            /*
			 * 面包运费
			 * 北京上海1个面包10元，2个面包5元，3个面包及以上0元运费。
			 * 北上以外地区，河北，天津1个面包（顺丰14元，普通10元）2个面包（顺丰20元，普通10元）3个面包（顺丰26元，普通22元），4个面包（顺丰26元，普通22元）
			 * 其他地区1个面包（顺丰23元，普通10元）2个面包（顺丰33元，普通16元）3个面包（顺丰43元，普通22元），4个面包（顺丰43元，普通22元））
            */
            $pdbjmb = array('33796','33806','33807','33808');
            foreach ($goods_list as $dyav)
            {
            	if(in_array($dyav['goods_id'], $pdbjmb))
				{
					$pdmdsbsky = 1;
					static $bjmds = 0;
				}    		
            }
            if ( $pdmdsbsky == 1 )
            {
            	if ($scity_id == 1 || $scity_id == 2)
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                if ($bjmds == 1)
	                {
	                	$peisongfei = 10;
	                }
	                else if ($bjmds == 2)
	                {
	                	$peisongfei = 5;
	                }
	                else 
	                {
	                	$peisongfei = 0;
	                }
	            }
	            else if ($scity_id == 80 || $scity_id == 96 )
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                //接收用户选择的快递方式
	                $user_md_kd = $_POST['maxzkd'];
	                if (empty($user_md_kd))
	                {
	                	$user_md_kd = 'mdptkd';
	                }
	                if ($user_md_kd == 'mdsfkd')
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 14;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 20;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 26;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 26;
	                	}
	                	else 
	                	{
	                		$peisongfei = 26;
	                	}
	                }
	                else 
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 10;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 16;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else 
	                	{
	                		$peisongfei = 22;
	                	}
	                }
	            }
	            else 
	            {
	            	foreach ($goods_list as $dyav)
	                {
	                    if ($dyav['goods_id'] == '33796' || $dyav['goods_id'] == '33806' || $dyav['goods_id'] == '33807' || $dyav['goods_id'] == '33808')
	                    {
	                    	$bjmds += $dyav['goods_number'];
	                    }
	                }
	                //接收用户选择的快递方式
	                $user_md_kd = $_POST['maxzkd'];
	                if (empty($user_md_kd))
	                {
	                	$user_md_kd = 'mdptkd';
	                }
	                if ($user_md_kd == 'mdsfkd')
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 23;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 33;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 43;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 43;
	                	}
	                	else 
	                	{
	                		$peisongfei = 43;
	                	}
	                }
	                else 
	                {
	                	if ($bjmds == 1)
	                	{
	                		$peisongfei = 10;
	                	}
	                	else if ($bjmds == 2)
	                	{
	                		$peisongfei = 16;
	                	}
	                	else if ($bjmds == 3)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else if ($bjmds == 4)
	                	{
	                		$peisongfei = 22;
	                	}
	                	else 
	                	{
	                		$peisongfei = 22;
	                	}
	                }
	            }
            }

			foreach ($goods_list as $kk=>$v)
			{
				$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
				$rst[] = $GLOBALS['db']->getAll($dajinsql);
					
				foreach ($rst as $vv)
				{
					foreach ($vv as $vvv)
					{
						if ($vvv['cat_id'] == 'AJ')
						{
							$abcdd = '1';
						}
						else if ($vvv['cat_id'] == 'ZD')
						{
							$xxxajaj = '1';
						}
					}
				}
					
			}
			if ( ! empty($abcdd) && ! empty($xxxajaj))
			{
				$peisongfei = 0;
			}

			//是否填写了收货人邮编
			if($_REQUEST['spost']!="")
			{
				$spost=$_REQUEST['spost'];
			}else
			{
				$spost=0;
			}
			$sxxaddress=$scity_name.$sq_name.$_REQUEST['sxxaddress'];//收货人详细信息
			$smobile=trim($_REQUEST['smobile']);//收货人移动电话
			$sfamily_qh=$_REQUEST['sfamily_qh'];//收货人电话区号
			$sfamily_dh=$_REQUEST['sfamily_dh'];//收货人电话号码
			$sfamily_fj=$_REQUEST['sfamily_fj'];//收货人电话分机号码
			$sfamily_phone=$sfamily_qh."-".$sfamily_dh."-".$sfamily_fj;
		}
		//存储已经登录用户的订货人信息避免反复填写
		if(!(empty($_SESSION["user_id"]))){
			$dh_sqla = "select * from users where user_id=".$_SESSION["user_id"];
			$dh_row = $GLOBALS['db']->getRow($sql);
			if($dh_row){
				$dh_sql = "update users set C_Add='".$sxxaddress."',real_name='".$dh_name."',home_phone='".$dfamily_phone."' where user_id=".$_SESSION["user_id"];
				$GLOBALS['db']->query($dh_sql);
			}
		}
	}
	//判断是否存在配送城市不能购买的商品

	$cant_goods = city_not_buy($sd);
	if($cant_goods)
	{
		$cant_goods_name = '';
		foreach ($cant_goods as $key=>$val)
		{
			$cant_goods_name = $cant_goods_name.$val['goods_name']."<br/>";
		}
		$cant_goods_name = $cant_goods_name."不支持此城市下单，请从购物车中移除后再试!";
		$links = array();
        $links['0']='重新填写';
        $links['1']='返回购物车';
        $hrefs = array();
        $hrefs['0']='/flow_cart.php';
        $hrefs['1']='/flow.php';
		show_message($cant_goods_name,$links,$hrefs);
	}
	
	/*------------------------------------------------------*/
	//-- 获取其他 发票/配送时间/备注等信息
	/*------------------------------------------------------*/
	$needfapiao=$_REQUEST['needfapiao'];    //是否需要发票
	if($needfapiao==1)
	{
		$gs_name=$_REQUEST['gs_name'];
		if($_REQUEST['fp_name']=='0')
		{
			$fp_name="餐费";
		}else if($_REQUEST['fp_name']=='1')
		{
			$fp_name="餐费";
		}
		else if($_REQUEST['fp_name']=='2')
		{
			$fp_name="食品(蛋糕)";
		}
		else if($_REQUEST['fp_name']=='3')
		{
			$fp_name="食品";
		}
		else if($_REQUEST['fp_name']=='5')
		{
			$fp_name="蛋糕";
		}
		
		/*xie获取发票的邮寄地址*/
		$postfpname = $_REQUEST['postfpname'];
		$postfpaddr = $_REQUEST['postfpaddress'];
		$postfptel  = $_REQUEST['postfptell'];
		$postfpzip  = $_REQUEST['postfpzip'];
	}else
	{
		$needfapiao=0;
		$gs_name="";
		$fp_name="";
		$postfpname = '';
		$postfpaddr = '';
		$postfptel  = '';
		$postfpzip  = '';
	}
	//送货时间
	$sh_tiem=$_REQUEST['sh_time'];
	$statime=isset($_REQUEST['statimehour'])?$_REQUEST['statimehour']:"00:00";
	$endtime=isset($_REQUEST['endtiemhour'])?$_REQUEST['endtiemhour']:"00:00";
	$time_pa = "/^(0\d{1}|1\d{1}|2[0-3]):[0-5]\d{1}:([0-5]\d{1})$/";
	if(preg_match($time_pa,$statime)){
		$statime = $statime;
	}
	else
	{
		$statime = '00:00:00';
	}
	if(preg_match($time_pa,$endtime)){
		$endtime = $endtime;
	}
	else
	{
		$endtime = '00:00:00';
	}
	$Deliver_Time=$_REQUEST['sh_time'].' '.$_REQUEST['statimehour'].'--'.$_REQUEST['endtiemhour'];
	
	$order_status = OS_UNCONFIRMED;
	if ($_SESSION['source'])
	{
	    $o_source = $_SESSION['source'];//订单来源
	}
	else
	{
	    $o_source = '网络订单';
	}
	
	$total_shop = $_REQUEST['zhekoujiage']+$_SESSION['append_total_price']+$peisongfei; //市场价不打 折价格
	
	
	if($_REQUEST['is_no_rate']==1)
	{
		$total_fee = $total_shop;
	}
	else 
	{
		$total_fee = $cart_goods['total']['goods_amount']+$_SESSION['append_total_price']+$peisongfei; //计算蛋糕及附件的总金额
	}
	
	//如果是团委的打折的用户则重新计算总价格
	if ( ! empty($_SESSION['user_id']))
	{
		$zhesql = "select is_youhui,zhe_val from users where user_id=".$_SESSION['user_id'];
		$zherst = $GLOBALS['db']->getRow($zhesql);
		if ($zherst['is_youhui'] == 1)
		{
			$total_fee = floor($total_fee * $zherst['zhe_val']);
		}
		
	}

	/**
	 * 中秋活动蛋糕跟月饼同时购买立减100元
	 */
	$cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	$yuebing = array('34067', '34070', '33771', '33772');
	static $aa = 0;
	foreach($goods_list as $yueval)
	{
		if (in_array($yueval['goods_id'], $yuebing))
		{
			$yuecount = 1;
		}

		if (cate_in_list('ZD', $goods_list))
		{
			$zdyuecont = 1;
		}
		$aa += $yueval['goods_price'] * $yueval['goods_number'];
	}
	if ($aa >= 378)
	{
		$aayuecont = 1;
	}
	$zyuecont = $yuecount + $zdyuecont + $aayuecont;
	if ($zyuecont == 3)
	{
		$total_fee = $total_fee - 100;
	}

    /**
     * 牛轧糖活动买一盒原价，2盒第2盒半价，3盒，第2盒半价，第3盒，半价的半价，超过3盒的部分不享受优惠
     */
    /*$cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    $allow_bj_niu = array('33351','33698','33699','33700','33701');
    $niucount = 0;
    static $niujianjia = 0;
    foreach($goods_list as $va)
    {
        if ( in_array($va['goods_id'], $allow_bj_niu))
        {
            $niucount += $va['goods_number'];
        }
    }
    if ($niucount == 2)
    {
        $niujianjia += -30;
    }
    elseif ($niucount == 3)
    {
        $niujianjia += -74;
    }
    elseif ($niucount > 3)
    {
        $niujianjia += -74;
    }
    $total_fee = $total_fee + $niujianjia;*/


    /**
	 * 如果是北京银行用户购买的指定款商品则打7折
	 */
	/*if ( ! empty( $_SESSION['user_id'] ) )
	{
		$bjyhsql = "select user_name from users where user_id=".$_SESSION['user_id'];
		$bjyhrst = $GLOBALS['db']->getOne($bjyhsql);
		if ( $bjyhrst == 'bjbank' )
		{
			//购买指定款商品
            $allow_bj = array('33825','33688','33689','33690','33736', '33737', '33738', '33739', '33657', '33658', '33659', '33660');
			$cart_goods = get_cart_goods();
			$goods_list=$cart_goods['goods_list'];
			$bjbankcarcount = 0;
			
			foreach($goods_list as $va)
			{
				if ( in_array($va['goods_id'], $allow_bj))
				{
					$bjbankcarcount += $va['goods_number'];
				}
			}
			
			//获取购物车商品数量
			$sql = "SELECT goods_number FROM " . $ecs->table('cart') .
			" WHERE session_id = '" . SESS_ID . "' " .
			"AND rec_type = '$flow_type'";
			
			$bjbcartnum = $GLOBALS['db']->getAll($sql);
			foreach ($bjbcartnum as $xxxnum)
			{
				$bjbcartnumshow += $xxxnum['goods_number'];
			}
			
			if ($bjbcartnumshow == $bjbankcarcount)
			{
				$total_fee = floor($total_fee * 0.7);
                $agio_type = '七折';
			}
		}
	}*/


    /**
     * 光大银行购买指定款商品打7折
     */

    if ( ! empty($_SESSION['user_id']))
    {
        $gdsql = "select user_name from users where user_id=".$_SESSION['user_id'];
        $gdrst = $GLOBALS['db']->getOne($gdsql);
        if ($gdrst == 'guangdabank')
        {
            $gd_zhe_val = 0;
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            $allow_gd = array('29366','15218','15219','15220','29442', '7933', '7934', '7935', '29474', '16093', '16094', '16095', '29480', '28585', '28586', '28587', '29375', '7375', '7376', '7377', '29540', '7378', '7379', '7380');
            foreach ($goods_list as $gd_val)
            {
                if (in_array($gd_val['goods_id'], $allow_gd))
                {
                    $gd_zhe_val += $gd_val['goods_price'] * $gd_val['goods_number'] * -0.3;
                }
            }
            $total_fee = $total_fee + $gd_zhe_val;
        }
    }

    /**
     * 国航用户guohangwfboy购买4款特定打8折，其他打8.5折
     */
    if ( ! empty($_SESSION['user_id']))
    {
        $ghsql = "select user_name from users where user_id=".$_SESSION['user_id'];
        $ghrst = $GLOBALS['db']->getOne($ghsql);
        if ($ghrst == 'guohangwfboy')
        {
            static $ghzhe_val = 0;
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            $allow_gh = array('33351','33353','29442','7933','7934', '7935', '29375', '7375', '7376', '7377');
            foreach ($goods_list as $gh_val)
            {
                if (in_array($gh_val['goods_id'], $allow_gh))
                {
                    $ghzhe_val += $gh_val['goods_price'] * $gh_val['goods_number'] * -0.2;
                }
                else
                {
                    $ghzhe_val += $gh_val['goods_price'] * $gh_val['goods_number'] * -0.15;
                }
            }
            $total_fee = floor($total_fee + $ghzhe_val);
        }
    }
    
    /*小熊组合装减3元*/
    /*$cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    static $xshum = 0;
    foreach ($goods_list as $x_val)
    {
    	if ($x_val['goods_id'] == '33819')
    	{
    		$xshum += 1;
    	}
    	if ($x_val['goods_id'] == '33796')
    	{
    		$xshum +=2;
    	}
    	if ($x_val['goods_id'] == '33806')
    	{
    		$xshum +=4;
    	}
    	if ($x_val['goods_id'] == '33807')
    	{
    		$xshum +=6;
    	}
    	if ($x_val['goods_id'] == '33809')
    	{
    		$xshum +=8;
    	}
    }
    if ($xshum == 3)
    {
    	$total_fee = floor($total_fee - 3);
    }
    if ($xshum == 5)
    {
    	$total_fee = floor($total_fee - 8);
    }
    if ($xshum == 7)
    {
    	$total_fee = floor($total_fee - 5);
    }
    if ($xshum == 9)
    {
    	$total_fee = floor($total_fee - 2);
    }*/

    /**
	 * 分销商打折
	 * 合约折扣
	 * 活动起止日期
	 */
	/*if ( ! empty( $_SESSION['user_id'] ) )
	{
		$sql_fenxiao = "select is_fenxiaoshang,fx_moren_dazhe,fx_huodong_start,fx_huodong_end,fx_huodong_dazhe,is_fenxiao_status from users where user_id=".$_SESSION['user_id'];
		$fenxiao_rst = $GLOBALS['db']->getRow($sql_fenxiao);
		//是分销且未禁用
		if ( $fenxiao_rst['is_fenxiaoshang'] == '1' && $fenxiao_rst['is_fenxiao_status'] == '' )
		{
			$n_Time = local_date ();
			if ( $n_Time > $fenxiao_rst['fx_huodong_start'] && $n_Time < $fenxiao_rst['fx_huodong_end'] )
			{
				if ( ! empty( $fenxiao_rst['fx_huodong_dazhe'] ) )
				{
					$total_fee = $total_fee * $fenxiao_rst['fx_huodong_dazhe'];
				}
			}
			else 
			{
				if ( ! empty( $fenxiao_rst['fx_moren_dazhe'] ) )
				{
					$total_fee = $total_fee * $fenxiao_rst['fx_moren_dazhe'];
				}
			}
		}
	}*/
	
	$smarty->assign('zongjine',$total_fee);
	$accept_time = local_date('Y-m-d H:i:s');
	$smarty->assign('odate',$accept_time);
    //如果全国送的产品订单总价超过179元则免运费
    $cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    $fuyan = '';
    foreach ($goods_list as $yav)
    {
        if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
        {
            if ($scity_id == 1)
            {
                $fuyan.='北京';
            }
            else
            {
                $fuyan.='外地';
            }
        }
    }
	$fuyan.=str_replace("'","’",$_REQUEST['fuyanlan']);
	if(isset($_REQUEST['hk_fuyanlan'])||$_REQUEST['hk_fuyanlan']!='')
	{
		$greetnote = $_REQUEST['hk_fuyanlan'];
	}
	/*-------------------------------------------------------*/
	//-- 获取支付方式等参数
	/*-------------------------------------------------------*/
	$fangshi = $_POST ['fangshi'];
	$zhifu = $_POST ['zhifu'];

	if($_POST['other_pay']==2 && is_numeric($_POST ['srchkje']))
	{
		$czkje = $_POST['srchkje'];
	}
	else 
	{
		$czkje = 0;
	}
	//储值卡金额
	$cake_points = $_POST['points'];  //W币
	
	$chuzhikaPwd = $_REQUEST['czkpwd'];
	$chuzhikaPwd = $chuzhikaPwd."*";
	$md5czkpwd = md5($chuzhikaPwd);  //获取储值卡密码
	
	/*-------------------------------------------------------*/
	//--获取用户id 判断用户是否登陆和存在
	/*-------------------------------------------------------*/
	$user_id=$_SESSION["user_id"];
	$isNetLogin = '';//1-已登录    2-未登录  3-qq登陆购买
	//判断用户是否登录
	if($_SESSION['qqlogin']=='1' && $user_id != null && $user_id !='')
	{
		$isNetLogin = 3;
	}
	else if ($user_id != null && $user_id !='')
	{
		$isNetLogin = 1;
	}
	else 
	{
		$isNetLogin = 2;
		//判断用户是否存在
		$sql_u = "select user_id from users where real_name = '$dh_name' and mobile_phone = '$dmobile'";
		$exist_users = $GLOBALS['db']->getOne($sql_u);
		
		if($exist_users != '')
		{
			$user_id = $exist_users;
		}
		else 
		{
			$add_username = "uu_".rand(100, 999)."_".substr($dmobile, 5);
			$add_password = substr($dmobile, 5);
			$add_email = $dmobile.'@139.com';
			$add_dizhi = '北京市';
			if(!$GLOBALS['user']->add_user($add_username, $add_password, $add_email,$add_dizhi,$cardid))
			{
				$sql_in = "select user_id from users where user_name='$add_username' and password = '".md5($add_password)."'";
				$user_id = $GLOBALS['db']->getOne($sql_in);
				if($user_id ='' or  $user_id ='NULL')
				{
					$user_id=0;
				}
			}
			else 
			{
				$sql_in = "select user_id from users where user_name='$add_username' and password = '".md5($add_password)."'";
				$user_id = $GLOBALS['db']->getOne($sql_in);
				$_SESSION['user_id_nologin']=$user_id;
				if($user_id !='' and $user_id !='NULL')
				{
					$sql_up = "update users set mobile_phone='$dmobile',reg_time=GETDATE(),real_name='$dh_name',C_Add='$dxxaddress' where user_id='$user_id'";
					$GLOBALS['db']->query($sql_up);
				}
			}
		}
	}
    /*-------------------------------------------------------*/
	//-- 检查 W币 输入是否符合要求
	/*-------------------------------------------------------*/
	if (isset ( $_POST ['points'] ) && $_POST ['points'] > 0) 
	{
		$max_point = get_user_point ( $_SESSION ['user_id'],"8888888" );
		$cake_points = intval ( $cake_points );
		
		$goods_all_total = $total_shop;
		if ($max_point < $cake_points || $cake_points <0 || $cake_points > $goods_all_total) {
			show_message ( '<p style=" margin:0px; padding:0px; padding-top:20px; margin:0px 5px;font-size:16px; font-weight:bold; color:#990099;">您输入的W币数量有误</p>' );
		}
	}
    /*-------------------------------------------------------*/
	//-- 检查 储值卡 输入是否符合要求
	/*-------------------------------------------------------*/
	$sql = "select czkpwd from users where user_id='".$_SESSION['user_id']."'";
	$userczkpwd = $GLOBALS['db']->getOne($sql);
	
	$userCzk = get_user_account ( $user_id );
	if($czkje > 0 && isset($_POST ['srchkje']))
	{
		if($userczkpwd!=$md5czkpwd || $md5czkpwd =='')
		{
			show_message ( '<p style=" margin:0px; padding:0px; padding-top:20px; margin:0px 5px;font-size:16px; font-weight:bold; color:#990099;">您的储值卡密码不正确</p>' );
		}
		else {
				if ($czkje > $userCzk && $userCzk < $total_shop) {
					show_message ( '<p style=" margin:0px; padding:0px; padding-top:20px; margin:0px 5px;font-size:16px; font-weight:bold; color:#990099;">您的储值卡金额不足</p>' );
				} 
				else if($czkje < 0 || $czkje > $total_shop || $czkje > $userCzk)
				{
					show_message ( '<p style=" margin:0px; padding:0px; padding-top:20px; margin:0px 5px;font-size:16px; font-weight:bold; color:#990099;">您输入储值卡金额有误</p>' );
				}
		}
	}
	/*-------------------------------------------------------*/
	//-- 判断是否多种优惠重复享用
	/*-------------------------------------------------------*/
	if(($_POST ['points'] > 0 && $_POST ['srchkje']>0) || ($_POST ['points'] > 0 && (isset($_REQUEST ['is_bouns'])&&$_REQUEST ['is_bouns']!=0)) || ($_POST ['srchkje']>0 && (isset($_REQUEST ['is_bouns'])&&$_REQUEST ['is_bouns']!=0)) )
	{
		show_message ( '<p style=" margin:0px; padding:0px; padding-top:20px; margin:0px 5px;font-size:16px; font-weight:bold; color:#990099;">对不起，两种优惠(W币/储值卡/优惠券)不能同时使用</p>' );	
	}

    /*推广统计20141125*/
    if ( ! empty($_SESSION['hmsr']))
    {
        $hmsr = $_SESSION['hmsr'];
    }
    if ( ! empty($_SESSION['hmmd']))
    {
        $hmmd = $_SESSION['hmmd'];
    }
    if ( ! empty($_SESSION['hmpl']))
    {
        $hmpl = $_SESSION['hmpl'];
    }
    if ( ! empty($_SESSION['hmkw']))
    {
        $hmkw = $_SESSION['hmkw'];
    }


    /*-------------------------------------------------------*/
	//-- 保存订单
	/*-------------------------------------------------------*/
	$U_Time = local_date ();
	//W币全额
    if($cake_points == $total_shop)
    {
    	$need_total = price_format(($total_shop - $cake_points),true);
    	
    	$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
        ." values".
        "('$sd',$user_id,'$o_source','$scity_name','1','预付费','$total_shop','$need_total','$cake_points','$is_ship_self','$needfapiao','$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
		

        $GLOBALS['db']->query($sql);
        $order_id = $GLOBALS['db']->insert_id($GLOBALS['ecs']->table('order_info'));
        $time = time ();
		$sql = "insert into point_log(user_id, num, type, description, time) values('{$_SESSION['user_id']}', '{$cake_points}', '2', '消费', '{$time}')";
		$GLOBALS ['db']->query ( $sql );
		
		$fangshi = '10';
		
		$Amount = price_format( $need_total,true );
        $smarty->assign ( 'Amount', $Amount );
    }
    //储值卡全额
    elseif($czkje == $total_shop)
    {
		$need_total = $total_shop - $czkje;
		$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,Paid,CardAmount,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
        ." values".
        "('$sd',$user_id,'$o_source','$scity_name','1','预付费','$total_shop','$need_total','$czkje','$czkje','$is_ship_self','$needfapiao','$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
		
        $GLOBALS['db']->query($sql);
        
       // $order_id = $GLOBALS['db']->insert_id($GLOBALS['ecs']->table('order_info'));
        
        $fangshi = '10';
        
        $Amount = price_format( $need_total,true );
        $smarty->assign ( 'Amount', $Amount );
    }
    //货到付款
   elseif($fangshi == '1' && $_REQUEST['paym']==0)
    {
		include_once ('includes/lib_order.php');
		$fangshiname = '您选择的是货到付款，收货时你需要支付';
		
		$need_total = $total_fee;
		$order_total_fee = $total_fee;
		
		//部分W币
		if($cake_points>0)
		{
			if($cake_points < $total_shop){
				$need_total = price_format(($total_shop - $cake_points),true);
				if($cake_points<0)
				{
					$cake_points=0;
				}
			}
			$order_total_fee = $total_shop;
			$czkje =0;
		}
		else if($czkje != $total_shop && $czkje > 0)
		{
			$need_total = price_format(($total_shop - $czkje),true);
			if($czkje<0)
			{
					$czkje=0;
			}
			$order_total_fee = $total_shop;
			$cake_points=0;
		}
		//优惠券
		elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
	    {
	    	$goods_all_total = $total_fee;
			$isEmpty = trim ( $_REQUEST ['input_sn'] );
			if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
				$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
				$bonus_type = $_REQUEST ['input_sn_VoucherType'];
				$bonus_no = trim ( $_REQUEST ['input_sn'] );
				$bonus_id = $_REQUEST ['input_sn_bonus_id'];
			} else {
				$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
				$bonus_value = $_REQUEST [$VoucherTypeID];
				$bonus_type = $_REQUEST ['VoucherType'];
				$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange!=1 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
				
				$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
				$bonus_no = $bonus_arr [0] ['bonus_sn'];
				$bonus_id = $bonus_arr [0] ['bonus_id'];
			}
			$need_total = (($total_shop - $bonus_value));
			
			if($need_total<0)
			{
				$need_total=0;
			}
			$order_total_fee = $total_shop;
			
			/*标记优惠券为已用*/
			$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
			$GLOBALS['db']->query($upsql);
	    }
		
	    $cake_points = empty($cake_points)?0:$cake_points;
	    
		$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
        ." values".
        "('$sd',$user_id,'$o_source','$scity_name','1','货到付款','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
        
        $GLOBALS['db']->query($sql);
        
        $sql="update users set last_time='$U_Time',LastBay_Time='$U_Time' where user_id=".$user_id;
		$GLOBALS['db']->query($sql);
        
        $Amount = price_format( $need_total,true );
        $smarty->assign ( 'Amount', $Amount );
		$smarty->assign ( 'fangshiname', $fangshiname );
        
    }
    elseif($fangshi == '1' && $_REQUEST['paym'] == 1 && $_REQUEST['cardname'] == '银联卡')
    {

    	include_once ('includes/lib_order.php');
    	$fangshiname = '您选择的是货到付款，收货时你需要支付';
    	
    	$need_total = $total_fee;
    	$order_total_fee = $total_fee;
    	
    	//部分W币
    	if($cake_points>0)
    	{
    		if($cake_points < $total_shop){
    			$need_total = price_format(($total_shop - $cake_points),true);
    			if($cake_points<0)
    			{
    				$cake_points=0;
    			}
    		}
    		$order_total_fee = $total_shop;
    		$czkje =0;
    	}
    	else if($czkje != $total_shop && $czkje > 0)
    	{
    		$need_total = price_format(($total_shop - $czkje),true);
    		if($czkje<0)
    		{
    			$czkje=0;
    		}
    		$order_total_fee = $total_shop;
    		$cake_points=0;
    	}
    	//优惠券
    	elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
    	{
    		$goods_all_total = $total_fee;
    		$isEmpty = trim ( $_REQUEST ['input_sn'] );
    		if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
    			$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
    			$bonus_type = $_REQUEST ['input_sn_VoucherType'];
    			$bonus_no = trim ( $_REQUEST ['input_sn'] );
    			$bonus_id = $_REQUEST ['input_sn_bonus_id'];
    		} else {
    			$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
    			$bonus_value = $_REQUEST [$VoucherTypeID];
    			$bonus_type = $_REQUEST ['VoucherType'];
    			$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange!=1 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
    	
    			$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
    			$bonus_no = $bonus_arr [0] ['bonus_sn'];
    			$bonus_id = $bonus_arr [0] ['bonus_id'];
    		}
    		$need_total = (($total_shop - $bonus_value));
    			
    		if($need_total<0)
    		{
    			$need_total=0;
    		}
    		$order_total_fee = $total_shop;
    			
    		/*标记优惠券为已用*/
    		$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
    		$GLOBALS['db']->query($upsql);
    	}
    	
    	$cake_points = empty($cake_points)?0:$cake_points;
    	 
    	$sql = "insert into ".$GLOBALS['ecs']->table('order_info')."
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
    			." values".
    			"('$sd',$user_id,'$o_source','$scity_name','1','货到付款','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
    	
    	$GLOBALS['db']->query($sql);
    	
    	$sql="update users set last_time='$U_Time',LastBay_Time='$U_Time' where user_id=".$user_id;
    	$GLOBALS['db']->query($sql);
    	
    	$Amount = price_format( $need_total,true );
    	$smarty->assign ( 'Amount', $Amount );
    	$smarty->assign ( 'fangshiname', $fangshiname );
    	
    	
    }
    elseif($fangshi == '1' && $_REQUEST['paym'] == 1 && $_REQUEST['cardname'] == '资和信商通卡')
    {
    	include_once ('includes/lib_order.php');
    	$fangshiname = '您选择的是货到付款资和信商通卡，收货时你需要支付';
    	 
    	$need_total = $total_fee;
    	$order_total_fee = $total_fee;
    	 
    	//部分W币
    	if($cake_points>0)
    	{
    		if($cake_points < $total_shop){
    			$need_total = price_format(($total_shop - $cake_points),true);
    			if($cake_points<0)
    			{
    				$cake_points=0;
    			}
    		}
    		$order_total_fee = $total_shop;
    		$czkje =0;
    	}
    	else if($czkje != $total_shop && $czkje > 0)
    	{
    		$need_total = price_format(($total_shop - $czkje),true);
    		if($czkje<0)
    		{
    			$czkje=0;
    		}
    		$order_total_fee = $total_shop;
    		$cake_points=0;
    	}
    	//优惠券
    	elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
    	{
    		$goods_all_total = $total_fee;
    		$isEmpty = trim ( $_REQUEST ['input_sn'] );
    		if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
    			$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
    			$bonus_type = $_REQUEST ['input_sn_VoucherType'];
    			$bonus_no = trim ( $_REQUEST ['input_sn'] );
    			$bonus_id = $_REQUEST ['input_sn_bonus_id'];
    		} else {
    			$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
    			$bonus_value = $_REQUEST [$VoucherTypeID];
    			$bonus_type = $_REQUEST ['VoucherType'];
    			$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange!=1 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
    			 
    			$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
    			$bonus_no = $bonus_arr [0] ['bonus_sn'];
    			$bonus_id = $bonus_arr [0] ['bonus_id'];
    		}
    		$need_total = (($total_shop - $bonus_value));
    		 
    		if($need_total<0)
    		{
    			$need_total=0;
    		}
    		$order_total_fee = $total_shop;
    		 
    		/*标记优惠券为已用*/
    		$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
    		$GLOBALS['db']->query($upsql);
    	}
    	 
    	$cake_points = empty($cake_points)?0:$cake_points;
    	
    	$sql = "insert into ".$GLOBALS['ecs']->table('order_info')."
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
    			." values".
    			"('$sd',$user_id,'$o_source','$scity_name','1','商务通资和信','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
    	 
    	$GLOBALS['db']->query($sql);
    	 
    	$sql="update users set last_time='$U_Time',LastBay_Time='$U_Time' where user_id=".$user_id;
    	$GLOBALS['db']->query($sql);
    	 
    	$Amount = price_format( $need_total,true );
    	$smarty->assign ( 'Amount', $Amount );
    	$smarty->assign ( 'fangshiname', $fangshiname );
    	 
    }
    elseif($fangshi == '1' && $_REQUEST['paym'] == 1 && $_REQUEST['cardname'] == '北京银行卡')
    {
    	include_once ('includes/lib_order.php');
    	$fangshiname = '您选择的是货到付款北京银行卡，收货时你需要刷卡支付';

    	$bjyhsql = "select user_name from users where user_id=".$_SESSION['user_id'];
    	$bjyhrst = $GLOBALS['db']->getOne($bjyhsql);
    	if ( $bjyhrst == 'bjbank' )
    	{
            $need_total = $total_fee;
            $order_total_fee = $total_fee;
    	}
        else
        {
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            static $bjnozhe = 0;
            foreach ( $goods_list as $bjcart_val )
            {
                if ( $bjcart_val['goods_id'] == '32343' || $bjcart_val['goods_id'] == '32344' || $bjcart_val['goods_id'] == '32346' )
                {
                    $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * 0;
                }
                else
                {
                    $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * -0.2;
                    $agio_type = '八折';
                }
            }
            $need_total = floor($total_fee + $bjnozhe);
            $order_total_fee = floor($total_fee + $bjnozhe);

        }

    	//部分W币
    	if($cake_points>0)
    	{
    		if($cake_points < $total_shop){
    			$need_total = price_format(($total_shop - $cake_points),true);
    			if($cake_points<0)
    			{
    				$cake_points=0;
    			}
    		}
    		$order_total_fee = $total_shop;
    		$czkje =0;
    	}
    	else if($czkje != $total_shop && $czkje > 0)
    	{
    		$need_total = price_format(($total_shop - $czkje),true);
    		if($czkje<0)
    		{
    			$czkje=0;
    		}
    		$order_total_fee = $total_shop;
    		$cake_points=0;
    	}
    	//优惠券
    	elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
    	{
    		$goods_all_total = $total_fee;
    		$isEmpty = trim ( $_REQUEST ['input_sn'] );
    		if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
    			$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
    			$bonus_type = $_REQUEST ['input_sn_VoucherType'];
    			$bonus_no = trim ( $_REQUEST ['input_sn'] );
    			$bonus_id = $_REQUEST ['input_sn_bonus_id'];
    		} else {
    			$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
    			$bonus_value = $_REQUEST [$VoucherTypeID];
    			$bonus_type = $_REQUEST ['VoucherType'];
    			$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange!=1 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
    
    			$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
    			$bonus_no = $bonus_arr [0] ['bonus_sn'];
    			$bonus_id = $bonus_arr [0] ['bonus_id'];
    		}
    		$need_total = (($total_shop - $bonus_value));
    		 
    		if($need_total<0)
    		{
    			$need_total=0;
    		}
    		$order_total_fee = $total_shop;
    		 
    		/*标记优惠券为已用*/
    		$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
    		$GLOBALS['db']->query($upsql);
    	}
    
    	$cake_points = empty($cake_points)?0:$cake_points;
    	 
    	$sql = "insert into ".$GLOBALS['ecs']->table('order_info')."
    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
    			." values".
    			"('$sd',$user_id,'$o_source','$scity_name','1','货到付款刷北京银行卡','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
    
    	$GLOBALS['db']->query($sql);
    
    	$sql="update users set last_time='$U_Time',LastBay_Time='$U_Time' where user_id=".$user_id;
    	$GLOBALS['db']->query($sql);
    
    	$Amount = price_format( $need_total,true );
    	$smarty->assign ( 'Amount', $Amount );
    	$smarty->assign ( 'fangshiname', $fangshiname );
    
    }
    elseif ($fangshi == '3') {
    	//网银在线
    	$need_total = $total_fee;
    	$order_total_fee = $total_fee;
    	
    	if ($zhifu == '6') {
			//部分W币
			if($cake_points>0)
			{
				if($cake_points < $total_shop){
					$need_total = price_format(($total_shop - $cake_points),true);
					if($cake_points<0)
					{
						$cake_points=0;
					}
				}
				$order_total_fee = $total_shop;
				$czkje =0;
			}
			else if($czkje != $total_shop && $czkje > 0)
			{
				$need_total = price_format(($total_shop - $czkje),true);
				if($czkje<0)
				{
						$czkje=0;
				}
				$order_total_fee = $total_shop;
				$cake_points=0;
			}
	    	//优惠券
			elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
		    {
		    	$goods_all_total = $total_fee;
				$isEmpty = trim ( $_REQUEST ['input_sn'] );
				if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
					$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
					$bonus_type = $_REQUEST ['input_sn_VoucherType'];
					$bonus_no = trim ( $_REQUEST ['input_sn'] );
					$bonus_id = $_REQUEST ['input_sn_bonus_id'];
				} else {
					$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
					$bonus_value = $_REQUEST [$VoucherTypeID];
					$bonus_type = $_REQUEST ['VoucherType'];
					$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
					
					$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
					$bonus_no = $bonus_arr [0] ['bonus_sn'];
					$bonus_id = $bonus_arr [0] ['bonus_id'];
				}
				$need_total = (($total_shop - $bonus_value));
	
				if($need_total<0)
				{
					$need_total=0;
				}
				$order_total_fee = $total_shop;
				
				/*标记优惠券为已用*/
				$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
				$GLOBALS['db']->query($upsql);
		    }
		    
		    $cake_points = empty($cake_points)?0:$cake_points;
			
			$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
	        ." values".
	        "('$sd',$user_id,'$o_source','$scity_name','0','网银在线','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
	        
	        $GLOBALS['db']->query($sql);
		}
		//支付宝支付
		if ($zhifu == '5') {
			$need_total = $total_fee;
			$order_total_fee = $total_fee;
			//部分W币
			if($cake_points>0)
			{
				if($cake_points < $total_shop){
					$need_total = price_format(($total_shop - $cake_points),true);
					if($cake_points<0)
					{
						$cake_points=0;
					}
				}
				$order_total_fee = $total_shop;
				$czkje =0;
			}
			else if($czkje != $total_shop && $czkje > 0)
			{
				$need_total = price_format(($total_shop - $czkje),true);
				if($czkje<0)
				{
						$czkje=0;
				}
				$order_total_fee = $total_shop;
				$cake_points=0;
			}
			//优惠券
			elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
		    {
		    	$goods_all_total = $total_fee;
				$isEmpty = trim ( $_REQUEST ['input_sn'] );
				if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
					$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
					$bonus_type = $_REQUEST ['input_sn_VoucherType'];
					$bonus_no = trim ( $_REQUEST ['input_sn'] );
					$bonus_id = $_REQUEST ['input_sn_bonus_id'];
				} else {
					$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
					$bonus_value = $_REQUEST [$VoucherTypeID];
					$bonus_type = $_REQUEST ['VoucherType'];
					$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
					
					$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
					$bonus_no = $bonus_arr [0] ['bonus_sn'];
					$bonus_id = $bonus_arr [0] ['bonus_id'];
				}
				$need_total = (($total_shop - $bonus_value));
	
				if($need_total<0)
				{
					$need_total=0;
				}
				$order_total_fee = $total_shop;
				
				/*标记优惠券为已用*/
				$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
				$GLOBALS['db']->query($upsql);
		    }
			
		    $cake_points = empty($cake_points)?0:$cake_points;
		    
			$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
	        ." values".
	        "('$sd',$user_id,'$o_source','$scity_name','0','支付宝支付','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
	        
	        $GLOBALS['db']->query($sql);
			
		}
        //银盈通
        if ($zhifu == '4') {
            $need_total = $total_fee;
            $order_total_fee = $total_fee;
            //部分W币
            if($cake_points>0)
            {
                if($cake_points < $total_shop){
                    $need_total = price_format(($total_shop - $cake_points),true);
                    if($cake_points<0)
                    {
                        $cake_points=0;
                    }
                }
                $order_total_fee = $total_shop;
                $czkje =0;
            }
            else if($czkje != $total_shop && $czkje > 0)
            {
                $need_total = price_format(($total_shop - $czkje),true);
                if($czkje<0)
                {
                    $czkje=0;
                }
                $order_total_fee = $total_shop;
                $cake_points=0;
            }
            //优惠券
            elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
            {
                $goods_all_total = $total_fee;
                $isEmpty = trim ( $_REQUEST ['input_sn'] );
                if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
                    $bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
                    $bonus_type = $_REQUEST ['input_sn_VoucherType'];
                    $bonus_no = trim ( $_REQUEST ['input_sn'] );
                    $bonus_id = $_REQUEST ['input_sn_bonus_id'];
                } else {
                    $VoucherTypeID = $_REQUEST ['VoucherTypeID'];
                    $bonus_value = $_REQUEST [$VoucherTypeID];
                    $bonus_type = $_REQUEST ['VoucherType'];
                    $sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";

                    $bonus_arr = $GLOBALS ['db']->getAll ( $sql );
                    $bonus_no = $bonus_arr [0] ['bonus_sn'];
                    $bonus_id = $bonus_arr [0] ['bonus_id'];
                }
                $need_total = (($total_shop - $bonus_value));

                if($need_total<0)
                {
                    $need_total=0;
                }
                $order_total_fee = $total_shop;

                /*标记优惠券为已用*/
                $upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
                $GLOBALS['db']->query($upsql);
            }

            $cake_points = empty($cake_points)?0:$cake_points;

            $sql = "insert into ".$GLOBALS['ecs']->table('order_info')."
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
                ." values".
                "('$sd',$user_id,'$o_source','$scity_name','0','银盈通支付','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";

            $GLOBALS['db']->query($sql);

        }
		//先锋支付
		if ($zhifu == '9') {
            $need_total = floor($total_fee * 0.9);
            $order_total_fee = floor($total_fee * 0.9);
            $agio_type = '九折';
            //先要判断是否购物车中相关商品
            /*$cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            foreach ($goods_list as $xvv)
            {

                static $xjian = '0';

                if ($xvv['goods_id'] == '33351' || $xvv['goods_id'] == '33353' || $xvv['goods_id'] == '29442' || $xvv['goods_id'] == '7933' || $xvv['goods_id'] == '7934' || $xvv['goods_id'] == '7935' || $xvv['goods_id'] == '29375' || $xvv['goods_id'] == '7375' || $xvv['goods_id'] == '7376' || $xvv['goods_id'] == '7377')
                {
                    $xjian += $xvv['goods_price'] * '-0.2';
                }
                else if ($xvv['goods_id'] != '33351' && $xvv['goods_id'] != '33353' && $xvv['goods_id'] != '29442' && $xvv['goods_id'] != '29375')
                {
                    $xjian += $xvv['goods_price'] * '-0.1';

                }
            }
            $need_total = $total_fee + $xjian;
            $order_total_fee = $total_fee + $xjian;
            */
			//部分W币
			if($cake_points>0)
			{
				if($cake_points < $total_shop){
					$need_total = price_format(($total_shop - $cake_points),true);
					if($cake_points<0)
					{
						$cake_points=0;
					}
				}
				$order_total_fee = $total_shop;
				$czkje =0;
			}
			else if($czkje != $total_shop && $czkje > 0)
			{
				$need_total = price_format(($total_shop - $czkje),true);
				if($czkje<0)
				{
					$czkje=0;
				}
				$order_total_fee = $total_shop;
				$cake_points=0;
			}
			//优惠券
			elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
			{
				$goods_all_total = $total_fee;
				$isEmpty = trim ( $_REQUEST ['input_sn'] );
				if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
					$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
					$bonus_type = $_REQUEST ['input_sn_VoucherType'];
					$bonus_no = trim ( $_REQUEST ['input_sn'] );
					$bonus_id = $_REQUEST ['input_sn_bonus_id'];
				} else {
					$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
					$bonus_value = $_REQUEST [$VoucherTypeID];
					$bonus_type = $_REQUEST ['VoucherType'];
					$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
						
					$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
					$bonus_no = $bonus_arr [0] ['bonus_sn'];
					$bonus_id = $bonus_arr [0] ['bonus_id'];
				}
				$need_total = (($total_shop - $bonus_value));
		
				if($need_total<0)
				{
					$need_total=0;
				}
				$order_total_fee = $total_shop;
		
				/*标记优惠券为已用*/
				$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
				$GLOBALS['db']->query($upsql);
			}
				
			$cake_points = empty($cake_points)?0:$cake_points;
		
			$sql = "insert into ".$GLOBALS['ecs']->table('order_info')."
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
					." values".
					"('$sd',$user_id,'$o_source','$scity_name','0','先锋支付','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
			 
			$GLOBALS['db']->query($sql);
				
		}
		if ($zhifu == '7')
		{
			$need_total = $total_fee;
			$order_total_fee = $total_fee;
			//部分W币
			if($cake_points>0)
			{
				if($cake_points < $total_shop){
					$need_total = price_format(($total_shop - $cake_points),true);
					if($cake_points<0)
					{
						$cake_points=0;
					}
				}
				$order_total_fee = $total_shop;
				$czkje =0;
			}
			else if($czkje != $total_shop && $czkje > 0)
			{
				$need_total = price_format(($total_shop - $czkje),true);
				if($czkje<0)
				{
						$czkje=0;
				}
				$order_total_fee = $total_shop;
				$cake_points=0;
			}
			
			//优惠券
			elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
		    {
		    	$goods_all_total = $total_fee;
				$isEmpty = trim ( $_REQUEST ['input_sn'] );
				if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
					$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
					$bonus_type = $_REQUEST ['input_sn_VoucherType'];
					$bonus_no = trim ( $_REQUEST ['input_sn'] );
					$bonus_id = $_REQUEST ['input_sn_bonus_id'];
				} else {
					$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
					$bonus_value = $_REQUEST [$VoucherTypeID];
					$bonus_type = $_REQUEST ['VoucherType'];
					$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
					
					$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
					$bonus_no = $bonus_arr [0] ['bonus_sn'];
					$bonus_id = $bonus_arr [0] ['bonus_id'];
				}
				$need_total = (($total_shop - $bonus_value));
	
				if($need_total<0)
				{
					$need_total=0;
				}
				$order_total_fee = $total_shop;
				
				/*标记优惠券为已用*/
				$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
				$GLOBALS['db']->query($upsql);
		    }
			
		    $cake_points = empty($cake_points)?0:$cake_points;
		    
			$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
	        ." values".
	        "('$sd',$user_id,'$o_source','$scity_name','0','财付通','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
	        
	        $GLOBALS['db']->query($sql);
	       
		}
		//浦发银行
		if($zhifu == '8')
		{
			$sql_pf = "select pufa from pufa";
			$max_money = $GLOBALS['db']->getOne($sql_pf);
			
			//优惠的价格
			$yh_num = floor($order_total_fee/98);
			if($yh_num<1)
			{
				$yh_num=0;
			}
			else if($yh_num>=1 && $yh_num<2)
			{
				$yh_num=1;
			}
			else if($yh_num>=2)
			{
				$yh_num=2;
			}
			
			$max_money = $max_money + ($yh_num*20);
			
			if($max_money<=100000)
			{
				$sql_upf = "update pufa set pufa='$max_money'";
				$GLOBALS['db']->query($sql_upf);
				$order_total_fee = $total_shop-($yh_num*20);
				$need_total = $order_total_fee;
				$is_pufa_yh = 1;
				
				$fuyan = "【浦发支付活动】".$fuyan;
			}
			else 
			{
				$need_total = $total_fee;
				$order_total_fee = $total_fee;
				//部分W币
				if($cake_points>0)
				{
					if($cake_points < $total_shop){
						$need_total = price_format(($total_shop - $cake_points),true);
						if($cake_points<0)
						{
							$cake_points=0;
						}
					}
					$order_total_fee = $total_shop;
					$czkje =0;
				}
				else if($czkje != $total_shop && $czkje > 0)
				{
					$need_total = price_format(($total_shop - $czkje),true);
					if($czkje<0)
					{
							$czkje=0;
					}
					$order_total_fee = $total_shop;
					$cake_points=0;
				}
				
				//优惠券
				elseif(isset($_REQUEST ['is_bouns']) && $_REQUEST ['is_bouns'])
			    {
			    	$goods_all_total = $total_fee;
					$isEmpty = trim ( $_REQUEST ['input_sn'] );
					if (isset ( $_REQUEST ['input_sn'] ) && ! empty ( $isEmpty )&&$_REQUEST['hi_result']>0) {
						$bonus_value = $_REQUEST ['input_sn_VoucherAmount'];
						$bonus_type = $_REQUEST ['input_sn_VoucherType'];
						$bonus_no = trim ( $_REQUEST ['input_sn'] );
						$bonus_id = $_REQUEST ['input_sn_bonus_id'];
					} else {
						$VoucherTypeID = $_REQUEST ['VoucherTypeID'];
						$bonus_value = $_REQUEST [$VoucherTypeID];
						$bonus_type = $_REQUEST ['VoucherType'];
						$sql = "select bonus_id, bonus_sn from user_bonus u,VoucherType v where u.VoucherTypeID=v.VoucherTypeID and v.IsChange=2 and u.user_id=" . $_SESSION ['user_id'] . " and u.bonus_states=5 and GETDATE()>u.use_start_date and GETDATE()<u.use_end_date order by u.bonus_sn";
						
						$bonus_arr = $GLOBALS ['db']->getAll ( $sql );
						$bonus_no = $bonus_arr [0] ['bonus_sn'];
						$bonus_id = $bonus_arr [0] ['bonus_id'];
					}
					$need_total = (($total_shop - $bonus_value));
		
					if($need_total<0)
					{
						$need_total=0;
					}
					$order_total_fee = $total_shop;
					
					/*标记优惠券为已用*/
					$upsql = "update user_bonus set web_use=1,bonus_states=6,U_Time=GETDATE() where bonus_sn='$bonus_no' and bonus_id='$bonus_id'";
					$GLOBALS['db']->query($upsql);
			    }
				
			    $cake_points = empty($cake_points)?0:$cake_points;
			}
			$sql = "insert into ".$GLOBALS['ecs']->table('order_info')." 
	    	(City_ID,user_id,O_Source,CityName,order_status,Bala_Mode,Total_Fee,cash,point,Paid,CardAmount,VoucherType,VoucherAmount,VoucherNo,is_ship_self,Invoice_Flag,inv_payee,inv_content,Accept_Time,U_Time,pay_status,isNetLogin,PostName,PostTel,PostAddress,PostZip,hmsr,hmmd,hmpl,hmkw)"
	        ." values".
	        "('$sd',$user_id,'$o_source','$scity_name','0','浦发支付','$order_total_fee','$need_total','$cake_points','$czkje','$czkje','$bonus_type','$bonus_value','$bonus_no',$is_ship_self,$needfapiao,'$gs_name','$fp_name','$accept_time','$U_Time',1,'$isNetLogin','$postfpname','$postfptel','$postfpaddr','$postfpzip','$hmsr','$hmmd','$hmpl','$hmkw')";
	        
	        $GLOBALS['db']->query($sql);
		}
    }
    
    //取出刚插入的ID


    if($cake_points != $total_fee || $total_fee==0)
    {
	    $order_id = $GLOBALS['db']->insert_id($GLOBALS['ecs']->table('order_info'));	
    }



    //把商品从购物车转到PAInfo
    $sql = "SELECT a.*,b.Cake_Pound,b.goods_thumb,b.shop_price,b.unit,b.cat_id " .
        " FROM cart a,goods b " .
        " WHERE a.goods_id=b.goods_id and b.lang_id=".LANG_ID." and a.session_id = '" . SESS_ID . "' AND a.rec_type = '" . CART_GENERAL_GOODS . "' " ." and parent_id=0 and a.is_real=1"." ORDER BY rec_id";  //(a.is_gift=0 or b.integral>0) and 

    $goods_list = $GLOBALS['db']->getAll($sql);
    
    //更新用户信息
    if($user_id!='' && $user_id !=0) 
    {
    	$last_time = local_date();
    	$sql_u = "update users set last_time='$last_time' where user_id ='$user_id'";
    	$GLOBALS['db']->query($sql_u);
    }
	//订货人信息表
    $tel3 = "";//小灵通
    $note = "";//备注
	$card_id = '';//卡号
	if($_SESSION[user_id]>0)
	{
		$sql="select Card_ID from users where user_id=".$user_id;
		$card_id=$GLOBALS['db']->getOne($sql);
	}
	
	$sql = "insert into ".$GLOBALS['ecs']->table('Subscriber')." (order_id,City_ID,user_id,Card_ID,Name,C_Add,Tel1,Tel2,Tel3,Note) "
        ."values('$order_id','$sd','$user_id','$card_id','$dh_name','$sxxaddress','$dmobile','$dfamily_phone','$tel3','$fuyan')";
    $GLOBALS['db']->query($sql);

	//收货人信息表
	$Curr_State = 1;//当前状态
	$note="";
	//判断是不是热卖产品
	//if(in_cate_ZA('AR',$goods_list)&&$sd!='021')
	$goods_id_arr = array();
	foreach ($goods_list as $goods)
    {
    	array_push($goods_id_arr,$goods['goods_id']);
    }
    $goods_id_arrs = implode(',', $goods_id_arr);

	if(in_cate_arr('AR',$goods_id_arrs)&&$sd!='021')
	{
		$Curr_State=3;
	}
	else
	{
		$Curr_State=1;
	}
	//判断是刷什么卡还是现金
	$cc_fuyan='';
	if(isset($_REQUEST['paym'])&&$_REQUEST!=''&&$fangshi == '1')
	{
		if($_REQUEST['paym']==0)//现金支付
		{
			$cc_fuyan=$fuyan."【现金支付】";	
		}
		if($_REQUEST['paym']==1)
		{
			$cardname = isset($_REQUEST['cardname'])?$_REQUEST['cardname']:'银联卡';
			$cc_fuyan = $fuyan."【刷卡支付--".$cardname."】";
		}
	}
	else 
	{
		$cc_fuyan = $fuyan;
	}

	//面包用户选择的是哪一种快递方式则备注
	if ( ! empty($user_md_kd))
	{
		if ($user_md_kd == 'mdsfkd')
		{
			$cc_fuyan = $cc_fuyan.'发顺丰快递';
		}
		else 
		{
			$cc_fuyan = $cc_fuyan.'发普通快递';
		}
	}

	//储值卡活动填写推荐人信息
	$czkhd = trim($_POST['chuzhihd_1603']);
	if ($czkhd)	
	{
		$cc_fuyan = $cc_fuyan.$czkhd;
	}
	
	//判断是否是新用户则送礼物
	/*if ( ! empty($dmobile))
	{	
		$nesql = "select user_id from users where mobile_phone="."'$dmobile'" ."and erp_id > 0";
		$nerst = $GLOBALS['db']->getOne($nesql);
		if ( empty($nerst) )
		{
			$xxsong_array = array();
			$xxsong = '';
			if( ! in_area_w('AJ',$goods_list))
			{
				$fuyan = $fuyan.'新用户'.$dh_name;
			}
		}
	}*/
	
	$sql = "insert into ".$GLOBALS['ecs']->table('CCInfo')." (BayUseType,greetnote,City_ID,order_id,Curr_State,Name,Address,Tel1,Tel2,Tel3,Deliver_Fee,Deliver_Date,Deliver_Time,Deliver_Time2,Note,Total_Fee) "
            ."values('$dgyt','$greetnote','$sd','$order_id','$Curr_State','$sh_name','$sxxaddress','$smobile','$sfamily_phone','$tel3','$Deliver_Fee','$sh_tiem','$statime','$endtime','$cc_fuyan','$order_total_fee')";

            $GLOBALS['db']->query($sql);
	$Consignee_ID = $GLOBALS['db']->insert_id($GLOBALS['ecs']->table('CCInfo'));
    //todo 现在把收获流水号更新为收货表ID，以后可能两个字段不一样。
    //$sql = "update ".$GLOBALS['ecs']->table('CCInfo')." set Consignee_No=str(".$Consignee_ID.") where Consignee_ID=".$Consignee_ID;
    //$GLOBALS['db']->query($sql);

	$_SESSION["CURRENT_ORDER_ID"] = $order_id; //把订单ID放到session中，在flow_confirm.php页面使用
	//判断是否要记录这个收货人信息
	if($_REQUEST['rd_self']==0 && $_REQUEST['ps_address']==0 && $user_id!=0)
	{
		$sql="select count(*) from user_address where user_id=$user_id and address='$sxxaddress'";
		if($GLOBALS['db']->getOne($sql)<1)
		{
			$sql="insert into user_address (user_id,consignee,city,district,address,tel,mobile) values (".$user_id.",'".$sh_name."',".$scity_id.",".$sq_id.",'".$sxxaddress."','".$sfamily_phone."','".$smobile."')";
			$GLOBALS['db']->query($sql);
		}
	}

	$temp_top_good = 0;
    foreach ($goods_list as $goods)
    {
    	$temp_top_good = $temp_top_good+1;
    	
        $goods_id = $goods['goods_id'];
        $pound = isset($goods['Cake_Pound'])?$goods['Cake_Pound']:0;
        
        $number = $goods['goods_number'];
        
        $unit = $goods['Unit'];
        $work_state = 1;//工单状态，todo
        $esp_request = $fuyan;//特殊需求
        $cake_type = $goods['cat_id'];//蛋糕类型
        require_once(dirname(__FILE__) . '/includes/no_vip_price_goods.php');
        $discount = '';
        
	    //设置用户折扣类型
	    $no_vip_price = no_vip_price();
	    
    	if($is_pufa_yh == 1 && $temp_top_good == 1)
		{
			$agio_type = '自定义';
		}
	    else if($user_id==0)
		{
			$agio_type ='无折扣';
		}
		else if ($is_pufa_yh == 1)
		{
			$agio_type ='无折扣';
		}
		else if($_REQUEST['is_no_rate']==1)
		{
			$agio_type ='无折扣';
		}
		/*else
		{
			//判断是否优惠活动（赠品）
			$sql = "select goods_id from cart where user_id='$_SESSION[user_id]' and session_id='".SESS_ID."' and is_gift !=0";
			$ggd_id = $GLOBALS['db']->getOne($sql);
			if($goods_id==$ggd_id)
			{
				$agio_type='优惠活动(赠品)';
			}
			else if(in_array($goods_id, $no_vip_price))
			{
				$agio_type = '无折扣';
			}
			else
			{
				$agio_type =$_SESSION['discount_name'];
			}

		}*/

		//如果是团委的订单则在painfo表中agio_type字段则要标明是几折
		if ( ! empty($_SESSION['user_id']))
		{
			$zhe2sql = "select is_youhui,zhe_name from users where user_id=".$_SESSION['user_id'];
			$zhe2rst = $GLOBALS['db']->getRow($zhe2sql);
			
			if ($zhe2rst['is_youhui'] == 1)
			{
				$agio_type = $zhe2rst['zhe_name'];
			}
		}
		
    	if($is_pufa_yh == 1 && $temp_top_good == 1)
		{
			$receivable = ($goods['goods_price']*$number)-($yh_num*20);//应收金额
		}
		else if(!in_array($goods_id, $no_vip_price)){
			//判断是否优惠活动（赠品）
			$sql = "select goods_id from cart where user_id='$_SESSION[user_id]' and session_id='".SESS_ID."' and is_gift !=0";
			$ggd_id = $GLOBALS['db']->getOne($sql);
			if($goods_id==$ggd_id)
			{
				$receivable = intval($goods['goods_price']* $number);//应收金额
			}
			else 
			{
				if($_REQUEST['is_no_rate']==1)
				{
					$receivable = intval($goods['goods_price']* $number);//应收金额
				}
				else 
				{
        			$receivable = intval($goods['goods_price']* $number*$_SESSION['discount']);//应收金额
				}
			}
		}
		else 
		{
			$receivable = ($goods['goods_price']* $number);//应收金额
		}
	    //判断是不是热卖产品
		//if(in_cate_ZA('AR',$goods_list)&&$sd!='021')
		if(in_cate_arr('AR',$goods_id)&&$sd!='021')
		{
			$work_state=5;
			$Use_Fini = 1;
		}
		else 
		{
			$work_state=1;
			$Use_Fini = 0;
		}

        $sql = "insert into ".$GLOBALS['ecs']->table('PAInfo')." (City_ID,order_id,Consignee_ID,Work_State,goods_id,Pound,Cake_Type,Unit,Number,Esp_Request,Agio_Type,Receivable,Use_Fini) "
            ."values('$sd',$order_id,$Consignee_ID,$work_state,$goods_id,$pound,'$cake_type','$unit',$number,'$esp_request','$agio_type','$receivable','$Use_Fini')";
        // print($sql);exit;
	    $GLOBALS['db']->query($sql);
        $PAInfo_ID = $GLOBALS['db']->insert_id($GLOBALS['ecs']->table('PAInfo'));

        //附件
		$sql="select c.*,g.* from cart c,goods g where c.session_id='".SESS_ID."' and c.parent_id=".$goods_id." and c.goods_id=g.goods_id";

        $append_list = $GLOBALS['db']->getAll($sql);


		if(count($append_list)>0)
		{
			foreach ($append_list as $append)
			{
				$goods_id = $append['goods_id'];
				$app_number = $append['goods_number'];

				//查询对应商品的对应附件的默认数量
				$sql="select App_Num from DefaultApp where goods_id=".$append['parent_id']." and DefaultAppNo=".$goods_id;
				$default_app_num=$GLOBALS['db']->getOne($sql);

				//实际附件数量
				$true_app_num=$app_number-$default_app_num*$number;
				$total =floor($true_app_num*$append['goods_price']);

				$sql = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
					."values('$sd',$PAInfo_ID,$order_id,$goods_id,$app_number,$total)";
				$GLOBALS['db']->query($sql);
			}
		}
		//用途为生日曾默认添加生日附件
		$sql_pc = "select goods_id from goods where cat_id in ('ABAF','ABAI','ABAH','ZDAA')";
		$sql_pc_1 = $GLOBALS['db']->getAll($sql_pc);
		$sql_pc = "select goods_id from goods_cat where cat_id in ('ABAF','ABAI','ABAH','ZDAA')";
		$sql_pc_2 = $GLOBALS['db']->getAll($sql_pc);
		$pc__gd = array_merge($sql_pc_1,$sql_pc_2);
		$pc__gd_a = array();
		foreach ($pc__gd as $key=>$val)
		{
			array_push($pc__gd_a,$val['goods_id']);
		}
		
		$czk_goods = czk_goods();
		if($dgyt=='生日' && !in_array($goods['goods_id'], $czk_goods) && !in_array($goods['goods_id'],$pc__gd_a))
		{
			$_sql = "select * from AppendInfo where Indent_No='$order_id' and Append_No='32291'";
			$_res = $GLOBALS['db']->getRow($_sql);
			if($_res == '')
			{
				$sql = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
						."values('$sd',$PAInfo_ID,$order_id,'32291',1,0)";
				$GLOBALS['db']->query($sql);
			}
		}
		
    }
	
    
    /*收集三个生日*/
    if ( ! empty($_SESSION['user_id']))
    {
    	//关系一
    	$myguanxi1 = trim($_REQUEST['myguanxi1']);
    	$myguanxi1 =preg_replace("/\s/","",$myguanxi1);
    	$guanxishengri1 = trim($_REQUEST['guanxishengri1']);
    	//关系二
    	$myguanxi2 = trim($_REQUEST['myguanxi2']);
    	$myguanxi2 =preg_replace("/\s/","",$myguanxi2);
    	$guanxishengri2 = trim($_REQUEST['guanxishengri2']);
    	//关系三
    	$myguanxi3 = trim($_REQUEST['myguanxi3']);
    	$myguanxi3 =preg_replace("/\s/","",$myguanxi3);
    	$guanxishengri3 = trim($_REQUEST['guanxishengri3']);
    	
    	//获取当前时间
    	$U_Time = local_date('Y-m-d H:i:s');
    	
    	$cusql = "select erp_id from users where user_id=".$_SESSION['user_id'];
    	$cust_id = $GLOBALS['db']->getOne($cusql);
    	if ( ! empty($cust_id))
    	{
    		//如果有相同的则提示不允许重复
    		$csql = "SELECT CustRelation FROM GCustConsignee WHERE Cust_ID=".$cust_id;
    		$rst = $GLOBALS['db']->getAll($csql);
    		
    		$all_guan_array = array($myguanxi1, $myguanxi2, $myguanxi3);
    		 
    		foreach ($all_guan_array as $vaa)
    		{
    			foreach ($rst as $rstaa)
    			{
    				if ($vaa == $rstaa['CustRelation'])
    				{
    					$mydelre = "delete from GCustConsignee where CustRelation="."'$vaa'";
    					$GLOBALS['db']->query($mydelre);
    				}
    			}
    		}
    		 
    		if ( ! empty($myguanxi1) && ! empty($guanxishengri1))
    		{
    			$gnsql = "INSERT INTO ". $GLOBALS['ecs']->table('GCustConsignee')." (city_id, U_Time, Cust_ID, CustRelation, CIDate)"." values('$sd', '$U_Time', '$cust_id', '$myguanxi1', '$guanxishengri1')";
    			$GLOBALS['db']->query($gnsql);
    		}
    		if ( ! empty($myguanxi2) && ! empty($guanxishengri2))
    		{
    			$gnsql = "INSERT INTO ". $GLOBALS['ecs']->table('GCustConsignee')." (city_id, U_Time, Cust_ID, CustRelation, CIDate)"." values('$sd', '$U_Time', '$cust_id', '$myguanxi2', '$guanxishengri2')";
    			$GLOBALS['db']->query($gnsql);
    		}
    		if ( ! empty($myguanxi3) && ! empty($guanxishengri3))
    		{
    			$gnsql = "INSERT INTO ". $GLOBALS['ecs']->table('GCustConsignee')." (city_id, U_Time, Cust_ID, CustRelation, CIDate)"." values('$sd', '$U_Time', '$cust_id', '$myguanxi3', '$guanxishengri3')";
    			$GLOBALS['db']->query($gnsql);
    		}
    	}		
    }

	//新品送手帕套装
	$cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	$allow_bjs = array('34051','34052','34053','34054');
	foreach ( $goods_list as $vva )
	{
		if ( in_array($vva['goods_id'], $allow_bjs) )
		{
			$sql123 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
				."values('$sd',$PAInfo_ID,$order_id,'34066',1,0)";
			$GLOBALS['db']->query($sql123);
		}
	}
    //新用户送礼附件随机
    /*if ( ! empty($dmobile))
	{	
		$nesql = "select user_id from users where mobile_phone="."'$dmobile'" ."and erp_id > 0";
		$nerst = $GLOBALS['db']->getOne($nesql);
		if ( empty($nerst) )
		{
			if ( $scity_id == 1)
			{
				$xxsong_array = array();
				$xxsong = '';
				if( ! in_area_w('AJ',$goods_list))
				{
					foreach ($goods_list as $xxglval)
					{
						if ($xxglval['goods_id'] == '33657')
						{
							$xxsong_array = array('33839', '33840');
							$xxsong = 0;
						}
						else 
						{
							$xxsong_array = array('33839', '33840');
							$xxsong = array_rand($xxsong_array);
						}
					}
					
					$sql123l1x = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
	                    ."values('$sd',$PAInfo_ID,$order_id,$xxsong_array[$xxsong],1,0)";
	                $GLOBALS['db']->query($sql123l1x);
				}
			}
		}
	}*/

    /**
     * 如果是北京银行购买新的大面包产品赠送一个巧巧菊
     */
    /*if ( ! empty( $_SESSION['user_id'] ) )
    {
        $bjyhsql = "select user_name from users where user_id=".$_SESSION['user_id'];
        $bjyhrst = $GLOBALS['db']->getOne($bjyhsql);
        if ( $bjyhrst == 'bjbank' )
        {
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            $allow_bjs = array('33796','33806','33808','33807');
            $bjcoutmb = 0;
            foreach ( $goods_list as $vva )
            {

                if ( in_array($vva['goods_id'], $allow_bjs) )
                {
                	$bjcoutmb += $vva['goods_number'];
                }
            }
            if ($bjcoutmb > 0)
            {
            	$sql123 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                        ."values('$sd',$PAInfo_ID,$order_id,'33757',$bjcoutmb,0)";
                $GLOBALS['db']->query($sql123);
            }

        }
    }*/

    /**
     * 买冰淇淋赠送附件
     *
     */

    /*$cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    if ($scity_id == 1)
    {
        foreach ($goods_list as $lval)
        {
            if (in_array($lval['goods_id'],array('29607','29571','29586','29622','29658')))
            {
                $sql123l1 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',1,0)";
                $GLOBALS['db']->query($sql123l1);
            }
            else if (in_array($lval['goods_id'], array('29608','29708','29572','29587','29623','29659')))
            {
                $sql123l2 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',2,0)";
                $GLOBALS['db']->query($sql123l2);
            }
            else if (in_array($lval['goods_id'], array('29609','29573','29588','29624','29660')))
            {
                $sql123l3 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',3,0)";
                $GLOBALS['db']->query($sql123l3);
            }
        }
    }else if ($scity_id == 2)
    {
        foreach ($goods_list as $lval)
        {
            if (in_array($lval['goods_id'],array('29658','29571')))
            {
                $sql123l1 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',1,0)";
                $GLOBALS['db']->query($sql123l1);
            }
            else if (in_array($lval['goods_id'], array('29572','29659')))
            {
                $sql123l2 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',2,0)";
                $GLOBALS['db']->query($sql123l2);
            }
            else if (in_array($lval['goods_id'], array('29660','29573')))
            {
                $sql123l3 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33692',3,0)";
                $GLOBALS['db']->query($sql123l3);
            }
        }
    }*/
	
    //2016母亲节活动满179元赠送一个附件
    /*if ($sh_tiem >= '2016-05-06' && $sh_tiem <= '2016-05-08')
    {
        if ( $scity_id == 1 || $scity_id == 2)
        {
        	$cart_goods = get_cart_goods();
    		$goods_list=$cart_goods['goods_list'];
    		$mqjarray = array();
    		$mqjarray = array('33835', '33928', '33929', '33930', '33657', '33658', '33659', '33660');
    		foreach($goods_list as $mqjval)
    		{
    			if ( in_array( $mqjval['goods_id'], $mqjarray ) )
    			{
    				$sql12311 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                ."values('$sd',$PAInfo_ID,$order_id,'33939',1,0)";
            		$GLOBALS['db']->query($sql12311);
    			}
    		}
        }
    }*/
    

    //圣诞节第二阶段活动
    /*$cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    foreach ($goods_list as $sserval)
    {
    	if ($sserval['goods_price'] == 159 || $sserval['goods_price'] == 179)
    	{
    		$ssjernum += $sserval['goods_number'];
    	}
    	if ($sserval['goods_price'] >= 199 && $sserval['goods_id'] != 33825)
    	{
    		$ssjernumyi += $sserval['goods_number'];
    	}
    	if ($sserval['goods_id'] == '33825')
    	{
    		$ssjernumqh = 1;
    	}
    }
    
    if ($ssjernum > 0)
    {
    	$sql123111 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'32991',$ssjernum,0)";
        $GLOBALS['db']->query($sql123111);
    }
    if ($ssjernumyi > 0)
    {
    	if ($scity_id == 1 )
		{
			$sql123112 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                ."values('$sd',$PAInfo_ID,$order_id,'33807',$ssjernumyi,0)";
    		$GLOBALS['db']->query($sql123112);
		}
		else 
		{
			$sql1231122 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                ."values('$sd',$PAInfo_ID,$order_id,'33796',$ssjernumyi,0)";
    		$GLOBALS['db']->query($sql1231122);
		}
    }
    if ($ssjernumqh == 1)
    {
    	$sql123113 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                    ."values('$sd',$PAInfo_ID,$order_id,'33828',$ssjernumqh,0)";
        $GLOBALS['db']->query($sql123113);
        if ($scity_id == 1 )
		{
			$sql1231131 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                ."values('$sd',$PAInfo_ID,$order_id,'33807',$ssjernumqh,0)";
    		$GLOBALS['db']->query($sql1231131);
		}
		else 
		{
			$sql1231132 = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
                ."values('$sd',$PAInfo_ID,$order_id,'33796',$ssjernumqh,0)";
    		$GLOBALS['db']->query($sql1231132);
		}
    }*/
    //圣诞节第二阶段活动结束

    //赠品
    $sql="select c.*,g.* from cart c,goods g where c.session_id='".SESS_ID."' and c.is_gift=1 and c.goods_id=g.goods_id";
	$zp = $GLOBALS['db']->getRow($sql);
	if(count($zp)>0)
	{
		$goods_id = $zp['goods_id'];
		$number = 1;
		$total =0;//金额
		//$sql = "insert into ".$GLOBALS['ecs']->table('AppendInfo')." (City_ID,PA_No,Indent_No,Append_No,Number,Cash) "
		//				."values('$sd',$PAInfo_ID,$order_id,$goods_id,$number,$total)";
		//$GLOBALS['db']->query($sql);
	}

    /* 取得购物类型 */
    $flow_type = isset($_SESSION['flow_type']) ? intval($_SESSION['flow_type']) : CART_GENERAL_GOODS;
    clear_cart($flow_type);
    //储值卡全额结算
    if($czkje==$total_fee)
    {
    	$sql = "update CCInfo set Pay_State = 1 , Total_Fee='$total_fee' where order_id='$order_id'";
		$GLOBALS ['db']->query ( $sql );
		$sql = "update PAInfo set Agio_Type ='' where order_id='$order_id'";
	
		$GLOBALS ['db']->query ( $sql );
		$sql = "update users set last_time='$U_Time',LastBay_Time='$U_Time' where user_id='$user_id'";
		$GLOBALS ['db']->query ( $sql );
		$goods_list_shichang =  get_order_goods($order_id);
		
		foreach ( $goods_list_shichang ['goods_list'] as $goods ) {
			$sql = "update PAInfo set Receivable =(" . $goods ['shop_price'] . " * " . $goods ['Number'] . ") where pa_id=" . $goods ['PA_ID'];
			$GLOBALS ['db']->query ( $sql );
		}
		/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
			if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
		
		/*聚合统计订单回传输出*/
		$smarty->assign('jh_total', $total_fee);	//订单总金额
		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
		
		
		$smarty->assign ( 'orderid', $order_id );
		$smarty->display ( 'flow_confirmother.dwt' );
		exit ();
    }
    else if($czkje>0 && $czkje<$total_fee)
    {
    	$sql = "update CCInfo set Total_Fee='$total_fee' where order_id='$order_id'";
		$GLOBALS ['db']->query ( $sql );
				
		$sql = "update PAInfo set Agio_Type ='' where order_id='$order_id'";
		$GLOBALS ['db']->query ( $sql );
		$goods_list_shichang =  get_order_goods($order_id);
		foreach ( $goods_list_shichang ['goods_list'] as $goods ) 
		{
			$sql = "update PAInfo set Receivable =(" . $goods ['shop_price'] . " * " . $goods ['Number'] . ") where pa_id=" . $goods ['PA_ID'];
			$GLOBALS ['db']->query ( $sql );
		}
    }



    /*------------------------------------------------------------------------------------*/
    clear_cart($flow_type);
    
    /*-----------------------------------------------------*/
    //-- 判断是否由51比购带来的订单--------
    /*-----------------------------------------------------*/
    if (isset($_COOKIE['bg_cps_user']))
    {
    	$cookie = $_COOKIE['bg_cps_user'];
    	add_bgcpsinfo($order_id,$cookie);
    	$rst = creatbg_cpsurl($order_id,$cookie);
    	if ( ! empty($cookie))
    	{
    		file_get_contents($rst);
    	}
    	
    }
    
    /*-----------------------------------------------------*/
    //-- 判断是否由唯一带来的订单--------
    /*-----------------------------------------------------*/
    if (isset($_COOKIE['weiyi_cps_user']))
    {
    	$cookie = $_COOKIE['weiyi_cps_user'];
    	add_weiyicpsinfo($order_id,$cookie);
    	$rst = creatweiyiCpsURL($order_id,$cookie);
    	if ( ! empty($cookie))
    	{
    		$outstr="<script src=\"".$rst."\"></script>";
    		echo $outstr;
    		/*$a = file_get_contents($rst);
    		var_dump($a);exit;*/
    	}
    	 
    }
    
	/*-------------------------------------------------------*/
	//-- 判断是否由亿码-亿起发平台带来的订单
	/*-------------------------------------------------------*/
	
	if(isset($_COOKIE['cps_user']))
	{
		$cookie = $_COOKIE['cps_user'];
		add_cpsinfo($order_id,$cookie);
		$url = creatCpsURL($order_id,$cookie);
		$smarty->assign ('cps_url', $url); // CPS_URL
	}
	/*if($_SESSION['qqlogin']=='1')
	{
		$qq_url = creatQQURL($order_id,$cookie);
		$smarty->assign ('qq_url', $qq_url); // CPS_URL
	}*/
	
	/*-------------------------------------------------------*/
	//-- 判断是否由易达平台带来的订单
	/*-------------------------------------------------------*/
	if (isset($_COOKIE['cps_yida_user']))
	{
		$cookies = $_COOKIE['cps_yida_user'];
		add_cpsinfo($order_id,$cookies);
		$url = creatCpsURLyida($order_id,$cookies);
		
		//$smarty->assign ('cps_url', $url); // CPS_URL
		if ( ! empty($cookies))
		{
			file_get_contents($url);
		}
	}
	
	/*-------------------------------------------------------*/
	//-- 判断是否由多麦cps-平台带来的订单
	/*-------------------------------------------------------*/
	if(!empty($_COOKIE['dm_cps_user']))
	{
		
		$cookie = $_COOKIE['dm_cps_user'];
		
		add_duomai_cpsinfo($order_id,$cookie);
		
	}
	
	/*-------------------------------------------------------*/
	//-- 判断是否由多麦cpsROI-平台带来的订单
	/*-------------------------------------------------------*/
	if(!empty($_COOKIE['dmroi_cps_user']))
	{
		$cookie = $_COOKIE['dmroi_cps_user'];
		add_duomairoi_cpsinfo($order_id,$cookie);
		$dm_cookie = $_COOKIE['dmroi_cps_user'];//多麦cookie
		$url = dmroicreatCpsURL($order_id,$dm_cookie);
		if(!empty($dm_cookie)) {
			file_get_contents($url);
		}
	}
	
	/*-------------------------------------------------------*/
	//-- 判断是否由海博cps-平台带来的订单
	/*-------------------------------------------------------*/
	if(!empty($_COOKIE['hb_cps_user']))
	{
		
		$cookie = $_COOKIE['hb_cps_user'];
		//微博登录的用户uid
		$uid = $_SESSION['token']['uid'];
		//微博登录的nickname
		$c = new SaeTClientV2( WB_AKEY , WB_SKEY , $_SESSION['token']['access_token'] );
		$nickname = $c->show_user_by_id( $uid);//根据ID获取用户等基本信息
		$wb_nickname = $nickname['screen_name'];
		add_hb_cpsinfo($order_id, $cookie, $uid, $wb_nickname);
		
	}
	
	/*-------------------------------------------------------*/
	//-- 判断是由哪个域名来的订单
	/*-------------------------------------------------------*/
	if(isset($_COOKIE['whichUrl']))
	{
		$cookie = $_COOKIE['whichUrl'];
		$sql = "update order_info set which_url='".$cookie.":".$_SESSION["urlname"].":".$_SESSION["s_s_keyword"]."' where order_id=".$order_id;
		$GLOBALS['db']->query($sql);
	}
    
    /*------------------------------------------------------------------------------------*/
    $smarty->assign ( 'BillNo', $order_id );
    
	if($fangshi=='10')
    {
    	//发送优惠券
    	if($_SESSION['user_id'] != '0')
    	{
    		//指定款商品和订单满额返券不同时进行
    		if(!isSendBonus($goods_list))
    		{
    			$is_send_falg = sendbonus("ORDER","22",$_SESSION['user_id'],$need_total,$order_id,'');
    		}
    	}
    	/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
    		if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
    	$smarty->assign ( 'fangshiname', $fangshiname );
		$smarty->assign ( 'orderid', $order_id );
		$smarty->display ( 'flow_confirmother.dwt' );
		exit ();
    }
    if($fangshi=='1')
    {
    	//发送优惠券
    	if($_SESSION['user_id'] != '0')
    	{
    		//指定款商品和订单满额返券不同时进行
    		if(!isSendBonus($goods_list))
    		{
    			$is_send_falg = sendbonus("ORDER","22",$_SESSION['user_id'],$need_total,$order_id,'');
    		}
    	}
    	/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
   			if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
		$smarty->assign ( 'fangshiname', $fangshiname );
		$smarty->assign ( 'orderid', $order_id );
		$goods_list_shichang =  get_order_goods($order_id);
		/*聚合统计订单回传输出*/
		$smarty->assign('jh_total', $total_fee);	//订单总金额
		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
		
		$smarty->display ( 'flow_confirmother.dwt' );
		exit ();
    }
    if($fangshi=='3')
    {
    	if($zhifu=='6')
    	{
    		include_once ('includes/modules/payment/chinabank.php');
			include_once ('includes/lib_order.php');

			$sql = "select * from order_info where order_id='$order_id'";
			$order = $GLOBALS ['db']->getAll ( $sql );
			$cash = $order [0] ['cash'];
			$Amount = price_format( $cash,true );
			
			$pay_obj = new chinabank ();
			$sql = "select * from payment where pay_id=4";
			$payment = $GLOBALS ['db']->getAll ( $sql );
			$pay_online = $pay_obj->get_code ( $order [0], unserialize_config ( $payment [0] ['pay_config'] ) );
			
    		/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
			
    		if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
			$smarty->assign ( 'Amount', $Amount );
    		$smarty->assign ( 'BillNo', $order_id );
    		
    		/*聚合统计订单回传输出*/
    		$smarty->assign ( 'orderid', $order_id );
    		$goods_list_shichang =  get_order_goods($order_id);
    		$smarty->assign('jh_total', $total_fee);	//订单总金额
    		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
    		
			$smarty->assign ( 'pay_online', $pay_online );
			$smarty->display ( 'flow_confirmwyzx.dwt' );
			exit ();
    	}
    	elseif($zhifu=='5')
    	{
    		include_once ('includes/modules/payment/alipay.php');
			include_once ('includes/lib_order.php');
			
			$sql = "select * from order_info where order_id='$order_id'";
			$order = $GLOBALS ['db']->getAll ( $sql );
			$cash = $order [0] ['cash'];
			$Amount = price_format( $cash,true );
			
			$pay_obj = new alipay ();
			$sql = "select * from payment where pay_id=1";
			$payment = $GLOBALS ['db']->getAll ( $sql );
			$pay_online = $pay_obj->get_code ( $order, unserialize_config ( $payment [0] ['pay_config'] ) );
			
    		/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
    		if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
			$smarty->assign ( 'Amount', $Amount );
    		$smarty->assign ( 'BillNo', $order_id );
    		
    		/*聚合统计订单回传输出*/
    		$smarty->assign ( 'orderid', $order_id );
    		$goods_list_shichang =  get_order_goods($order_id);
    		$smarty->assign('jh_total', $total_fee);	//订单总金额
    		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
    		
			$smarty->assign ( 'pay_online', $pay_online );
			
			$smarty->display ( 'flow_confirmzfb.dwt' );
			exit ();
    	}
    	elseif($zhifu=='9')
    	{
    			
    		$sql = "select * from order_info where order_id='$order_id'";
    		$order = $GLOBALS ['db']->getAll ( $sql );
    		$cash = $order [0] ['cash'];
    		$Amount = price_format( $cash,true );
    		
    		//支付参数
    		$xfzfurl = 'https://cp.ucfpay.com:58101/fapay/paygate.action';
    		$mchid = '000000000000014';
    		$vsn = '1.0';
    		$md5str = $mchid.$order_id.$vsn.'WWHRTVBNWWGFGWW'.$Amount.$mchid;
    		
    		/*多麦推送*/
    		$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
    		$url = dmcreatCpsURL($order_id,$dm_cookie);
    		if(!empty($dm_cookie)) {
    			file_get_contents($url);
    		}
    		$smarty->assign ( 'Amount', $Amount );
    		$smarty->assign ( 'BillNo', $order_id );
    	
    		/*聚合统计订单回传输出*/
    		$smarty->assign ( 'orderid', $order_id );
    		$goods_list_shichang =  get_order_goods($order_id);
            //在先锋支付的付款页面需要体现优惠了多少金额
            $xfzfnnn = $total_fee * 0.1;
            $smarty->assign('xfzfnnn', $xfzfnnn);
    		$smarty->assign('jh_total', $total_fee);	//订单总金额
    		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
    		$smarty->assign ( 'url', $xfzfurl );
    		$smarty->assign ( 'mchid', $mchid );
    		$smarty->assign ( 'vsn', $vsn );
    		$smarty->assign ( 'md5str', md5($md5str) );
    		$smarty->display ( 'flow_confirmxfzf.dwt' );
    		exit ();
    	}
        elseif($zhifu=='4')
        {
            include_once ('includes/modules/payment/yyt.php');
            //银盈通
            $sql = "select * from order_info where order_id='$order_id'";
            $order = $GLOBALS ['db']->getAll ( $sql );
            $cash = $order [0] ['cash'];
            $Amount = price_format( $cash,true );
            $spsql = "select g.goods_name from PAInfo p,goods g where p.goods_id=g.goods_id and p.order_id='$order_id'";
            $sqrst = $GLOBALS['db']->getOne($spsql);
            $sqrst = $sqrst.'等';
            $sqrst = bin2hex($sqrst);
            //银盈通支付参数
            $yyturl = "http://www.ebcpay.com/payment/getwaychange.html";
            $key = "4dc00d3a";
            $merchno = '611100000309857';
            $dsorderid = $order_id;
            $amount = $Amount;
            $dsyburl = 'http://www.wfboy.com/notify_url_yyt.php';
            $dstburl = 'http://www.wfboy.com/return_url_yyt.php';
            $params = "";
            $params .= "merchno=".$merchno;
            $params .= "&dsorderid=".$dsorderid;
            $params .= "&amount=".$amount;
            $params .= "&dsyburl=".$dsyburl;
            $params .= "&dstburl=".$dstburl;
            $params .= "&product=".$sqrst;

            $des = new DES_JAVA($key);
            $sign = $des->encrypt($params);
            $url = $yyturl."?".$params."&dstbdatasign=".$sign;

            $smarty->assign ( 'getway', $yyturl );
            $smarty->assign ( 'merchno', $merchno );
            $smarty->assign ( 'dsorderid', $dsorderid );
            $smarty->assign ( 'amount', $amount );
            $smarty->assign ( 'dsyburl', $dsyburl );
            $smarty->assign ( 'dstburl', $dstburl );
            $smarty->assign ( 'dstburl', $dstburl );
            $smarty->assign ( 'product', $sqrst );
            $smarty->assign ( 'sign', $sign );
            $smarty->assign ( 'myurl', $url );

            $smarty->assign ( 'Amount', $Amount );
            $smarty->assign ( 'BillNo', $order_id );

            /*聚合统计订单回传输出*/
            $smarty->assign ( 'orderid', $order_id );
            $goods_list_shichang =  get_order_goods($order_id);
            //在先锋支付的付款页面需要体现优惠了多少金额

            $smarty->assign('jh_total', $total_fee);	//订单总金额
            $smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
            $smarty->assign ( 'url', $xfzfurl );

            $smarty->display ( 'flow_confirmyyt.dwt' );
            exit ();
        }
    	elseif($zhifu=='8')
    	{
			require_once(ROOT_PATH . 'includes/modules/payment/pufa_bank.php');
			
			$sql = "select * from order_info where order_id='$order_id'";
			$order = $GLOBALS ['db']->getAll ( $sql );
			$cash = $order [0] ['cash'];
			$Amount = price_format( $cash,true );
			
			$MercDtTm = date("YmdHis",strtotime($order [0]['Accept_Time']));
			
			$plain="TranAmt=".price_format($order[0]['cash'],true)."|MercCode=912354410000101|TranAbbr=IPER|".
				   "TermSsn=".$order_id."|OAcqSsn=|MercDtTm=".$MercDtTm."|TermCode=001|Remark1=|Remark2=".
                   "|OSttDate=";
			
			$pfbank = new PufaBank();
 			$html = $pfbank->creathtml('IPER', $plain);
 			/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
    		if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
 			$smarty->assign ( 'Amount', $Amount );
    			$smarty->assign ( 'BillNo', $order_id );
			$smarty->assign ( 'pay_online', $html );
			$smarty->display ( 'flow_confirmpufa.dwt' );
    	}
    	elseif($zhifu=='7')
    	{
    		include_once ('includes/classes/RequestHandler.class.php');
			include_once ('includes/lib_order.php');
    		
    		$sql = "select * from order_info where order_id='$order_id'";
			$order = $GLOBALS ['db']->getAll ( $sql );
			$cash = $order [0] ['cash'];
			$Amount = price_format( $cash,true );

			$sql = "select * from payment where pay_id=5";
			$payment = $GLOBALS ['db']->getAll ( $sql );
			$payconfig = unserialize_config ( $payment [0] ['pay_config'] );
			/* 商户号 */
			$partner = $payconfig['tenpay_account'];
			/* 密钥 */
			$key = $payconfig['tenpay_key'];
			//4位随机数
			$randNum = rand(1000, 9999);
			//订单号，此处用时间加随机数生成，商户根据自己情况调整，只要保持全局唯一就行
			$out_trade_no = $order_id;
			
			/* 创建支付请求对象 */
			$reqHandler = new RequestHandler();
			$reqHandler->init();
			$reqHandler->setKey($key);
			$reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");
			
			//----------------------------------------
			//设置支付参数 
			//----------------------------------------
			$reqHandler->setParameter("total_fee", $Amount*100);  //总金额单位为分
			//用户ip
			$reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
			$reqHandler->setParameter("return_url", "http://www.waffleboy.com.cn/payReturnUrl.php");//支付成功后返回
			$reqHandler->setParameter("partner", $partner);
			$reqHandler->setParameter("out_trade_no", $out_trade_no);
			$reqHandler->setParameter("notify_url", "http://www.waffleboy.com.cn/payNotifyUrl.php");
			$reqHandler->setParameter("body", "窝夫订单号为：".$order_id);
			$reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
			$reqHandler->setParameter("fee_type", "1");               //币种
			//系统可选参数
			$reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
			$reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
			$reqHandler->setParameter("input_charset", "UTF-8");   	  //字符集
			$reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号
			
			//业务可选参数
			$reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
			$reqHandler->setParameter("product_fee", "");        	  //商品费用
			$reqHandler->setParameter("transport_fee", "");      	  //物流费用
			$reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
			$reqHandler->setParameter("time_expire", "");             //订单失效时间
			
			$reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
			$reqHandler->setParameter("goods_tag", "");               //商品标记

			//请求的URL
			$reqUrl = $reqHandler->getRequestURL();
			$params = $reqHandler->getAllParameters();
			$pay_online = "";
			foreach($params as $k => $v) {
				$pay_online = $pay_online."<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
			}
			$pay_online = $pay_online."<input type=\"submit\" value=\"\" id=\"cftBtn\">\n";
			/*多麦推送*/
			$dm_cookie = $_COOKIE['dm_cps_user'];//多麦cookie
			$url = dmcreatCpsURL($order_id,$dm_cookie);
    		if(!empty($dm_cookie)) {
				file_get_contents($url);
			}
    		$smarty->assign ( 'BillNo', $order_id );
			$smarty->assign ('gateUrl', $reqHandler->getGateUrl());
			$smarty->assign ( 'pay_online', $pay_online);
			$smarty->assign ( 'Amount', $Amount );
			/*聚合统计订单回传输出*/
    		$smarty->assign ( 'orderid', $order_id );
    		$goods_list_shichang =  get_order_goods($order_id);
    		$smarty->assign('jh_total', $total_fee);	//订单总金额
    		$smarty->assign('jh_goodsinfo', $goods_list_shichang['goods_list']);	//商品信息
			
			$smarty->display ( 'flow_confirmcft.dwt' );
			exit ();
    	}
    }
    clear_cart($flow_type);
	/*-------------------------------------------------------*/
	//-- 判断是否由亿码-亿起发平台带来的订单
	/*-------------------------------------------------------*/
			
	/*if(isset($_COOKIE['cps_user']))
	{
		$cookie = $_COOKIE['cps_user'];
		add_cpsinfo($order_id,$cookie);
		$url = creatCpsURL($order_id,$cookie);
		$smarty->assign ('cps_url', $url); // CPS_URL
	}*/
	/*-------------------------------------------------------*/
	//-- 判断是由哪个域名来的订单
	/*-------------------------------------------------------*/
	if(isset($_COOKIE['whichUrl']))
	{
		$cookie = $_COOKIE['whichUrl'];
		$sql = "update order_info set which_url='".$cookie.":".$_SESSION['urlname'].":".$_SESSION['s_s_keyword']."' where order_id=".$order_id;
		$GLOBALS['db']->query($sql);
	}
	//ecs_header("Location: flow_payment.php?step=view_order&order_id=$mt_order_id\n");
}else
{
    //查询发票自动匹配的信息
	$user_id = $_SESSION['user_id'];
	$sql = "select top 1 o.inv_payee from order_info o where o.user_id={$user_id} order by order_id desc";
	$rst = $GLOBALS['db']->getRow($sql);
	$smarty->assign('fp',$rst['inv_payee']);
    /* 标记购物流程为普通商品 */
    $_SESSION['flow_type'] = CART_GENERAL_GOODS;
    
    /* 取得商品列表，计算合计 */
   
	$cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	


	//判断是否送牛轧糖
	//$song_niu = array('32637', '32638', '32639', '32640');

	/*foreach ($goods_list as $vv)
	{
		if (in_array($vv['goods_id'], $song_niu))
		{
			$smarty->assign('pd_sniu1', '1233');
		}
		if ($vv['goods_id'] == '33351')
		{
		//购买两个牛轧糖送2盒牛奶
			if ($vv['goods_number'] >= 2)
			{
				$smarty->assign('pd_sniu2', '12331');
			}
		}
	}*/
	
	/*全国送的产品同时购买一件运费8元两件4元3件或以上0元*/
	foreach ($goods_list as $kk=>$v)
	{
		$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
		$rst[] = $GLOBALS['db']->getAll($dajinsql);
		$rst = array_slice($rst,1);
		foreach ($rst as $vv)
		{
			foreach ($vv as $vvv)
			{
				if ($vvv['cat_id'] == 'AJ')
				{
					$ajnum[$kk] = $v['goods_number'];
                    $ajprice[$kk] = $v['shop_price'] * $v['goods_number'];
				}
			}
		}
			
	}
    if (is_array($ajprice))
    {
        $ajpriceyun = array_sum($ajprice);
    }
    if ($ajpriceyun >= '179')
    {
        $smarty->assign('pd_yunfei', '12321');
        $peisongfei = 0;
        $smarty->assign('myyunfei', $peisongfei);
    }
    else
    {
        if (is_array($ajnum))
        {
            $yunfeinum = array_sum($ajnum);
        }
        if ( ! empty($yunfeinum))
        {
            if ($yunfeinum < 2)
            {
                $smarty->assign('pd_yunfei', '12321');
                $peisongfei = 8;
                $smarty->assign('myyunfei', $peisongfei);
            }
            else if($yunfeinum == 2)
            {
                $smarty->assign('pd_yunfei', '12321');
                $peisongfei = 4;
                $smarty->assign('myyunfei', $peisongfei);
            }
            else if($yunfeinum > 2)
            {
                $smarty->assign('pd_yunfei', '12321');
                $peisongfei = 0;
                $smarty->assign('myyunfei', $peisongfei);
            }
            else
            {
                $smarty->assign('pd_yunfei', '12321');
                $peisongfei = 0;
                $smarty->assign('myyunfei', $peisongfei);
            }
        }
    }
	foreach ($goods_list as $kk=>$v)
	{
		$dajinsql = "select cat_id from goods_cat where goods_id = ($v[goods_id])";
		$rst[] = $GLOBALS['db']->getAll($dajinsql);
			
		foreach ($rst as $vv)
		{
			foreach ($vv as $vvv)
			{
				if ($vvv['cat_id'] == 'AJ')
				{
					$abcdd = '1';
				}
				else if ($vvv['cat_id'] == 'ZD')
				{
					$xxxajaj = '1';
				}
			}
		}
			
	}
	if ( ! empty($abcdd) && ! empty($xxxajaj))
	{
		$smarty->assign('pd_yunfei', '12321');
		$peisongfei = 0;
		$smarty->assign('myyunfei', $peisongfei);
	}

    //一盒甜品的运费展示
    $cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    static $yhtznum = 0;
    static $dmbwj = 0;
    foreach ($goods_list as $yav)
    {
        if ($yav['goods_id'] == '33755' || $yav['goods_id'] == '33754' || $yav['goods_id'] == '33753')
        {
            $yhtznum += $yav['goods_number'];
        }
        if ($yav['goods_id'] == '33796' || $yav['goods_id'] == '33806' || $yav['goods_id'] == '33807' || $yav['goods_id'] == '33808')
        {
            $dmbwj += $yav['goods_number'];
        }
    }

    if ($yhtznum >= 1)
    {
        $smarty->assign('myyunfei', '一盒甜品北京包邮，其他地区一盒运费25元，2盒及以上0');
    }

    if ($dmbwj >= 1)
    {
        $smarty->assign('myyunfei', '窝家大面包运费');
    }

	$real_append_list=array();
	$append_money='';
	$have_cx = 0; //是否有促销商品
	
	foreach($goods_list as $item)
	{
		//查询对应商品的总数量
		$sql="select c.parent_id,c.goods_id,c.goods_name,c.goods_price,c.goods_number,g.Unit,g.goods_img from cart c,goods g where c.parent_id=".$item['goods_id']." and c.goods_id=g.goods_id and session_id='".SESS_ID."'";
		$total_append_list=$GLOBALS['db']->getAll($sql);
		foreach($total_append_list as $v)
		{
			if(count($v)>0)
			{
				//查询对应商品的对应附件的默认数量
				$sql="select App_Num from DefaultApp where goods_id=".$item['goods_id']." and DefaultAppNo=".$v['goods_id'];
				$default_app_num=$GLOBALS['db']->getOne($sql);
				$v['goods_number']=$v['goods_number']-$default_app_num*$item['goods_number'];
				$v['one_total']=floor($v['goods_price']*$v['goods_number']);
				$append_money+=$v['one_total'];
				$v['format_total']=price_format($v['one_total'],true);
				$v['goods_price']=price_format($v['goods_price'],false);
			}
			$real_append_list[]=$v;
		}
		if($item['is_gift']>0)
		{
			$have_cx = 1;
		}

	}
	$_SESSION['append_total_price']=$append_money;
	//商品和附件的总金额
	$all_total=$cart_goods['total']['goods_amount']+$append_money;
	
	//如果是像团委的客户打折，则重新显示总价格
	if ( ! empty($_SESSION['user_id']))
	{
		$zhe3sql = "SELECT is_youhui,zhe_val FROM users WHERE user_id=".$_SESSION['user_id'];
		$rst3zhe = $GLOBALS['db']->getRow($zhe3sql);
		
		if ($rst3zhe['is_youhui'] == 1)
		{
			$all_total *= $rst3zhe['zhe_val'];
            $all_total = floor($all_total);
		}
	}

	/**
	 * 中秋活动蛋糕跟月饼同时购买立减100元
	 */
	$cart_goods = get_cart_goods();
	$goods_list=$cart_goods['goods_list'];
	$yuebing = array('34067', '34070', '33771', '33772');
	static $aa = 0;
	foreach($goods_list as $yueval)
	{
		if (in_array($yueval['goods_id'], $yuebing))
		{
			$yuecount = 1;
		}

		if (cate_in_list('ZD', $goods_list))
		{
			$zdyuecont = 1;
		}
		$aa += $yueval['goods_price'] * $yueval['goods_number'];
	}
	if ($aa >= 378)
	{
		$aayuecont = 1;
	}
	$zyuecont = $yuecount + $zdyuecont + $aayuecont;
	if ($zyuecont == 3)
	{
		$all_total = $all_total - 100;
	}

	/**
	 * 如果是北京银行用户购买的指定款商品则打7折
	 */
	/*if ( ! empty( $_SESSION['user_id'] ) )
	{
		$bjyhsql = "select user_name from users where user_id=".$_SESSION['user_id'];
		$bjyhrst = $GLOBALS['db']->getOne($bjyhsql);
		if ( $bjyhrst == 'bjbank' )
		{
			//购买指定款商品
            $allow_bj = array('33825','33688','33689','33690','33736', '33737', '33738', '33739', '33657', '33658', '33659', '33660');
			$cart_goods = get_cart_goods();
			$goods_list=$cart_goods['goods_list'];
			$bjbankcarcount = 0;
				
			foreach($goods_list as $va)
			{
				if ( in_array($va['goods_id'], $allow_bj))
				{
					$bjbankcarcount += $va['goods_number'];
				}
			}
				
			//获取购物车商品数量
			$sql = "SELECT goods_number FROM " . $ecs->table('cart') .
			" WHERE session_id = '" . SESS_ID . "' " .
			"AND rec_type = '$flow_type'";
				
			$bjbcartnum = $GLOBALS['db']->getAll($sql);
			foreach ($bjbcartnum as $xxxnum)
			{
				$bjbcartnumshow += $xxxnum['goods_number'];
			}
				
			if ($bjbcartnumshow == $bjbankcarcount)
			{
				$all_total = floor($all_total * 0.7);
			}

            $smarty->assign('is_beijingbankshow', 'isbankshow');
		}
	}*/

    /**
     * 使用北京银行货到付款本可以打8折如购买了储值卡则除储值卡才打折
     */
    /*if (  empty( $_SESSION['user_id'] ) )
    {
        $cart_goods = get_cart_goods();
        $goods_list=$cart_goods['goods_list'];
        static $bjnozhe = 0;
        foreach ( $goods_list as $bjcart_val )
        {
            if ( $bjcart_val['goods_id'] == '32343' || $bjcart_val['goods_id'] == '32344' || $bjcart_val['goods_id'] == '32346' )
            {
                $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * 0;
            }
            else
            {
                $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * -0.2;
            }
        }
        $all_total = floor($all_total + $bjnozhe);

    }
    else
    {
        $bjyhsql = "select user_name from users where user_id=".$_SESSION['user_id'];
        $bjyhrst = $GLOBALS['db']->getOne($bjyhsql);
        if ( $bjyhrst == 'bjbank' )
        {
            $need_total = $total_fee;
            $order_total_fee = $total_fee;
        }
        else
        {
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            static $bjnozhe = 0;
            foreach ( $goods_list as $bjcart_val )
            {
                if ( $bjcart_val['goods_id'] == '32343' || $bjcart_val['goods_id'] == '32344' || $bjcart_val['goods_id'] == '32346' )
                {
                    $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * 0;
                }
                else
                {
                    $bjnozhe += $bjcart_val['goods_price'] * $bjcart_val['goods_number'] * -0.2;
                }
            }
            $all_total = floor($all_total + $bjnozhe);
        }
    }*/

    /**
     * 国航用户guohangwfboy购买4款特定打8折，其他打8.5折
     */
    if ( ! empty($_SESSION['user_id']))
    {
        $ghsql = "select user_name from users where user_id=".$_SESSION['user_id'];
        $ghrst = $GLOBALS['db']->getOne($ghsql);
        if ($ghrst == 'guohangwfboy')
        {
            static $ghzhe_val = 0;
            $cart_goods = get_cart_goods();
            $goods_list=$cart_goods['goods_list'];
            $allow_gh = array('33351','33353','29442','7933','7934', '7935', '29375', '7375', '7376', '7377');
            foreach ($goods_list as $gh_val)
            {
                if (in_array($gh_val['goods_id'], $allow_gh))
                {
                    $ghzhe_val += $gh_val['goods_price'] * $gh_val['goods_number'] * -0.2;
                }
                else
                {
                    $ghzhe_val += $gh_val['goods_price'] * $gh_val['goods_number'] * -0.15;
                }
            }
            $all_total = floor($all_total + $ghzhe_val);
            $smarty->assign('is_beijingbankshow', 'isbankshow');
        }
    }

    /**
     * 牛轧糖活动买一盒原价，2盒第2盒半价，3盒，第2盒半价，第3盒，半价的半价，超过3盒的部分不享受优惠
     */
    $cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    $allow_bj_niu = array('33351','33698','33699','33700','33701');
    $niucount = 0;
    static $niujianjia = 0;
    foreach($goods_list as $va)
    {
        if ( in_array($va['goods_id'], $allow_bj_niu))
        {
            $niucount += $va['goods_number'];
        }
    }
    if ($niucount == 2)
    {
        $niujianjia += -30;
    }
    elseif ($niucount == 3)
    {
        $niujianjia += -74;
    }
    elseif ($niucount > 3)
    {
        $niujianjia += -74;
    }
    $all_total = floor($all_total + $niujianjia);
    
    /*小熊组合装减3元*/
    /*$cart_goods = get_cart_goods();
    $goods_list=$cart_goods['goods_list'];
    static $xshum2 = 0;
    foreach ($goods_list as $x_val)
    {
    	if ($x_val['goods_id'] == '33819')
    	{
    		$xshum += 1;
    	}
    	if ($x_val['goods_id'] == '33796')
    	{
    		$xshum +=2;
    	}
    	if ($x_val['goods_id'] == '33806')
    	{
    		$xshum +=4;
    	}
    	if ($x_val['goods_id'] == '33807')
    	{
    		$xshum +=6;
    	}
    	if ($x_val['goods_id'] == '33809')
    	{
    		$xshum +=8;
    	}
    }
    if ($xshum == 3)
    {
    	$all_total = floor($all_total - 3);
    }
    if ($xshum == 5)
    {
    	$all_total = floor($all_total - 8);
    }
    if ($xshum == 7)
    {
    	$all_total = floor($all_total - 5);
    }
    if ($xshum == 9)
    {
    	$all_total = floor($all_total - 2);
    }*/
    /**
     * 61活动
     */
    /*if ($sh_tiem >= '2015-05-30' && $sh_tiem <= '2015-06-01')
    {
        $cart_goods = get_cart_goods();
        $goods_list=$cart_goods['goods_list'];
        static $ljian = 0;
        foreach ($goods_list as $lval)
        {
            if (in_array($lval['goods_id'],array('33688','33689','33690','33668')))
            {
                $ljian += 30;
            }
            else if (in_array($lval['goods_id'], array('30648','7680','7681','30656','30657','30658','30659','29708','33216','33217','33218','33219','33220','33221')))
            {
                $ljian += 20;
            }
        }
        $all_total = floor($all_total - $ljian);
    }*/



	/*如果该用户之前忆经提供过关系生日则要在页面显示*/
	if ( ! empty($_SESSION['user_id']))
	{
		$smarty->assign('is_showsr', 'isshowsr');
	}
	
	$_SESSION['zongjine']=floor($all_total);
	$format_all_total=price_format($all_total,true);

	$append_money=($append_money=='')?0:$append_money;

	$_SESSION['fujianjiaqian']=floor($append_money);

	$smarty->assign('goods_list', $goods_list);
    $smarty->assign('format_all_total', $format_all_total);
	$smarty->assign('append_list', $real_append_list);
	$smarty->assign('append_money', $append_money);
    $smarty->assign('present_list', $cart_goods['present_list']);
    $smarty->assign('present1_list', $cart_goods['present1_list']);


    /*判断购物车的配送时间*/
    $cartPsTime = CakePsTime($goods_list);
    if(isset($cartPsTime))
    {
    	$smarty->assign('cartPsTime', $cartPsTime);
    }
    else
    {
    	$smarty->assign('cartPsTime', '8');
    }

    /* 取得客户收货人地址列表*/
    $AddressList = address_list($_SESSION['user_id']);
	foreach($AddressList as $key=>$value)
	{
		$AddressList[$key]['city']=get_region($value['city']);
		$AddressList[$key]['district']=get_region($value['district']);

	}
    $smarty->assign('AddressList', $AddressList);
	$smarty->assign("",count($AddressList));
	
	//判断是否可配送到外地的商品
	if(in_area_w('AJ',$goods_list)){
			$TopAddList = get_regions(0);
			$smarty->assign('qgsdz', '1234321');//这里是全国送的商品如果选择北京上海后还得用以前的运费收取方式进行判断
	}
	else 
	{
		if(cate_in_list('AM',$goods_list))
		{
			$TopAddList = get_regions_bj(0);
			$smarty->assign('gwcar_bg', 2);
			//判断不是全国送的才可以货到付款
			$smarty->assign('pdhdfk', 12212);
		}
		else
		{
    		$TopAddList = get_regions_all(0);
    		$smarty->assign('gwcar_bg', 2);
    		//判断不是全国送的才可以货到付款
    		$smarty->assign('pdhdfk', 12212);
		}
	}
    $smarty->assign('TopAddList', $TopAddList);
    //判断是否可以使用优惠券W币等
	if(in_use_wb('AK',$goods_list)){
		$can_use_wb = 0;
	}
	elseif($have_cx==1)
	{
		$can_use_wb = 0;
	}
	else 
	{
    	$can_use_wb = 1;
	}
	
	$is_no_rate =0;
	if(count($have1)>0||count($have2)>0)
	{
		$can_use_wb = 0;
		$is_no_rate = 1; //打不打折
	}
    $smarty->assign('CanUserWb', $can_use_wb);
	//取得订货人个人信息如果为登录用户
	if($_SESSION['user_id'])

	{
		$current_user_info=get_current_user_info($_SESSION['user_id']);

		if($current_user_info['city_id']==1)
		{
			$NextAddList = get_regions($TopAddList[0]['region_id']);
		}else
		{
			$NextAddList = get_regions($TopAddList[1]['region_id']);
		}

        //北京银行国航的不显示
        if ($_SESSION['user_id'] != '925040' || $_SESSION['user_id'] == '938300')
        {
            $smarty->assign('current_user_info',$current_user_info);
        }
	}else
	{
		$NextAddList = get_regions($TopAddList[0]['region_id']);
	}
	$getDateandtime = local_date('Y-m-d');
	if($_SESSION['user_id']>0)
	{
		$smarty->assign('account', floor(get_user_account($_SESSION['user_id'])));
		$smarty->assign('point', get_user_point($_SESSION['user_id'],$order_id));
		$smarty->assign('bonus',get_user_bonus_by_uid($_SESSION['user_id']));
	}
	else {
		$smarty->assign('account', 0);
		$smarty->assign('point', 0);
	}
	//市场价
	$order_shichangjia = 0;
	foreach($cart_goods['goods_list'] as $key=>$val){
		$order_shichangjia = $order_shichangjia + $val['shop_price']*$val['goods_number'];
	}

	//判断购物车中是否有购买储值卡，才让其参加储值卡活动。
	$czkarray = array();
	$czkarray = array('32343', '32344', '32346');
	foreach ($cart_goods['goods_list'] as $czkvalue) {
		if (in_array($czkvalue['goods_id'], $czkarray))
		{
			$smarty->assign('pdczkhd', 'pdczkhd111');
		}
	}

	//婚礼蛋糕不能货到付款
	$hlarray = array();
	$hlarray = array('32679', '28683', '7517', '32543', '32544');
	foreach ($cart_goods['goods_list'] as $czkvalue) {
		if (in_array($czkvalue['goods_id'], $hlarray))
		{
			$smarty->assign('pdhdfk', 12212111);
		}
	}
	
	//测试财付通
	if ($_SESSION['user_id'] == '905460')
	{
		$smarty->assign('showcft', '905460');
	}

	$smarty->assign('miniDate',local_date('Y-m-d',time()));
	$smarty->assign('is_no_rate',$is_no_rate);
	$smarty->assign('shichangjia',$order_shichangjia);
	$smarty->assign('getDateandtime', $getDateandtime);
    $smarty->assign('NextAddList', $NextAddList);
	$smarty->assign('currency_format', $_CFG['currency_format']);
	$smarty->assign('integral_scale',  $_CFG['integral_scale']);
	$smarty->assign('step',            $_REQUEST['step']);
	$smarty->display('flow_order.dwt');
}
?>
