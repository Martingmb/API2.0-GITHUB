<?php
class db
{
    protected $dbms = 'mysql';
    protected $host = 'localhost'; 
    protected $db = 'hackathon';
    protected $user = 'root';
    protected $pass = '';
    protected $dsn = '' ;
    
    /*
    protected $host = '10.130.33.206'; 
    protected $db = 'PayziProduction';
    protected $user = 'admin';
    protected $pass = 'Payzi2016!';
    protected $sam = '$host';
    protected $dsn = '';
    protected $logger = null;
    */
    protected $errorCode = '';
    
    
    public function __construct($_logger){
        $this->dsn=$this->dbms.":host=".$this->host.";dbname=".$this->db;
        $this->logger = $_logger;
    }
   
    //TODO: Make generic function to send proc name and data
    function add_merchant($merchant){
        try
        {
        $cn=new PDO($this->dsn, $this->user, $this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call add_merchant(:companyName,:contactName,:email,:phone,:bankAccount,:bankName,:rfc,@merchantID,@branchID)");
            $proc->bindParam("companyName", $merchant->companyName, PDO::PARAM_STR);
            $proc->bindParam("contactName", $merchant->contactName, PDO::PARAM_STR);
            $proc->bindParam("email", $merchant->email, PDO::PARAM_STR);
            $proc->bindParam("phone", $merchant->phone, PDO::PARAM_STR);
            $proc->bindParam("bankAccount", $merchant->bankAccount, PDO::PARAM_STR);
            $proc->bindParam("bankName", $merchant->bankName, PDO::PARAM_STR);
            $proc->bindParam("rfc", $merchant->rfc, PDO::PARAM_STR);
            
            $proc->execute();
            $proc->closeCursor();
            $query = $cn->query("SELECT @merchantID AS MerchantID, @branchID as BranchID"); 
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $merchant->merchantID = $result['MerchantID'];
            $branchID = $result['BranchID'];
            //Create the branch on the merchant object
            $branch = new Branch(["merchantID"=>$merchant->merchantID,"branchID"=>$branchID,"name"=>$merchant->companyName]);
            $merchant->branchList = array($branch);
            $returnvalue = $merchant->merchantID;
            if($returnvalue == "")
                return $returnvalue;
            else 
                return "success";
        }
        catch (PDOException $e)
        {
             $this->logger->error("Error con SQL",array("Funcion"=>"add_merchant", "companyName"=>$merchant->companyName, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
    
    }
    
    function add_merchant_user($user){
        
       $cn;
        try{
             $cn=new PDO($this->dsn, $this->user, $this->pass);
             
            $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $proc = $cn->prepare("call add_merchant_user(:userName,:password,:branchID,:admin)");
            $proc->bindParam("userName", $user->userName, PDO::PARAM_STR);
            $proc->bindParam("password", $user->password, PDO::PARAM_STR);
            $proc->bindParam("branchID", $user->branchID, PDO::PARAM_INT);
            $proc->bindParam("admin", $user->admin, PDO::PARAM_INT);
            
            $returnValue = $proc->execute();
            $proc->closeCursor();
            
            if($returnValue==1){
                return "success";
            }
            else{
                return "error";
            }
        }
        catch(PDOException  $e){
            if ($e->errorInfo[1] == ErrorList::SQL_ER_DUP_ENTRY){
                return ErrorList::getUserMessage(ErrorList::APP_DUPLICATE_USER);
            }
            else{
                $this->logger->error("Error con SQL",array("Funcion"=>"add_merchant_user", "UserName"=>$user->userName, "Causa"=>$e->getMessage()));
                return $e->getMessage();
            }
             
            }
           
        }
    
    
    function get_user($userName){
        try{
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $proc = $cn->prepare("call get_merchant_user(:userName)");
                  
            $proc->bindParam("userName", $userName, PDO::PARAM_STR);
            $proc->execute();
            
            $user = new User();
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $user->userID = $data['userID']; 
                $user->userName = $data['user_name']; 
                $user->password = $data['password']; 
                $user->branchID = $data['branchID']; 
                $user->admin = $data['type']; 
                $user->merchantID = $data['merchantID'];
            }
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"get_user", "UserName"=>$userName, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
        
        return $user;
    }
    
    function get_wristband($wristbandID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_wristband(:wristbandID)");

            $proc->bindParam("wristbandID", $wristbandID, PDO::PARAM_STR);
            $proc->execute();
            
            $wristband = new Wristband();
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $wristband->wristbandID = $data['wristbandID']; 
                $wristband->hardwareID = $data['hardwareID']; 
                $wristband->privateKey = $data['private_key']; 
                $wristband->publicKey = $data['public_key']; 
                $wristband->pinCode = $data['pin_code']; 
                $wristband->balance = $data['balance'];
                $wristband->giftBalance = $data['gift'];
            }
        }
        catch(PDOException $e){
             $this->logger->error("Error con SQL",array("Funcion"=>"get_wristband", "WristbandID"=>$wristbandID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
        
        return $wristband;
    }
    
    function add_order($order, $paymentBalance){
    
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call add_order(:wristbandID,:amount,:userID,:paymentBalance,@orderID,@balance,@giftBalance)");
            

            $proc->bindParam("wristbandID", $order->wristband->wristbandID, PDO::PARAM_STR);
            $proc->bindParam("amount", $order->amount, PDO::PARAM_STR);
            $proc->bindParam("userID", $order->userID, PDO::PARAM_STR);
            $proc->bindParam("paymentBalance", $paymentBalance, PDO::PARAM_STR);
            
            $proc->execute();
            $proc->closeCursor();
            $query = $cn->query("SELECT @orderID AS OrderID, @balance as balance, @giftBalance as GiftBalance"); 
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $order->orderID = $result['OrderID'];
            $order->wristband->balance = $result['balance'];
            $order->wristband->giftBalance = $result['GiftBalance'];
            
            $returnvalue = $order->orderID;
            if($returnvalue == "")
                return $returnvalue;
            else {
                $orderID = $order->orderID;
                return "success";
            }
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"add_order", "WristbandID"=>$order->wristband->wristbandID, "User"=>$order->userID, "Amount"=>$order->amount, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }    
            
    }
    
    function add_order_detail($orderDetail, $orderID){
    try
    {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call add_order_detail(:order_id,:product_id,:qty,:totalA,@orderDetailID)");
            
  
            $proc->bindParam("order_id", $orderID, PDO::PARAM_STR);
            $proc->bindParam("product_id", $orderDetail->productID, PDO::PARAM_STR);
            $proc->bindParam("qty", $orderDetail->quantity, PDO::PARAM_STR);
            $proc->bindParam("totalA", $orderDetail->total, PDO::PARAM_STR);
            
            $proc->execute();
            $proc->closeCursor();
            $query = $cn->query("SELECT @orderDetailID AS OrderDetailID"); 
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $orderDetail->orderDetailID = $result['OrderDetailID'];
            
            $returnvalue = $orderDetail->orderDetailID;
            if($returnvalue == "")
                return $returnvalue;
            else {
                return "success";
            }
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"add_order_detail", "OrderID"=>$orderID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }    
            
    }
    
    function recharge_wristband($wristband,$amount){
    try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call recharge_wristband(:wristbandID,:amount,@balance)");

            $proc->bindParam("wristbandID", $wristband->wristbandID, PDO::PARAM_STR);
            $proc->bindParam("amount", $amount, PDO::PARAM_STR);
            
            $proc->execute();
            $proc->closeCursor();
            $query = $cn->query("SELECT @balance as balance"); 
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $wristband->balance = $result['balance'];
            $returnvalue = $wristband->wristbandID;
            if($returnvalue == "")
                return $returnvalue;
            else 
                return "success";
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"recharge_wristband", "WristbandID"=>$wristband, "Amount"=>$amount, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }    
            
    }
    
    function get_orders_for_user($userID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_orders_for_user(:usID)");
        
        $proc->bindParam("usID", $userID, PDO::PARAM_STR);
           
            $proc->execute();
            $orders = array();
            $i = 0;
            while ($row = $proc->fetch()) {
                $order = new Order();
                $order->userID = $userID;
                $order->amount = $row['amount'];
                $order->wristband = $row['wristbandID'];
                $order->date = $row['date'];
                $orders[$i] = $order;
                $i = $i+1;
            }
            
            return $orders;
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"get_orders_for_user", "UserID"=>$userID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }  
    }
    
    function get_total_sales_for_user($userID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_total_sales_for_user(:usID)");
        
        $proc->bindParam("usID", $userID, PDO::PARAM_STR);
           
            $proc->execute();
            $total = 0;
            $i = 0;
            while ($row = $proc->fetch()) {
               
                $total = $row['Total'];
            }
            
            return $total;
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"get_total_sales_for_user", "UserID"=>$userID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }  
    }
    
    function get_products_by_merchant($merchantID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_products_by_merchant(:merchant_id)");
            
            $proc->bindParam("merchant_id", $merchantID, PDO::PARAM_STR);
           
            $proc->execute();
            $prouducts = array();
            $i = 0;
            while ($row = $proc->fetch()) {
                $product = new Product();
                $product->productID = $row['productID'];
                $product->description = $row['description'];
                $product->unitPrice = $row['unit_price'];
                $product->categoryID = $row['categoryID'];
                $product->image = $row['image'];
                $product->available = $row['available'];
                $products[$i] = $product;
                $i = $i+1;
            }
            
            return $products;
        }
        catch (PDOException $e)
        {
            $this->logger->error("Error con SQL",array("Funcion"=>"get_products_by_merchant", "MerchantID"=>$merchantID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }  
    }
    
    function get_wristband_user($username){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_wristband_user(:userName)");

            $proc->bindParam("userName", $username, PDO::PARAM_STR);
            $proc->execute();
            
            $user = new WristbandUser();
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $user->userID = $data['userID']; 
                $user->userName = $data['email']; 
                $user->password = $data['password'];
                $user->wristbandID = $data['wristbandID']; 
                $user->lastName = $data['last_name']; 
                $user->name = $data['name'];
                $user->gift = $data['gift'];
                $user->balance = $data['balance'];
            }
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"get_wristband_user", "Username"=>$username, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
        
        return $user;
    }
    
    function add_wristband_user($user){
        
        try
        {
        $cn=new PDO($this->dsn, $this->user, $this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call add_wristband_user(:wristID,:name,:lastName,:email,:pwd)");
   
            $proc->bindParam("wristID", $user->wristbandID, PDO::PARAM_STR);
            $proc->bindParam("name", $user->name, PDO::PARAM_STR);
            $proc->bindParam("lastName", $user->lastName, PDO::PARAM_STR);
            $proc->bindParam("email", $user->userName, PDO::PARAM_STR);
            $proc->bindParam("pwd", $user->password, PDO::PARAM_STR);
            
            $proc->execute();
            $retVal = "";
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $retVal = $data["response"];
            }
            $proc->closeCursor();
            
            return $retVal;
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"add_wristband_user", "WristbandID"=>$user->wristbandID, "Email"=>$user->userName, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
    }
    
    function use_gift_balance($userID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call use_gift_balance(:userID)");
     
            $proc->bindParam("userID", $userID, PDO::PARAM_STR);
            $proc->execute();
            
            $response = "";
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $response = $data['useGiftBalance']; 
                
            }
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"use_gift_balance", "UserID"=>$userID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
        
        return $response;
    }
    
    function get_total_sales_for_merchant($merchantID){
        try
        {
        $cn = new PDO($this->dsn,$this->user,$this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_total_sales_for_merchant(:mercID)");
            
            $proc->bindParam("mercID", $merchantID, PDO::PARAM_STR);
            $proc->execute();
            
            $total = 0;
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $total = $data['Total'];
            }
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"get_total_sales_by_merchant", "MerchantID"=>$merchantID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
        
        return $total;
    } 
    
    function add_attendence($wristbandID, $userID){
        
        try
        {
        $cn=new PDO($this->dsn, $this->user, $this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call add_attendence(:wristID,:usID)");
   
            $proc->bindParam("wristID", $wristbandID, PDO::PARAM_STR);
            $proc->bindParam("usID", $userID, PDO::PARAM_STR);
            
            $retVal = $proc->execute();
            
            return $retVal;
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"add_attendence", "WristbandID"=>$wristbandID, "UserID"=>$userID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
    }
    
    function get_attendence_list_by_user($userID, $date, $group){
        
        try
        {
        $cn=new PDO($this->dsn, $this->user, $this->pass);
        $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $proc = $cn->prepare("call get_attendence_by_user(:usID,:date, :group)");
          
            $proc->bindParam("usID", $userID, PDO::PARAM_STR);
            $proc->bindParam("date", $date, PDO::PARAM_STR);
            $proc->bindParam("group", $group, PDO::PARAM_STR);
           
            $retVal = $proc->execute();
            
            $obj = array();
            while($data =  $proc->fetch( PDO::FETCH_ASSOC )){ 
                $temp = array("name"=>$data["name"],
                              "checkin"=>$data["checkin"],
                              "checkout"=>$data["checkout"],
                              "perfil"=>$data["perfil"],
                              "facultad"=>$data["facultad"],
                              "etapa"=>$data["etapa"],
                              "proyecto"=>$data["proyecto"]
                             );
                $obj[] = $temp;
            }
            return $obj;
        }
        catch(PDOException $e){
            $this->logger->error("Error con SQL",array("Funcion"=>"get_attendence_list_by_user", "UserID"=>$userID, "Causa"=>$e->getMessage()));
            return $e->getMessage();
        }
    }
}