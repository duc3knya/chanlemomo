<?php
error_reporting(0);
date_default_timezone_set('Asia/Ho_Chi_Minh');
$host = "localhost";
$username = "u312874129_automomo";
$password = 'z2g4IM@Z';
$dbname = "u312874129_automomo";
$conn = mysqli_connect($host,$username,$password,$dbname);
if(!$conn){
   die('Bao Tri 5 phut.').mysqli_connect_error();
}
mysqli_set_charset($conn,"utf8");
function xss($data)
{
	$data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
	$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
	$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
	$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
	$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
	$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
	$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
	$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
	do {
		$old_data = $data;
		$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
	} while ($old_data !== $data);
	$data = addslashes($data);
	return $data;
}
?>
<meta charset="utf-8">
<title>Kiểm Tra Lịch Sử Giao Dịch</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="https://vipclmm.com/image/favicon.png">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
<style>
    body {
  min-height: 75rem;
  padding-top: 4.5rem;
}
</style>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $magiaodich = mysqli_real_escape_string($conn, xss( $_POST['magiaodich']));
  if (empty($magiaodich)||(strlen($magiaodich)>15)||(preg_match("/\s/", $magiaodich))) {
    echo'<script type="text/javascript">alert("Ôi bạn ơi, Mã Giao dịch không đúng!");</script>';
    echo'<meta http-equiv="refresh" content="1;url=./">'; // không có mã gd tự về trang chủ
  } else { // đã nhập vào mã gd,đợi chút nha.
      $query = mysqli_query($conn, "SELECT * FROM `lich_su_choi_momos` WHERE `magiaodich` = '$magiaodich'");
      if(mysqli_num_rows($query) !== 0){
      ?>
       <body>
    <br/>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="./">autocltxmomo.CLUB</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="./">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="https://zalo.me/g/qxohvz110">Hỗ Trợ</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">
      <div class="jumbotron">
          <?php
                //sdt|magiaodich|tiencuoc|trochoi|noidung|ketqua(t/f)|status|created_at|updated_at(trả thưởng lúc)
   
 while ($row = mysqli_fetch_assoc($query)) {
    $sdt_mem = $row['sdt']; 
    $xoasdt = substr($sdt_mem, 0,-4);
    $del_sdt = $xoasdt."****";
    $magiaodich_mem = $row['magiaodich']; 
    $tiencuoc_mem = $row['tiencuoc']; 
    $tiennhan_mem = $row['tiennhan']; 
    $trochoi_mem = $row['trochoi']; 
    $noidung_mem = $row['noidung']; 
    $ketqua_mem = $row['ketqua']; 
    $status_mem = $row['status']; 
    $created_at_mem = date("H:i:s - d/m/Y", strtotime($row['created_at'] )); 
    $updated_at_mem = date("H:i:s - d/m/Y", strtotime($row['updated_at'] )); 
    
?>
        <h2>Thông tin giao dịch:</h2>
        <!--<p class="lead">Nội dung xaoloz</p>-->
        <p></p>
        <p>SĐT: <?=$del_sdt ?></p>
        <p>Mã GD: <b style="color:brown"><?=$magiaodich_mem ?></b></p>
        <p>Số tiền: <b style="color: #ff0066;"><?= number_format($tiencuoc_mem) ?>VNĐ</b></p>
        <p>Nội dung: <b style="color:blue;"><?= $noidung_mem ?></b></p>
        <hr/>
        <p>👉 Thông tin trò chơi 👈</p>
        <p>Loại trò chơi: <b style="color: #ff0066;"><?=$trochoi_mem;?></b></p>
        <p>Kết quả:  <b style="color: #00cc66;"><?php if($ketqua_mem=="1"){echo "Thắng 😍";}else{echo "Thua 😥";}?></b></p
        ><p>Tiền nhận: <b style="color: #ff0066;"><?= number_format($tiennhan_mem) ?>VNĐ</b></p>
        <p>Trạng thái: <b style="color: #0000ff"><?php if($status_mem=="3"){echo "Đã Thanh Toán";}else{echo "Tài Khoản không đủ tiền! Đợi admin nạp tiền. ĐỚP ÍT THÔI! đừng lo Tiền sẽ về . Yêu Bạn!";}?></b></p>
        <p>Thời gian nhận: <?= $created_at_mem ?></p>
        <p>Đã xử lý lúc: <?= $updated_at_mem ?></p>
        <br/><hr/>
        <a class="btn btn-lg btn-primary" href="./" role="button">Trang Chủ</a> | <a class="btn btn-lg btn-success" href="https://zalo.me/g/qxohvz110" role="button">Hỗ Trợ</a>
      </div>
    </main>
  </body>
  <?php
}
mysql_close($conn);
      }else{
          echo'  <script type="text/javascript">alert("Không tồn tại giao dịch này");</script>';
                  echo'<meta http-equiv="refresh" content="1;url=./">';

         exit();
      }
    
  }
}else{
?>
<body>
 <form  method="POST" action="#">
  <div class="form-group">
    <label for="exampleInputEmail1">Nhập vào Lịch Sử giao dịch</label>
    <input type="text" name="magiaodich" class="form-control" placeholder="">
    <small id="emailHelp" class="form-text text-muted">Cuộc sống này có làm ....</small>
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
</body>
mysql_close($conn);
<?php }?>