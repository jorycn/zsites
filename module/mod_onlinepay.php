<?php
if (!defined('IN_CONTEXT')) die('access violation error!');
class ModOnlinepay extends Module {
protected $_filters = array(
'check_login'=>''
);
public function index() {
$this->assign('page_title',__('Online Payment'));
$curr_user_id = SessionHolder::get('user/id');
$curr_order_id = ParamHolder::get('o_id',0);
if (intval($curr_order_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$o_order = new OnlineOrder();
$curr_order =&$o_order->find("id=? AND user_id=?",array($curr_order_id,$curr_user_id));
if (!$curr_order) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$this->assign('curr_order',$curr_order);
$this->_getEnabledPayAccounts();
}
public function saving() {
$this->assign('page_title',__('Online Saving'));
$this->_getEnabledPayAccounts();
}
public function do_payment() {
$this->assign('page_title',__('Sending Payment Infomation, please wait...'));
$curr_user_id = SessionHolder::get('user/id');
if (!$curr_user_id) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$curr_order_id = ParamHolder::get('o_id',0);
if (intval($curr_order_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$o_order = new OnlineOrder();
$curr_order =&$o_order->find("id=? AND user_id=?",array($curr_order_id,$curr_user_id));
if (!$curr_order) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$order_prods = __('Product Order');
$curr_order->loadRelatedObjects(REL_CHILDREN);
$prd_num = sizeof($curr_order->slaves['OrderProduct']);
if (sizeof($curr_order->slaves['OrderProduct']) >0) {
$order_prods = '';
foreach ($curr_order->slaves['OrderProduct'] as $order_product) {
$order_prods .= $order_product->product_name.';';
}
}
$payacct_id =&ParamHolder::get('paygate',0);
if (intval($payacct_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$curr_payacct = new PaymentAccount($payacct_id);
$curr_payacct->loadRelatedObjects(REL_PARENT);
$new_histo = new OnlinepayHistory();
$new_histo->user_id = $curr_user_id;
$new_histo->outer_oid = "ord".$curr_order->oid;
$new_histo->payment_provider_id = $curr_payacct->payment_provider_id;
$new_histo->send_time = time();
$new_histo->return_time = 0;
$new_histo->finished = '0';
$spec_code = ">$curr_user_id,{$new_histo->send_time}";
if ($curr_payacct->masters['PaymentProvider']->name == 'alipay') {
$strReturn = str_replace('index.php','onlinepay/alipay/return.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$strNotify = str_replace('index.php','onlinepay/alipay/notify.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
include_once(ROOT.'/onlinepay/alipay/alipay_service.php');
$parameter = array(
"service"=>"trade_create_by_buyer",
"partner"=>$curr_payacct->partner_id,
"return_url"=>$strReturn,
"notify_url"=>$strNotify,
"_input_charset"=>"utf-8",
"subject"=>$order_prods,
"body"=>$order_prods.$spec_code,
"out_trade_no"=>"ord".$curr_order->oid,
"price"=>strval($curr_order->discount_price),
"payment_type"=>"1",
"quantity"=>"1",
"logistics_fee"=>strval($curr_order->delivery_fee),
"logistics_payment"=>'BUYER_PAY',
"logistics_type"=>'EXPRESS',
"show_url"=>$curr_payacct->seller_site_url,
"seller_email"=>$curr_payacct->seller_account     
);
$alipay = new alipay_service($parameter,$curr_payacct->partner_key,"MD5");
$link = $alipay->create_url();
$postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == '99bill') {
$curr_user = new User(SessionHolder::get('user/id'));
$strReceive = str_replace('index.php','onlinepay/99bill/receive.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$merchantAcctId = $curr_payacct->partner_id;
$key = $curr_payacct->partner_key;
$inputCharset = "1";
$bgUrl = $strReceive;
$version = "v2.0";
$language = "1";
$signType = "1";
$payerName = $curr_user->login;
$payerContactType = "1";
$payerContact = $curr_user->email;
$orderId = "ord".$curr_order->oid;
$orderAmount = strval(intval(floatval($curr_order->total_amount) * 100));
$orderTime = date('YmdHis');
$productName = $order_prods;
$productNum = $prd_num;
$productId = "";
$productDesc = $order_prods;
$ext1 = $spec_code;
$ext2 = "";
$payType = "00";
$redoFlag = "0";
$pid = "";
$signMsgVal = $this->_appendParam($signMsgVal,"inputCharset",$inputCharset);
$signMsgVal = $this->_appendParam($signMsgVal,"bgUrl",$bgUrl);
$signMsgVal = $this->_appendParam($signMsgVal,"version",$version);
$signMsgVal = $this->_appendParam($signMsgVal,"language",$language);
$signMsgVal = $this->_appendParam($signMsgVal,"signType",$signType);
$signMsgVal = $this->_appendParam($signMsgVal,"merchantAcctId",$merchantAcctId);
$signMsgVal = $this->_appendParam($signMsgVal,"payerName",$payerName);
$signMsgVal = $this->_appendParam($signMsgVal,"payerContactType",$payerContactType);
$signMsgVal = $this->_appendParam($signMsgVal,"payerContact",$payerContact);
$signMsgVal = $this->_appendParam($signMsgVal,"orderId",$orderId);
$signMsgVal = $this->_appendParam($signMsgVal,"orderAmount",$orderAmount);
$signMsgVal = $this->_appendParam($signMsgVal,"orderTime",$orderTime);
$signMsgVal = $this->_appendParam($signMsgVal,"productName",$productName);
$signMsgVal = $this->_appendParam($signMsgVal,"productNum",$productNum);
$signMsgVal = $this->_appendParam($signMsgVal,"productId",$productId);
$signMsgVal = $this->_appendParam($signMsgVal,"productDesc",$productDesc);
$signMsgVal = $this->_appendParam($signMsgVal,"ext1",$ext1);
$signMsgVal = $this->_appendParam($signMsgVal,"ext2",$ext2);
$signMsgVal = $this->_appendParam($signMsgVal,"payType",$payType);
$signMsgVal = $this->_appendParam($signMsgVal,"redoFlag",$redoFlag);
$signMsgVal = $this->_appendParam($signMsgVal,"pid",$pid);
$signMsgVal = $this->_appendParam($signMsgVal,"key",$key);
$signMsg= strtoupper(md5($signMsgVal));
$postform = "<form name=\"kqPay\" method=\"post\" action=\"https://www.99bill.com/gateway/recvMerchantInfoAction.htm\">
    <input type=\"hidden\" name=\"inputCharset\" value=\"$inputCharset\"/>
    <input type=\"hidden\" name=\"bgUrl\" value=\"$bgUrl\"/>
    <input type=\"hidden\" name=\"version\" value=\"$version\"/>
    <input type=\"hidden\" name=\"language\" value=\"$language\"/>
    <input type=\"hidden\" name=\"signType\" value=\"$signType\"/>
    <input type=\"hidden\" name=\"signMsg\" value=\"$signMsg\"/>
    <input type=\"hidden\" name=\"merchantAcctId\" value=\"$merchantAcctId\"/>
    <input type=\"hidden\" name=\"payerName\" value=\"$payerName\"/>
    <input type=\"hidden\" name=\"payerContactType\" value=\"$payerContactType\"/>
    <input type=\"hidden\" name=\"payerContact\" value=\"$payerContact\"/>
    <input type=\"hidden\" name=\"orderId\" value=\"$orderId\"/>
    <input type=\"hidden\" name=\"orderAmount\" value=\"$orderAmount\"/>
    <input type=\"hidden\" name=\"orderTime\" value=\"$orderTime\"/>
    <input type=\"hidden\" name=\"productName\" value=\"$productName\"/>
    <input type=\"hidden\" name=\"productNum\" value=\"$productNum\"/>
    <input type=\"hidden\" name=\"productId\" value=\"$productId\"/>
    <input type=\"hidden\" name=\"productDesc\" value=\"$productDesc\"/>
    <input type=\"hidden\" name=\"ext1\" value=\"$ext1\"/>
    <input type=\"hidden\" name=\"ext2\" value=\"$ext2\"/>
    <input type=\"hidden\" name=\"payType\" value=\"$payType\"/>
    <input type=\"hidden\" name=\"redoFlag\" value=\"$redoFlag\"/>
    <input type=\"hidden\" name=\"pid\" value=\"$pid\"/>
</form>
<script type=\"text/javascript\" language=\"javascript\">
<!--
    document.forms[\"kqPay\"].submit();
//-->
</script>
";
$new_histo->save();
}elseif($curr_payacct->masters['PaymentProvider']->name == 'alipaymed') {
$return_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$notify_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
$parameter = array(
"service"=>"create_partner_trade_by_buyer",
"payment_type"=>"1",
"partner"=>$curr_payacct->partner_id,
"seller_email"=>$curr_payacct->seller_account,
"return_url"=>$return_url,
"notify_url"=>$notify_url,
"_input_charset"=>"utf-8",
"show_url"=>$curr_payacct->seller_site_url,
"subject"=>$order_prods,
"body"=>$order_prods.$spec_code,
"out_trade_no"=>"ord".$curr_order->oid,
"price"=>strval($curr_order->discount_price),
"quantity"=>"1",
"logistics_fee"=>strval($curr_order->delivery_fee),
"logistics_payment"=>'BUYER_PAY',
"logistics_type"=>'EXPRESS'
);
$alipay = new alipay_service($parameter,$curr_payacct->partner_key,"MD5");
$link = $alipay->create_url();
$postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == 'paypal') {
$notify_url = str_replace('index.php','onlinepay/paypal/paypal_notify.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$return_url = str_replace('index.php','onlinepay/paypal/paypal_return.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$amount = strval($curr_order->discount_price);
$shipping = strval($curr_order->delivery_fee);
$product_name = __('Product Order');
$paypal_interface_address = 'https://www.paypal.com/cgi-bin/webscr';
$item_number = "ord".$curr_order->oid;
$postform = "<form name=\"kqPay\" action=\"$paypal_interface_address\" method=\"post\">
<input type=\"hidden\" name=\"notify_url\" value=\"$notify_url\" /><!--测试地址待修改-->
<input type=\"hidden\" name=\"return\" value=\"$return_url\" /><!--测试地址待修改-->
<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
<input type=\"hidden\" name=\"business\" value=\"$curr_payacct->seller_account\">
<input type=\"hidden\" name=\"item_name\" value=\"$product_name\">
<input type=\"hidden\" name=\"item_number\" value=\"$item_number\">
<input type=\"hidden\" name=\"currency_code\" value=\"CNY\"><!--CNY 人民币 USD 美元-->
<input type=\"hidden\" name=\"amount\" value=\"$amount\">
<input type=\"hidden\" name=\"shipping\" value=\"$shipping\">
<input type=\"hidden\" name=\"custom\" value=\"$spec_code\">
<input type=\"hidden\" name=\"charset\" value=\"utf-8\"> 
<input type='hidden' name='no_note' value=''>
</form>
<script type=\"text/javascript\" language=\"javascript\">
<!--
    document.forms[\"kqPay\"].submit();
//-->
</script>
";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == 'paypalen') {
$notify_url = str_replace('index.php','onlinepay/paypal/paypal_notify.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$return_url = str_replace('index.php','onlinepay/paypal/paypal_return.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$amount = strval($curr_order->discount_price);
$shipping = strval($curr_order->delivery_fee);
$product_name = __('Product Order');
$paypal_interface_address = 'https://www.paypal.com/cgi-bin/webscr';
$item_number = "ord".$curr_order->oid;
$postform = "<form name=\"kqPay\" action=\"$paypal_interface_address\" method=\"post\">
<input type=\"hidden\" name=\"notify_url\" value=\"$notify_url\" /><!--测试地址待修改-->
<input type=\"hidden\" name=\"return\" value=\"$return_url\" /><!--测试地址待修改-->
<input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
<input type=\"hidden\" name=\"business\" value=\"$curr_payacct->seller_account\">
<input type=\"hidden\" name=\"item_name\" value=\"$product_name\">
<input type=\"hidden\" name=\"item_number\" value=\"$item_number\">
<input type=\"hidden\" name=\"currency_code\" value=\"CNY\"><!--CNY 人民币 USD 美元-->
<input type=\"hidden\" name=\"amount\" value=\"$amount\">
<input type=\"hidden\" name=\"shipping\" value=\"$shipping\">
<input type=\"hidden\" name=\"custom\" value=\"$spec_code\">
<input type=\"hidden\" name=\"charset\" value=\"utf-8\"> 
<input type='hidden' name='no_note' value=''>
</form>
<script type=\"text/javascript\" language=\"javascript\">
<!--
    document.forms[\"kqPay\"].submit();
//-->
</script>
";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == 'moneybookers') {
$notify_url = str_replace('index.php','onlinepay/paypal/paypal_notify.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$return_url = str_replace('index.php','onlinepay/paypal/paypal_return.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$amount = strval($curr_order->discount_price);
$shipping = strval($curr_order->delivery_fee);
$product_name = __('Product Order');
$paypal_interface_address = 'https://www.moneybookers.com/app/payment.pl';
$item_number = "ord".$curr_order->oid;
$CURRENCY = CURRENCY;
$postform = "<form name=\"kqPay\" action=\"$paypal_interface_address\" method=\"post\">
<input type=\"hidden\" name=\"pay_to_email\" value=\"$curr_payacct->seller_account\">
<input type=\"hidden\" name=\"status_url\" value=\"$curr_payacct->seller_account\">
<input type=\"hidden\" name=\"language\" value=\"CN\">
<input type=\"hidden\" name=\"amount\" value=\"$amount\">
<input type=\"hidden\" name=\"currency\" value=\"$CURRENCY\">
<input type=\"hidden\" name=\"detail1_description\" value=\"$product_name\">
<input type=\"hidden\" name=\"detail1_text\" value=\"$product_name\">
</form>
<script type=\"text/javascript\" language=\"javascript\">
<!--
    document.forms[\"kqPay\"].submit();
//-->
</script>
";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == 'tencentimd') {
include_once("onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/classes/PayRequestHandler.class.php");
$bargainor_id = $curr_payacct->seller_account;
$key = $curr_payacct->partner_key;
$return_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$strDate = date("Ymd");
$strTime = date("His");
$randNum = rand(1000,9999);
$strReq = $strTime .$randNum;
$sp_billno = "ord".$curr_order->oid;
$transaction_id = $bargainor_id .$strDate .$strReq;
$total_fee = strval(intval(floatval($curr_order->total_amount) * 100));
$product_name = $order_prods;
$desc = mb_convert_encoding($product_name,'GB2312','UTF-8');
$attach = ">{$curr_user_id}_{$new_histo->send_time}_{$sp_billno}";
$reqHandler = new PayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setParameter("bargainor_id",$bargainor_id);
$reqHandler->setParameter("sp_billno",$sp_billno);
$reqHandler->setParameter("transaction_id",$transaction_id);
$reqHandler->setParameter("total_fee",$total_fee);
$reqHandler->setParameter("return_url",$return_url);
$reqHandler->setParameter("desc",$desc);
$reqHandler->setParameter("attach",$attach);
$reqHandler->setParameter("spbill_create_ip",$_SERVER['REMOTE_ADDR']);
$new_histo->save();
$reqHandler->doSend();
}else if($curr_payacct->masters['PaymentProvider']->name == 'tencentmed') {
include_once("onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/classes/MediPayRequestHandler.class.php");
$curDateTime = date("YmdHis");
$randNum = rand(1000,9999);
$key = $curr_payacct->partner_key;
$chnid = $curr_payacct->seller_account;
$seller = $curr_payacct->seller_account;
$mch_desc = __('Thank you for your shopping');
$mch_desc = mb_convert_encoding($mch_desc,'GB2312','UTF-8');
$mch_name = $order_prods;
$mch_name = mb_convert_encoding($mch_name,'GB2312','UTF-8');
$mch_price = strval(intval(floatval($curr_order->discount_price) * 100));
$transport_fee = strval(intval(floatval($curr_order->delivery_fee) * 100));
$mch_returl = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/mch_returl.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$mch_vno = "ord".$curr_order->oid;
$show_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/show_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$transport_desc = __('Depending on bargainor');
$transport_desc = mb_convert_encoding($transport_desc,'GB2312','UTF-8');
$attach = ">{$curr_user_id}_{$new_histo->send_time}_{$mch_vno}";
$reqHandler = new MediPayRequestHandler();
$reqHandler->init();
$reqHandler->setKey($key);
$reqHandler->setParameter("chnid",$chnid);
$reqHandler->setParameter("encode_type","1");
$reqHandler->setParameter("mch_desc",$mch_desc);
$reqHandler->setParameter("mch_name",$mch_name);
$reqHandler->setParameter("mch_price",$mch_price);
$reqHandler->setParameter("mch_returl",$mch_returl);
$reqHandler->setParameter("mch_type","1");
$reqHandler->setParameter("mch_vno",$mch_vno);
$reqHandler->setParameter("need_buyerinfo","2");
$reqHandler->setParameter("seller",$seller);
$reqHandler->setParameter("show_url",$show_url);
$reqHandler->setParameter("transport_desc",$transport_desc);
$reqHandler->setParameter("transport_fee",$transport_fee);
$reqHandler->setParameter("attach",$attach);
$new_histo->save();
$reqHandler->doSend();
}elseif($curr_payacct->masters['PaymentProvider']->name == 'alipayimd') {
$return_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$notify_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
$parameter = array(
"service"=>"create_direct_pay_by_user",
"payment_type"=>"1",
"partner"=>$curr_payacct->partner_id,
"seller_email"=>$curr_payacct->seller_account,
"return_url"=>$return_url,
"notify_url"=>$notify_url,
"_input_charset"=>"utf-8",
"show_url"=>$curr_payacct->seller_site_url,
"out_trade_no"=>"ord".$curr_order->oid,
"subject"=>$order_prods,
"body"=>$order_prods.$spec_code,
"total_fee"=>strval($curr_order->discount_price) +strval($curr_order->delivery_fee)           
);
$alipay = new alipay_service($parameter,$curr_payacct->partner_key,"MD5");
$link = $alipay->create_url();
$postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";
$new_histo->save();
}else {
$postform = __('Payment gateway not supported!');
}
$this->assign('postform',$postform);
}
public function do_sav_payment() {
$this->assign('page_title',__('Sending Payment Infomation, please wait...'));
$curr_user_id = SessionHolder::get('user/id');
if (!$curr_user_id) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$saving_amount = ParamHolder::get('amount','0.00');
if (number_format($saving_amount,2) == '0.00') {
$this->assign('json',Toolkit::jsonERR(__('Saving amount could not be empty or 0.00')));
return '_error';
}
$payacct_id =&ParamHolder::get('paygate',0);
if (intval($payacct_id) == 0) {
$this->assign('json',Toolkit::jsonERR(__('Invalid ID!')));
return '_error';
}
$curr_payacct = new PaymentAccount($payacct_id);
$curr_payacct->loadRelatedObjects(REL_PARENT);
$order_seed = date('YmdHis');
$new_histo = new OnlinepayHistory();
$new_histo->user_id = $curr_user_id;
$new_histo->outer_oid = "sav".$order_seed;
$new_histo->payment_provider_id = $curr_payacct->payment_provider_id;
$new_histo->send_time = time();
$new_histo->return_time = 0;
$new_histo->finished = '0';
$spec_code = ">$curr_user_id,{$new_histo->send_time}";
if ($curr_payacct->masters['PaymentProvider']->name == 'alipay') {
$strReturn = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$strNotify = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
include_once(ROOT.'/onlinepay/alipay/alipay_service.php');
$parameter = array(
"service"=>"trade_create_by_buyer",
"partner"=>$curr_payacct->partner_id,
"return_url"=>$strReturn,
"notify_url"=>$strNotify,
"_input_charset"=>"utf-8",
"subject"=>__('Online Saving'),
"body"=>$spec_code,
"out_trade_no"=>"sav".$order_seed,
"price"=>strval($saving_amount),
"payment_type"=>"1",
"quantity"=>"1",
"logistics_fee"=>"0",
"logistics_payment"=>'BUYER_PAY',
"logistics_type"=>'EXPRESS',
"show_url"=>$curr_payacct->seller_site_url,
"seller_email"=>$curr_payacct->seller_account     
);
$alipay = new alipay_service($parameter,$curr_payacct->partner_key,"MD5");
$link = $alipay->create_url();
$postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";
$new_histo->save();
}else if ($curr_payacct->masters['PaymentProvider']->name == '99bill') {
$strReceive = str_replace('index.php','onlinepay/99bill/receive.php','http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$curr_user = new User(SessionHolder::get('user/id'));
$merchantAcctId = $curr_payacct->partner_id;
$key = $curr_payacct->partner_key;
$inputCharset = "1";
$bgUrl = $strReceive;
$version = "v2.0";
$language = "1";
$signType = "1";
$payerName = $curr_user->login;
$payerContactType = "1";
$payerContact = $curr_user->email;
$orderId = "sav".$order_seed;
$orderAmount = strval(intval(floatval($saving_amount) * 100));
$orderTime = date('YmdHis');
$productName = __('Online Saving');
$productNum = 0;
$productId = "";
$productDesc = "";
$ext1 = $spec_code;
$ext2 = "";
$payType = "00";
$redoFlag = "0";
$pid = $curr_payacct->seller_account;
$signMsgVal = $this->_appendParam($signMsgVal,"inputCharset",$inputCharset);
$signMsgVal = $this->_appendParam($signMsgVal,"bgUrl",$bgUrl);
$signMsgVal = $this->_appendParam($signMsgVal,"version",$version);
$signMsgVal = $this->_appendParam($signMsgVal,"language",$language);
$signMsgVal = $this->_appendParam($signMsgVal,"signType",$signType);
$signMsgVal = $this->_appendParam($signMsgVal,"merchantAcctId",$merchantAcctId);
$signMsgVal = $this->_appendParam($signMsgVal,"payerName",$payerName);
$signMsgVal = $this->_appendParam($signMsgVal,"payerContactType",$payerContactType);
$signMsgVal = $this->_appendParam($signMsgVal,"payerContact",$payerContact);
$signMsgVal = $this->_appendParam($signMsgVal,"orderId",$orderId);
$signMsgVal = $this->_appendParam($signMsgVal,"orderAmount",$orderAmount);
$signMsgVal = $this->_appendParam($signMsgVal,"orderTime",$orderTime);
$signMsgVal = $this->_appendParam($signMsgVal,"productName",$productName);
$signMsgVal = $this->_appendParam($signMsgVal,"productNum",$productNum);
$signMsgVal = $this->_appendParam($signMsgVal,"productId",$productId);
$signMsgVal = $this->_appendParam($signMsgVal,"productDesc",$productDesc);
$signMsgVal = $this->_appendParam($signMsgVal,"ext1",$ext1);
$signMsgVal = $this->_appendParam($signMsgVal,"ext2",$ext2);
$signMsgVal = $this->_appendParam($signMsgVal,"payType",$payType);
$signMsgVal = $this->_appendParam($signMsgVal,"redoFlag",$redoFlag);
$signMsgVal = $this->_appendParam($signMsgVal,"pid",$pid);
$signMsgVal = $this->_appendParam($signMsgVal,"key",$key);
$signMsg= strtoupper(md5($signMsgVal));
$postform = "<form name=\"kqPay\" method=\"post\" action=\"https://www.99bill.com/gateway/recvMerchantInfoAction.htm\">
    <input type=\"hidden\" name=\"inputCharset\" value=\"$inputCharset\"/>
    <input type=\"hidden\" name=\"bgUrl\" value=\"$bgUrl\"/>
    <input type=\"hidden\" name=\"version\" value=\"$version\"/>
    <input type=\"hidden\" name=\"language\" value=\"$language\"/>
    <input type=\"hidden\" name=\"signType\" value=\"$signType\"/>
    <input type=\"hidden\" name=\"signMsg\" value=\"$signMsg\"/>
    <input type=\"hidden\" name=\"merchantAcctId\" value=\"$merchantAcctId\"/>
    <input type=\"hidden\" name=\"payerName\" value=\"$payerName\"/>
    <input type=\"hidden\" name=\"payerContactType\" value=\"$payerContactType\"/>
    <input type=\"hidden\" name=\"payerContact\" value=\"$payerContact\"/>
    <input type=\"hidden\" name=\"orderId\" value=\"$orderId\"/>
    <input type=\"hidden\" name=\"orderAmount\" value=\"$orderAmount\"/>
    <input type=\"hidden\" name=\"orderTime\" value=\"$orderTime\"/>
    <input type=\"hidden\" name=\"productName\" value=\"$productName\"/>
    <input type=\"hidden\" name=\"productNum\" value=\"$productNum\"/>
    <input type=\"hidden\" name=\"productId\" value=\"$productId\"/>
    <input type=\"hidden\" name=\"productDesc\" value=\"$productDesc\"/>
    <input type=\"hidden\" name=\"ext1\" value=\"$ext1\"/>
    <input type=\"hidden\" name=\"ext2\" value=\"$ext2\"/>
    <input type=\"hidden\" name=\"payType\" value=\"$payType\"/>
    <input type=\"hidden\" name=\"redoFlag\" value=\"$redoFlag\"/>
    <input type=\"hidden\" name=\"pid\" value=\"$pid\"/>
</form>
<script type=\"text/javascript\" language=\"javascript\">
<!--
    document.forms[\"kqPay\"].submit();
//-->
</script>
";
$new_histo->save();
}else if($curr_payacct->masters['PaymentProvider']->name == 'alipayimd') {
$return_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/return_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
$notify_url = str_replace('index.php',"onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/notify_url.php",'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
include_once(ROOT."/onlinepay/{$curr_payacct->masters['PaymentProvider']->name}/class/alipay_service.php");
$parameter = array(
"service"=>"create_direct_pay_by_user",
"payment_type"=>"1",
"partner"=>$curr_payacct->partner_id,
"seller_email"=>$curr_payacct->seller_account,
"return_url"=>$return_url,
"notify_url"=>$notify_url,
"_input_charset"=>"utf-8",
"show_url"=>$curr_payacct->seller_site_url,
"out_trade_no"=>"sav".$order_seed,
"subject"=>__('Online Saving'),
"body"=>$spec_code,
"total_fee"=>strval($saving_amount)
);
$alipay = new alipay_service($parameter,$curr_payacct->partner_key,"MD5");
$link = $alipay->create_url();
$postform = "<script type=\"text/javascript\" language=\"javascript\">\n<!--\nwindow.location.href =\"$link\";\n//-->\n</script>\n";
$new_histo->save();
}else {
$postform = __('Payment gateway not supported!');
}
$this->assign('postform',$postform);
return 'do_payment';
}
private function _appendParam($returnStr,$paramId,$paramValue) {
if ($returnStr != "") {
if ($paramValue != "") {
$returnStr .= "&".$paramId."=".$paramValue;
}
}else {
if ($paramValue != "") {
$returnStr = $paramId."=".$paramValue;
}
}
return $returnStr;
}
private function _getEnabledPayAccounts() {
$acct_select_array = array();
$o_payacct = new PaymentAccount();
$enabled_accts =&$o_payacct->findAll("`enabled`='1'");
if (sizeof($enabled_accts) >0) {
foreach ($enabled_accts as $account) {
$account->loadRelatedObjects(REL_PARENT);
$acct_select_array[strval($account->id)] = $account->masters['PaymentProvider']->disp_name;
}
}
$this->assign('payaccts',$acct_select_array);
}
}