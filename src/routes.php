<?php
// Routes
require('db.php');
//require('errorlist.php');
require('objects/object.php');
require('objects/merchant.php');
require('objects/branch.php');
require('objects/user.php');
require('objects/wristband.php');
require('objects/wristbanduser.php');
require('objects/order.php');
require('objects/product.php');
require('objects/orderdetail.php');


$app->post('/merchant',  function ($request, $response)  {
     try{
        $allPostPutVars = $request->getParsedBody();

        $merchant = new Merchant();
        $merchant->companyName = $allPostPutVars['companyName'];
        $merchant->contactName = $allPostPutVars['contactName'];
        $merchant->phone = $allPostPutVars['phone'];
        $merchant->email = $allPostPutVars['email'];
        $merchant->bankAccount = $allPostPutVars['bankAccount'];
        $merchant->bankName = $allPostPutVars['bankName'];
        $merchant->rfc = $allPostPutVars['rfc'];

        $cn = new db($this->logger);
        
        //TODO: Validate company name and email are unique values
        //Add the merchant
        $message = $cn->add_merchant($merchant);
        if($message == "success"){
            $data = array("status"=>"success","data"=>array("merchantID"=>$merchant->merchantID,"branchID"=>$merchant->branchList[0]->branchID)); 
            return json_encode($data);
        }
        else{
            $data = array("status"=>"error","data"=>$message);
            $this->logger->error("Routes.php",array("Funcion"=>"merchant", "companyName"=>$merchant->companyName, "Causa"=>$message));
            return json_encode($data);
        }
    }
    catch(Exception $e)
    {
         $this->logger->error("Routes.php",array("Funcion"=>"merchant", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/testmerchant',  function ($request, $response)  {

     try{
        $allPostPutVars = $request->getParsedBody();

        $merchant = new Merchant();
        $merchant->companyName = "Payzi";
        $merchant->contactName = "Payzi";
        $merchant->phone = "23432423";
        $merchant->email = "23423@sdf.com";
        $merchant->bankAccount = "qwerqwer";
        $merchant->bankName = "Payzi";
        $merchant->rfc = "Payzi";

        $cn = new db($this->logger);
        
        //TODO: Validate company name and email are unique values
        
        //Add the merchant
        $message = $cn->add_merchant($merchant);
        if($message == "success"){
            $data = array("status"=>"success","data"=>array("merchantID"=>$merchant->merchantID,"branchID"=>$merchant->branchList[0]->branchID)); 
            return json_encode($data);
        }
        else{
            $data = array("status"=>"error","data"=>$message);
            $this->logger->error("Routes.php",array("Funcion"=>"merchant", "companyName"=>$merchant->companyName, "Causa"=>$message));
            return json_encode($data);
        }
    }
    catch(Exception $e)
    {
         $this->logger->error("Routes.php",array("Funcion"=>"merchant", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/get_sales_by_merchant/{merchantID}',  function ($request, $response, $args)  {

    $cn = new db($this->logger);
    $orders = $cn->get_sales_by_merchant($args["merchantID"]);
    if (is_array($orders) && $orders[0]!= null) {
        $data = array("status"=>"success","data"=>salesInJSON($orders)); 
    }
    else{
         $data = array("status"=>"error","data"=>array("error"=>"No records".$orders)); 
    }
     return json_encode($data);
});

$app->get('/testsales/{merchantID}',  function ($request, $response, $args)  {

    $cn = new db($this->logger);
    $orders = $cn->get_sales($args["merchantID"]);
    if (is_array($orders) && $orders[0]!= null) {
        $data = array("status"=>"success","data"=>salesInJSON($orders)); 
    }
    else{
         $data = array("status"=>"error","data"=>array("error"=>"No records".$orders)); 
    }
     return json_encode($data);
});

$app->post('/merchantUser', function($request, $response) {

    try{
        $allPostVars = $request->getParsedBody();
        
        $user = new User();
        $user->merchantID = $allPostVars['merchantID'];
        $user->branchID = $allPostVars['branchID'];
        $user->userName = $allPostVars['userName'];
        $user->password = $allPostVars['password'];
        $user->admin = $allPostVars['admin'];
        if($user->admin == "")
            $user->admin = 0;
        
        $cn = new db($this->logger);
        
        $message = $cn->add_merchant_user($user);
        if($message == "success"){
            $data = array("status"=>"success","data"=>array("nodatayet"));
            return json_encode($data);
        }
        else{
            $data = array("status"=>"error","data"=>$message);
            return json_encode($data);
        }
    }
    catch(Exception $e){

        $this->logger->error("Routes.php",array("Funcion"=>"merchantUser", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});


$app->get('/testmerchantuser', function($request, $response) {

    try{
        
        $user = new User();
        $user->merchantID = "121";
        $user->branchID = "70";
        $user->userName = "c";
        $user->password = "C";
        $user->admin = "1";
        if($user->admin == "")
            $user->admin = 0;
        
        $cn = new db($this->logger);
        
        $message = $cn->add_merchant_user($user);
        if($message == "success"){
            $data = array("status"=>"success","data"=>array("nodatayet"));
            return json_encode($data);
        }
        else{
            $data = array("status"=>"error","data"=>$message);
            return json_encode($data);
        }
    }
    catch(Exception $e){
        $this->logger->error("Routes.php",array("Funcion"=>"merchantUser", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/login/{userName}/{password}', function ($request, $response, $args) {
    $cn = new db($this->logger);
    $user = $cn->get_user($args["userName"]);

    
    if($user->password == $args["password"]){
        
        $data = array("status"=>"success","data"=>array("userID"=>$user->userID,"merchantID"=>$user->merchantID,"branchID"=>$user->branchID));
    }
    else{
        if($user->password == ""){
            $data = array("status"=>"error","data"=>array("errorMessage"=>"El usuario no existe"));
        }else{
            $data = array("status"=>"error","data"=>array("errorMessage"=>"El password es incorrecto"));
        }
    }
    
    return json_encode($data);
    
});

$app->get('/testgift/{userID}', function ($request, $response, $args) {
    
    
    $cn = new db($this->logger);
    $ret = $cn->use_gift_balance($args["userID"]);
    
    echo $ret;
    
});

$app->post('/pay',  function ($request, $response)  {
    try{
        $allPostPutVars = $request->getParsedBody();
       
        $order = new Order();
        $order->userID = $allPostPutVars['userID'];
        $order->amount = $allPostPutVars['amount'];
        $orderDetail = json_decode($allPostPutVars['orderDetail'],true); 
        
       
        $cn = new db($this->logger);
        
        $wristband = $cn->get_wristband($allPostPutVars['wristbandKey']);
        $order->wristband = $wristband;
        $errorMessage = "";
        $status = "";
        $returnArray = null;
        $message="";
        $orderID = null;
       
        if(strcmp($wristband->wristbandID,$allPostPutVars["wristbandKey"])==0){
            if($wristband->validateKey()){
                $useGiftBalance = false;
                if($cn->use_gift_balance($order->userID) == "1"){
                    $useGiftBalance = true;
                }
                $paymentBalance = $wristband->validatePayment($order->amount, $useGiftBalance,$orderDetail);
                if($paymentBalance != 4){
                    //Add the payment
                    
                    $message = $cn->add_order($order, $paymentBalance);
                    //Create the detail
                    if($order->orderID > -1){
                        foreach ($orderDetail as $value){
                            foreach($value as $valc){
                                //0-ProductID
                                //1-Description
                                //2-price
                                //3-quantity
                                //4-ext price
                               
                                $od = new OrderDetail();
                                $od->productID =  $valc["ProductID"];
                                $od->quantity = $valc["Quantity"];
                                $od->total = $valc["ExtPrice"];
                                $order->addDetail($od);
                                $message = $cn->add_order_detail($od,$order->orderID);
                            }
                        }
                        
                        /*foreach($order->orderDetail as $value){
                            
                            $message = $cn->add_order_detail($value,$order->orderID);
                            if($message != "success"){
                                break;
                            }
                        }*/
                       
                    }
                }
                else{
                    $errorMessage = "No tienes saldo suficiente";
                }
            }
            else{
                $errorMessage = "La clave esta corrupta!";
            }
        }
        else{
            $errorMessage =  "La pulsera no existe en el sistema Payzi";
        }
        
        if($errorMessage != "" || $message != "success"){
            $data = array("status"=>"error","data"=>array("balance"=>$order->wristband->balance,"giftBalance"=>$order->wristband->giftBalance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
        }
        else{
             $data = array("status"=>"success","data"=>array("balance"=>$order->wristband->balance,"giftBalance"=>$order->wristband->giftBalance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
             $this->logger->error("Routes.php",array("Funcion"=>"pay", "dbError"=>$message, "Causa"=>$errorMessage, "Wristband"=>$order->wristband->wristbandID));
        }
        return json_encode($data);
        
    }
    catch(Exception $e)
    {
        $this->logger->error("Routes.php",array("Funcion"=>"pay", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/testpay/{userID}/{amount}/{wrist}',  function ($request, $response,$args)  {
    try{
        $allPostPutVars = $request->getParsedBody();
       
        $order = new Order();
        $order->userID = $args['userID'];
        $order->amount = $args['amount'];

        $cn = new db($this->logger);
        
        $wristband = $cn->get_wristband($args['wrist']);
        $order->wristband = $wristband;
        $errorMessage = "";
        $status = "";
        $returnArray = null;
        $message="";
        $orderID = null;
       
        if(strcmp($wristband->wristbandID,$args["wrist"])==0){
            if($wristband->validateKey()){
                $useGiftBalance = false;
                if($cn->use_gift_balance($order->userID) == "1"){
                    $useGiftBalance = true;
                }

                $paymentBalance = $wristband->validatePayment($order->amount, $useGiftBalance);
                if($paymentBalance != 4){
                    //Add the payment
                    $message = $cn->add_order($order, $paymentBalance);
                }
                else{
                    $errorMessage = "No tienes saldo suficiente";
                }
            }
            else{
                $errorMessage = "La clave esta corrupta!";
            }
        }
        else{
            $errorMessage =  "La pulsera no existe en el sistema Payzi";
        }
        
        if($errorMessage != "" || $message != "success"){
            $data = array("status"=>"error","data"=>array("balance"=>$order->wristband->balance,"giftBalance"=>$order->wristband->giftBalance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
        }
        else{
             $data = array("status"=>"success","data"=>array("balance"=>$order->wristband->balance,"giftBalance"=>$order->wristband->giftBalance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
        }
        return json_encode($data);
        
    }
    catch(Exception $e)
    {
        $this->logger->error("Routes.php",array("Funcion"=>"testpay", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->post('/recharge',  function ($request, $response)  {
    try{
        $allPostPutVars = $request->getParsedBody();
       
        $cn = new db($this->logger);
        
        $wristband = $cn->get_wristband($allPostPutVars['wristbandKey']);
        $errorMessage = "";
        $status = "";
        $returnArray = null;
        $message="";
       
        if(strcmp($wristband->wristbandID,$allPostPutVars["wristbandKey"])==0){
            if($wristband->validateKey()){
                $message = $cn->recharge_wristband($wristband,$allPostPutVars["amount"]);
            }
            else{
                $errorMessage = "La clave esta corrupta!";
            }
        }
        else{
            $errorMessage =  "La pulsera no existe en el sistema Payzi";
        }
        
        if($errorMessage != "" || $message != "success"){
            $data = array("status"=>"error","data"=>array("balance"=>$wristband->balance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
        }
        else{
             $data = array("status"=>"success","data"=>array("balance"=>$wristband->balance,"errorMessage"=>$errorMessage,"dbError"=>$message)); 
            $this->logger->info("RECARGA",array("Funcion"=>"recharge", "Wristband"=>$wristband->wristbandID, "Amount"=>$allPostPutVars["amount"]));
        }
        return json_encode($data);
        
    }
    catch(Exception $e)
    {
        $this->logger->error("Routes.php",array("Funcion"=>"recharge", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/wristband/{wristbandKey}', function ($request, $response, $args) {
    
    
    $cn = new db($this->logger);
    $wristband = $cn->get_wristband($args["wristbandKey"]);
    
    if(strcmp($wristband->wristbandID,$args["wristbandKey"])==0){
        $data = array("status"=>"success","data"=>array("balance"=>$wristband->balance, "gift"=>$wristband->gift)); 
    }
    else{
         $data = array("status"=>"error","data"=>array("error"=>$wristband)); 
    }
     return json_encode($data);
    
    
});

$app->get('/orders/{userID}', function ($request, $response, $args) {
    
    
    $cn = new db($this->logger);
    $orders = $cn->get_orders_for_user($args["userID"]);
    $total = $cn->get_total_sales_for_user($args["userID"]);
    
    if (is_array($orders) && $orders[0]!= null) {
        $data = array("status"=>"success","data"=>getOrdersInJSON($orders),"total"=>$total); 
    }
    else{
         $data = array("status"=>"error","data"=>array("error"=>"No records")); 
    }
     return json_encode($data);
    
    
});

$app->get('/products/{merchantID}', function ($request, $response, $args) {
    
    
    $cn = new db($this->logger);
    $products = $cn->get_products_by_merchant($args["merchantID"]);
    if (is_array($products) && $products[0]!= null) {
        $data = array("status"=>"success","data"=>getProductsInJSON($products)); 
    }
    else{
         $data = array("status"=>"error","data"=>array("error"=>"No records".$products)); 
    }
     return json_encode($data);
    
    
});

function getOrdersInJSON($orders){
    $json = '{"orders":[';
    foreach($orders as $val){
        $json .= '{';
        $json .= '"WristbandID":'.'"'.$val->wristband.'",';
        $json .= '"Amount":'.'"'.$val->amount.'",';
        $json .= '"Date":'.'"'.$val->date.'"';
        $json .= '},';
    }
    $json = rtrim($json, ",");
    $json .= ']}';
    return $json;
}

function salesInJSON($orders){
    $json = '{"sales":[';
    foreach($orders as $val){
        $json .= '{';
        $json .= '"Amount":'.'"'.$val->amount.'",';
        $json .= '"Date":'.'"'.$val->date.'"';
        $json .= '},';
    }
    $json = rtrim($json, ",");
    $json .= ']}';
    return $json;
}

function getProductsInJSON($products){
    $json = '{"products":[';
    foreach($products as $val){
        $json .= '{';
        $json .= '"ProductID":'.'"'.$val->productID.'",';
        $json .= '"Description":'.'"'.$val->description.'",';
        $json .= '"UnitPrice":'.'"'.$val->unitPrice.'",';
        $json .= '"CategoryID":'.'"'.$val->categoryID.'",';
        $json .= '"Image":'.'"'.$val->image.'",';
        $json .= '"Available":'.'"'.$val->available.'"';
        $json .= '},';
    }
    $json = rtrim($json, ",");
    $json .= ']}';
    return $json;
}

function getAttendenceListInJSON($attendence){
    $json = '{"attendence":[';
    $con = 0;
    foreach($attendence as $val){
        $json .= '{';
        $json .= '"Name":'.'"'.$val["name"].'",';
        $json .= '"Checkin":'.'"'.$val["checkin"].'",';
        $json .= '"Checkout":'.'"'.$val["checkout"].'",';
        $json .= '"Profile":'.'"'.$val["perfil"].'",';
        $json .= '"Faculty":'.'"'.$val["facultad"].'",';
        $json .= '"Phase":'.'"'.$val["etapa"].'",';
        $json .= '"Project":'.'"'.$val["proyecto"].'"';
        $json .= '},';
    }
    $json = rtrim($json, ",");
    $json .= ']}';
    return $json;
}

function getAttendencePieFilterInJSON($attendence){
    $json = '[';
    $con = 0;
    foreach($attendence as $val){
        $json .= '{';
        $json .= '"Column":'.'"'.$val["col"].'",';
        $json .= '"Percentage":'.'"'.$val["percentage"].'"';
        $json .= '},';
    }
    $json = rtrim($json, ",");
    $json .= ']';
    return $json;
}

$app->get('/payTest',  function ($request, $response)  {
    $array = array(array("red", "green", "blue", "yellow"),array("red", "green", "blue", "yellow"));
    //$someJSON = '[{"name":"Jonathan Suh","gender":"male"},{"name":"William Philbin","gender":"male"},{"name":"Allison McKinnery","gender":"female"}]';
    $someJSON = '{"detail":[{"ProductID":"4","Quantity":"2","ExtPrice":"120"},{"ProductID":"4","Quantity":"2","ExtPrice":"120"}]}';
    $arr = json_decode($someJSON,true);
    foreach($arr as $val){
        foreach($val as $val2){
            var_dump($val2["ProductID"]);
        }
    }
});


/*
 * API FOR WEBAPP
 * 
 */

$app->post('/authenticate', function($request, $response) {
    
    $cn = new db($this->logger);
    $allPostPutVars = $request->getParsedBody();
    $user = $cn->get_wristband_user($allPostPutVars['username']);
    if($user->password == $allPostPutVars['password']){
        $data = array("status"=>"success","data"=>array("userID"=>$user->userID,"name"=>$user->name,"lastName"=>$user->lastName,"gift"=>$user->gift,"balance"=>$user->balance));
    }
    else{
        if($user->password == "."){
            $data = array("status"=>"error","data"=>array("errorMessage"=>"El usuario no existe"));
        }else{
            $data = array("status"=>"error","data"=>array("errorMessage"=>"El password es incorrecto"));
        }
    }
    
    return json_encode($data);
});

$app->post('/wristbandUser', function($request, $response) {

    try{
        $allPostVars = $request->getParsedBody();
        
        $user = new WristbandUser();
        $user->wristbandID = $allPostVars['wristbandID'];
        $user->userName = $allPostVars['username'];
        $user->password = $allPostVars['password'];
        $user->name = $allPostVars['name'];
        $user->lastName = $allPostVars['lastName'];
        
        $cn = new db($this->logger);
        
        $message = $cn->add_wristband_user($user);
        if($message == "3"){
            $data = array("status"=>"success","data"=>array("nodatayet"));
            return json_encode($data);
        }
        else{
            $errorm = "";
            if($message == "1"){
                $errorm = "La pulsera ingresada ya se encuentra registrada";
            }
            else if($message == "2"){
                 $errorm = "El correo ya se encuentra registrado";
            }
            else if($message == "4"){
                $errorm = "El codigo de pulsera proporcionado es incorrecto";
            }
            $data = array("status"=>"error","data"=>array("errorMessage"=>$errorm));
            return json_encode($data);
        }
    }
    catch(Exception $e){
        $this->logger->error("Routes.php",array("Funcion"=>"wristbandUser", "Causa"=>$e->getMessage()));
        return $e->getMessage();
    }
});

$app->get('/testwristbanduser', function($request, $response) {

    try{
        $allPostVars = $request->getParsedBody();
        
        $user = new WristbandUser();
        $user->wristbandID = 'atlassss';
        $user->userName = "email5@email.com";
        $user->password = "sam";
        $user->name = "sam";
        $user->lastName = "sam";
        
        $cn = new db($this->logger);
        
        $message = $cn->add_wristband_user($user);
        if($message == "3"){
            $data = array("status"=>"success","data"=>array("nodatayet"));
            return json_encode($data);
        }
        else{
            $errorm = "";
            if($message == "1"){
                $errorm = "La pulsera ingresada ya se encuentra registrada";
            }
            else{
                 $errorm = "El correo ya se encuentra registrado";
            }
            $data = array("status"=>"error","data"=>array("errorMessage"=>$errorm));
            return json_encode($data);
        }
    }
    catch(Exception $e){
        return $e->getMessage();
    }
});

$app->get('/totalsales/{merchantID}', function($request, $response, $args) {

    try{
        $cn = new db($this->logger);
        
        $message = $cn->get_total_sales_for_merchant($args["merchantID"]);
        if($message > "0"){
            $data = array("status"=>"success","data"=>array("Total"=>$message));
            return json_encode($data);
        }
        else{
            $data = array("status"=>"error","data"=>array("errorMessage"=>$message));
            return json_encode($data);
        }
    }
    catch(Exception $e){
        return $e->getMessage();
    }
});

$app->post('/attendence', function($request, $response) {

    try{
        $allPostVars = $request->getParsedBody();
        
        $wristbandID = $allPostVars['wristbandKey'];
        $userID = $allPostVars['userID'];
      
        $cn = new db($this->logger);
        
        $wristband = $cn->get_wristband($wristbandID);
    
        
        if(strcmp($wristband->wristbandID,$wristbandID)==0){
            if($wristband->validateKey()){
                $message = $cn->add_attendence($wristbandID,$userID);
                if(!$message){
                    $errorMessage = "Error con pulsera";
                }
            }
            else{
                $errorMessage = "La clave esta corrupta!";
            }
        }
        else{
            $errorMessage =  "La pulsera no existe en el sistema Payzi";
        }
        
        if($errorMessage != "" || $message != "success"){
            $data = array("status"=>"error","data"=>array("errorMessage"=>$errorMessage,"dbError"=>$message)); 
            $this->logger->error("Routes.php",array("Funcion"=>"attendence", "dbError"=>$message, "Causa"=>$errorMessage, "Wristband"=>$order->wristband->wristbandID));
        }
        else{
             $data = array("status"=>"success","data"=>array("errorMessage"=>$errorMessage,"dbError"=>$message)); 
        }
        return json_encode($data);
        
    }
    catch(Exception $e){
        return $e->getMessage();
    }
});

$app->get('/attendence/list/{userID}/{group}/{date}', function($request, $response,$args) {

    try{
        $userID = $args['userID'];
        $group = $args['group'];
        $date = $args['date'];
      
        $cn = new db($this->logger);
        $resultSet = $cn->get_attendence_list_by_user($userID, $date,$group);
        if (is_array($resultSet)) {
            if ($resultSet[0]!= null){
                $data = array("status"=>"success","data"=>getAttendenceListInJSON($resultSet)); 
            }
            else{
                $data = array("status"=>"success","data"=>""); 
            }
        }else{
            $data = array("status"=>"error","data"=>array("errorMessage"=>"","dbError"=>$resultSet)); 
            $this->logger->error("Routes.php",array("Funcion"=>"attendenceList", "dbError"=>$resultSet, "Causa"=>$resultSet));
        }
        
        return json_encode($data);
        
    }
    catch(Exception $e){
        return $e->getMessage();
    }
});


$app->get('/attendence/pie/filter/{col}/{group}', function($request, $response,$args) {

    try{
        $col = $args['col'];
        $group = $args['group'];
      
        $cn = new db($this->logger);
        $resultSet = $cn->get_attendence_pie_data_filter($col,$group);
        if (is_array($resultSet)) {
            if ($resultSet[0]!= null){
                $data = array("status"=>"success","data"=>getAttendencePieFilterInJSON($resultSet)); 
            }
            else{
                $data = array("status"=>"success","data"=>""); 
            }
        }else{
            $data = array("status"=>"error","data"=>array("errorMessage"=>"","dbError"=>$resultSet)); 
            $this->logger->error("Routes.php",array("Funcion"=>"attendencePieFilter", "dbError"=>$resultSet, "Causa"=>$resultSet));
        }
        
        return json_encode($data);
        
    }
    catch(Exception $e){
        return $e->getMessage();
    }
});