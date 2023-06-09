<?php
error_reporting(0);

session_start();

$total=0;


$conn = new PDO("mysql:host=localhost;dbname=db6424", 't63301280024', '26122543');		
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$action = isset($_GET['action'])?$_GET['action']:"";



if($action=='addcart' && $_SERVER['REQUEST_METHOD']=='POST') {
	
	$query = "SELECT * FROM products WHERE sku=:sku";	
	$stmt = $conn->prepare($query);
	$stmt->bindParam('sku', $_POST['sku']);
	$stmt->execute();
	$product = $stmt->fetch();
	$qt = $_POST['qtya'];

	$currentQty = $_SESSION['products'][$_POST['sku']]['qty'] + $qt; //Incrementing the product qty in cart
	$_SESSION['products'][$_POST['sku']] =array('qty'=>$currentQty ,'name'=>$product['name'],'image'=>$product['image'],'price'=>$product['price']);
	$product='';
	header("Location:cart.php");
}


if($action=='emptyall') {
	$_SESSION['products'] =array();
	header("Location:cart.php");	
}


if($action=='empty') {
	$sku = $_GET['sku'];
	$products = $_SESSION['products'];
	unset($products[$sku]);
	$_SESSION['products']= $products;
	
}



 
 

$query = "SELECT * FROM products";
$stmt = $conn->prepare($query);
$stmt->execute();
$products = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ตะกร้าสินค้า</title>


</head>
<style>
@font-face {
    font-family: 'PS LCD 3310';
    src: url('PSLCD3310.eot');
    src: url('PSLCD3310.eot?#iefix') format('embedded-opentype'),
        url('PSLCD3310.woff2') format('woff2'),
        url('PSLCD3310.woff') format('woff'),
        url('PSLCD3310.ttf') format('truetype'),
        url('PSLCD3310.svg#PSLCD3310') format('svg');
    font-weight: normal;
    font-style: normal;
    font-display: swap;
}
* {
    font-family: 'PS LCD 3310';
    font-weight: normal;
    font-style: normal;
	text-align: center;
}

.container{
	margin-left: auto;
	margin-right: auto;
	text-align: center;
	align: center;
	
}
.btn-simple{
	text-decoration:none;
	background-color: #FAEBD7;
	color: #333333;
	padding: 2px 6px 2px 6px;
	border-top: 1px solid #CCCCCC;
	border-right: 1px solid #333333;
	border-bottom: 1px solid #333333;
	border-left: 1px solid #CCCCCC;
	border-radius: 5px;
}
.btn-simple:hover{
	background-color:#5F9EA0;
	
}
div.scrollmenu {
  background-color: #062C30;
  overflow: auto;
  white-space: nowrap;
  text-align: center;
}

div.scrollmenu a {
  display: inline-block;
  color: #E2D784;
  text-align: center;
  padding: 14px;
  text-decoration: none;
  text-align: center;
}

div.scrollmenu a:hover {
  background-color: #F5F5F5;
  text-decoration: underline;
  color: #05595B;
  text-align: center;
}




</style>
<body>
<div class="scrollmenu">
  <a class ="one" href="cartmanage.php"><img src="images/cart.png" alt="" id="logo" style="width:42px;height:42px;"><br>จัดการระบบตะกร้าสินค้า</a>
  <a class ="one"href="membermanage.php"><img src="images/users.png" alt="" id="logo" style="width:42px;height:42px;"><br>จัดการระบบสมาชิก</a>
  <a class ="one"href="webboard.php"><img src="images/webboard.png" alt="" id="logo" style="width:80px;height:42px;"><br>webboard</a>
  <a class ="one"href="guestbook.php"><img src="images/book.png" alt="" id="logo" style="width:50px;height:42px;"><br>guestbook</a>
  <a class ="one"href="cart.php"><img src="images/shop.png" alt="" id="logo" style="width:50px;height:42px;"><br>shop</a>
  <a class ="one"href="index.php"><img src="images/exit.png" alt="" id="logo" style="width:50px;height:42px;"><br>LOGOUT</a>
</div>
<br>
<div class="container" style="width:100%">
  <?php if(!empty($_SESSION['products'])):?>
  <nav style="background:#F08080;">
    <div style="width:100%;">
      <div> <p style="color:#FFFFFF;">ตะกร้า</p> </div>
    </div>
    
  </nav>
  <table class="container" border=5px>
    <thead>
      <tr>
        <th>รูปภาพ</th>
        <th>ชื่อ</th>
        <th>ราคา</th>
        <th>จำนวน</th>
		<th>ลบ</th>
		<th>ล้าง</th>
      </tr>
    </thead>
	
    <?php $total = 0;
	foreach($_SESSION['products'] as $key=>$product):?>
    <tr>
      <td><img src="<?php print $product['image']?>" width="50"></td>
      <td><?php print $product['name']?></td>
	  
	
      <td>&nbsp;<?php print $product['price']*$product['qty']?>&nbsp;บาท</td>          
      <td>&nbsp;<?php print $product['qty']?></td>        
      <td><a class="btn-simple" href="cart.php?action=empty&sku=<?php print $key?>">ลบ</a></td>
	  <td><a class="btn-simple" href="cart.php?action=emptyall&sku=<?php print $key?>">ล้าง</a></td>
    </tr>	
	
	
    <?php $totalall = $totalall + $product['price']*$product['qty']?> 
    <?php endforeach;?>
	<form method="post" action="checkbill.php">
    <tr><td colspan="6" align="right"><h4>รวม:<?php print $totalall?>บาท</h4>
	</td></tr>
	<tr><td colspan="6"><button type="submit" class="btn-simple" >กรอกที่อยู่</button></td></tr>
	</form>
	
  </table>
  <?php endif;?>
  <nav  style="background:#F08080;">
    <div>
      <div > <p style="color:#FFFFFF;">สินค้า</p> </div>
    </div>
  </nav>
  <div>
    <div class="container" style="width:600px;">
      <?php foreach($products as $product):?>
      <div >
        <div> <img src="<?php print $product['image']?>" alt="Lights">
          <div>
			
            <p style="text-align:center;"><?php print $product['name']?></p>
            <p style="text-align:center;color: black;"><b><?php print $product['price']?>บาท</b></p>
            <form method="post" action="cart.php?action=addcart">
              <p style="text-align:center;color:#04B745;">
				<input type ="number" min="1" name = "qtya"required></input>
                <button type="submit">เพิ่มลงตะกร้า</button>
                <input type="hidden" name="sku" value="<?php print $product['sku']?>">
              </p>
            </form>
          </div>
        </div>
      </div>
      <?php endforeach;?>
    </div>
  </div>
</div>
</body>
</html>