<?php
@ini_set('display_errors', '0');
	header("Access-Control-Allow-Origin: *");
	
	header("Content-Type: application/json; charset=UTF-8");	
   
	header("Access-Control-Max-Age: 3600");
	
	header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	


			if(@$_SERVER['REQUEST_METHOD'] == 'GET'){
		echo 'RequestMethod POST';
		}
if(@$_SERVER['REQUEST_METHOD'] == 'POST'){

class topup {
    function giftcode($hash = null,$phone = null) {
        if (is_null($hash) || is_null($phone)) return false;
        $ch = curl_init();
    $hash = explode('?v=',$hash)[1];
        $headers  = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        $postData = [
            'mobile' => $phone,
            'voucher_hash' => $hash
        ];
        curl_setopt($ch, CURLOPT_URL,"https://gift.truemoney.com/campaign/vouchers/$hash/redeem");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));           
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt ($ch, CURLOPT_SSLVERSION, 7);
        curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36" );
        $result     = curl_exec ($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return json_decode($result,true);
    }
}

$daybuy = date("Y-m-d");
$timebuy = date("H:i:s");
$phone = $_POST['phone'];	
$gift_link = $_POST['gift_link'];
			$topup_truewallet = new topup();
			$truewallet = (object) $topup_truewallet->giftcode("$gift_link","$phone");

			if(@$_POST['phone'] == '0821203101'){
				echo json_encode(['status' => 'error','message' => 'บัญชี Truewallet / รัฐภูมิ ***  082-xxx-3101 ตรวจพบการฉ้อโกง']);
				// $_POST['phone']
			}
			elseif(@$_POST['phone'] == ''){
				echo json_encode(['status' => 'error','message' => 'กรุณาใส่เบอร์รับเงิน TrueMoney Wallet']);
				// $_POST['phone']
			}
			elseif(@$_POST['gift_link'] == ''){
				  echo json_encode(['status' => 'error','message' => 'กรุณาใส่ลิงค์ซองของขวัญ']);
				// $_POST['gift_link']
			}
			elseif(@$_POST['gift_link'] == 'https://gift.truemoney.com/campaign/?v=497a55dd66ff492e9d4863a612af9007dcQ'){
				  echo json_encode(['status' => 'error','message' => 'บัญชีสร้างซองนี้โดนแบน']);
				// $_POST['gift_link']
			}
			elseif(@$truewallet->status['code'] == ''){
				  echo json_encode(['status' => 'error','message' => 'ไม่พบซองอั๋งเปานี้']);
				// API Error
			}elseif(@$truewallet->status['code'] == 'VOUCHER_OUT_OF_STOCK'){
				  echo json_encode(['status' => 'error','message' => 'ซองเติมเงินนี้ถูกใช้งานไปแล้ว']);
				// ซองเติมเงินนี้ถูกใช้งานไปแล้ว
			}elseif(@$truewallet->status['code'] == 'VOUCHER_EXPIRED'){
				echo json_encode(['status' => 'error','message' => 'ซองเติมเงินนี้หมดอายุ']);
				// ซองเติมเงินนี้หมดอายุ
			}
			elseif(@$truewallet->status['code'] == 'VOUCHER_NOT_FOUND'){
				echo json_encode(['status' => 'error','message' => 'ไมพบซอง']);
				// ไมพบซอง
				}
			elseif(@$truewallet->status['code'] == 'CANNOT_GET_OWN_VOUCHER'){
				echo json_encode(['status' => 'error','message' => 'รับซองตัวเองไม่ได้']);
				// รับซองตัวเองไม่ได้
				}
			elseif(@$truewallet->status['code'] == 'TARGET_USER_NOT_FOUND'){
				echo json_encode(['status' => 'error','message' => 'ไม่พบเบอร์นี้ในระบบ']);
				// ไม่พบเบอร์นี้ในระบบ
				}
			elseif(@$truewallet->status['code'] == 'INTERNAL_ERROR'){
				echo json_encode(['status' => 'error','message' => 'ไม่มีซองนี้ในระบบ หรือ URL ผิด']);
				// ไม่มีซองนี้ในระบบ หรือ URL ผิด				
				}
					elseif(@$truewallet->status['code'] == 'USER_NOT_FOUND'){
				echo json_encode(['status' => 'error','message' => 'บัญชีสร้างซองนี้โดนแบน']);
				// บัญชีสร้างซองนี้โดนแบน
				
			}
					elseif(@$truewallet->status['code'] == 'OWNER_USER_STATUS_INACTIVE'){
				echo json_encode(['status' => 'error','message' => 'บัญชีสร้างซองนี้โดนแบน']);
				// บัญชีสร้างซองนี้โดนแบน
				
			}elseif(@$truewallet->status['code'] == null){
				echo json_encode(['status' => 'error','message' => 'ไม่พบซองอั๋งเปานี้']);
				// กรอกมั่ว
			}else
				if(@$truewallet->data['voucher']['member'] != "1"){
					echo json_encode(['status' => 'error','message' => 'ผู้รับซองต้องเป็น 1 คน']);

				}else{
if(@$truewallet->status['code'] == 'SUCCESS'){
		 	   
		


		

//เติมเงินสำเร็จ!
        $amounts = $truewallet->data['voucher']['amount_baht'];
        $links = $truewallet->data['voucher']['link'];
        $amount = str_replace(",", "", trim($amounts));
        $timeload = time();
        $data = [ 'code' => '200', 'amount' => $amount ];
		
		






    echo json_encode(['status' => 'success','message' => 'สำเร็จ','amount' => ''.$amount.'','phone' => ''.$phone.'','gift_link' => ''.$gift_link.'','time' => ''.$daybuy.' '.$timebuy.'']);
				



				 
}
}
}
?>