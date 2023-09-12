<?php

namespace App\Http\Controllers;

use App\Http\Services\CartService;
use App\Http\Services\Menu\MenuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use DateTime;
use Exception;

class CartController extends Controller
{
    protected $cartService;
    protected $menu;

    public function __construct(CartService $cartService, MenuService $menu)
    {
        $this->cartService = $cartService;
        $this->menu = $menu;

    }

    public function index(Request $request)
    {
        $result = $this->cartService->create($request);
        if($result == false){
            return redirect()->back();
        }

        return redirect('/carts');
    }

    public function show()
    {
        $products = $this->cartService->getProduct();

        return view('carts.list', [
            'title' => 'Giỏ hàng',
            'products' => $products,
            'menus' => $this->menu->show(),
            'carts' => Session::get('carts')
        ]);
    }

    public function update(Request $request)
    {
        $this->cartService->update($request);

        return redirect('/carts');
    }

    public function remove($id = 0)
    {
        $this->cartService->remove($id);

        return redirect('/carts');
    }

    public function addCart(Request $request)
    {
        $result = $this->cartService->addCart($request);

        return redirect()->back();
    }

    public function map()
    {
        // // $response = \GoogleMaps::load('geocoding')
		// ->setParam (['address' =>'santa cruz'])
 		// ->get();

        //  die($response);
    }

    public function vnpay_payment(){
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "https://web_lavarel.test/donepayment";
        $vnp_TmnCode = "XCGAYSB8";//Mã website tại VNPAY 
        $vnp_HashSecret = "VRTQFJVDDZKRPJPNGKOEFLRDUYGQCWOG"; //Chuỗi bí mật

        $date = new DateTime();

        $vnp_TxnRef = date_format($date,"YmdHisu"); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $_POST['total'] * 100;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $vnp_Bill_Name = $_POST['name'];
        $vnp_Bill_Mobile = $_POST['phone'];
        $vnp_Bill_Address=$_POST['address'];
        $vnp_Bill_Email = $_POST['email'];
        $vnp_Bill_Content = $_POST['content'];
        //Add Params of 2.0.1 Version

        $json = '{"vnp_Bill_Name":"'.$vnp_Bill_Name.'","vnp_Bill_Mobile":"'.$vnp_Bill_Mobile.'","vnp_Bill_Address":"'.$vnp_Bill_Address.'","vnp_Bill_Email":"'.$vnp_Bill_Email.'","vnp_Bill_Content":"'.$vnp_Bill_Content.'"}';

        $vnp_OrderInfo = $json;

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_Bill_Name"=>$vnp_Bill_Name,
            // "vnp_Bill_Mobile"=>$vnp_Bill_Mobile,
            // "vnp_Bill_Address"=>$vnp_Bill_Address,
            // "vnp_Bill_Email"=>$vnp_Bill_Email,
            // "vnp_Bill_Content"=>$vnp_Bill_Content,
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        // die($hashdata);

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, "$vnp_HashSecret");//  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        // die($vnp_Url);
        $returnData = array('code' => '00'
            , 'message' => 'success'
            , 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
    }

    public function done_payment(){
        /* Payment Notify
        * IPN URL: Ghi nhận kết quả thanh toán từ VNPAY
        * Các bước thực hiện:
        * Kiểm tra checksum 
        * Tìm giao dịch trong database
        * Kiểm tra số tiền giữa hai hệ thống
        * Kiểm tra tình trạng của giao dịch trước khi cập nhật
        * Cập nhật kết quả vào Database
        * Trả kết quả ghi nhận lại cho VNPAY
        */

        // require_once("./config.php");
        $vnp_HashSecret = "VRTQFJVDDZKRPJPNGKOEFLRDUYGQCWOG"; //Chuỗi bí mật
        $inputData = array();
        $returnData = array();

        foreach ($_GET as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'];
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }
        // die($hashData);
        $hashData = urldecode($hashData);

        $vnp_OrderInfo = json_decode($inputData['vnp_OrderInfo'], true);
        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnpTranId = $inputData['vnp_TransactionNo']; //Mã giao dịch tại VNPAY
        $vnp_BankCode = $inputData['vnp_BankCode']; //Ngân hàng thanh toán
        $vnp_Amount = $inputData['vnp_Amount']/100; // Số tiền thanh toán VNPAY phản hồi

        $Status = 0; // Là trạng thái thanh toán của giao dịch chưa có IPN lưu tại hệ thống của merchant chiều khởi tạo URL thanh toán.
        $orderId = $inputData['vnp_TxnRef'];

        try {
            //Check Orderid    
            //Kiểm tra checksum của dữ liệu
            // if ($secureHash == $vnp_SecureHash) {
                //Lấy thông tin đơn hàng lưu trong Database và kiểm tra trạng thái của đơn hàng, mã đơn hàng là: $orderId            
                //Việc kiểm tra trạng thái của đơn hàng giúp hệ thống không xử lý trùng lặp, xử lý nhiều lần một giao dịch
                //Giả sử: $order = mysqli_fetch_assoc($result);   

                // $order = NULL;
                // if ($order != NULL) {
                //     if($order["Amount"] == $vnp_Amount) //Kiểm tra số tiền thanh toán của giao dịch: giả sử số tiền kiểm tra là đúng. //$order["Amount"] == $vnp_Amount
                //     {
                //         if ($order["Status"] != NULL && $order["Status"] == 0) {
                //             if ($inputData['vnp_ResponseCode'] == '00' || $inputData['vnp_TransactionStatus'] == '00') {
                //                 $Status = 1; // Trạng thái thanh toán thành công
                //             } else {
                //                 $Status = 2; // Trạng thái thanh toán thất bại / lỗi
                //             }
                            //Cài đặt Code cập nhật kết quả thanh toán, tình trạng đơn hàng vào DB

                            $result = $this->cartService->addCartWithPayment(
                                $vnp_OrderInfo['vnp_Bill_Name'],
                                $vnp_OrderInfo['vnp_Bill_Mobile'],
                                $vnp_OrderInfo['vnp_Bill_Address'],
                                $vnp_OrderInfo['vnp_Bill_Email'],
                                $vnp_OrderInfo['vnp_Bill_Content'],
                            );

                            return redirect('/carts');
                            //Trả kết quả về cho VNPAY: Website/APP TMĐT ghi nhận yêu cầu thành công                
                            // $returnData['RspCode'] = '00';
                            // $returnData['Message'] = 'Confirm Success';
                    //     } else {
                    //         $returnData['RspCode'] = '02';
                    //         $returnData['Message'] = 'Order already confirmed';
                    //     }
                    // }
                //     else {
                //         $returnData['RspCode'] = '04';
                //         $returnData['Message'] = 'invalid amount';
                //     }
                // } else {
                //     $returnData['RspCode'] = '01';
                //     $returnData['Message'] = 'Order not found';
                // }
            // } else {
            //     $returnData['RspCode'] = '97';
            //     $returnData['Message'] = 'Invalid signature';
            // }
        } catch (Exception $e) {
            $returnData['RspCode'] = '99';
            $returnData['Message'] = 'Unknow error';
        }
        //Trả lại VNPAY theo định dạng JSON
        echo json_encode($returnData);
    }

}
