<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng từ session
        $cart = session()->get('cart');
        $categories = Category::all(); // Lấy tất cả danh mục

        // Nếu giỏ hàng rỗng, chuyển hướng về giỏ hàng
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        return view('cart.checkout', compact('cart', 'categories'));
    }
    public function online_checkout(Request $request)
    {
        // Kiểm tra nếu phương thức thanh toán là COD
        if ($request->has('cod')) {
            return response()->json(['message' => 'Thanh toán bằng COD'], 200);
        }

        // Kiểm tra nếu phương thức thanh toán là MoMo
        elseif ($request->has('payUrl')) {
            $cart = session()->get('cart', []);
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

            $orderInfo = "Thanh toán qua MoMo";
            $amount = array_sum(array_map(function ($product) {
                return $product['quantity'] * $product['price'];
            }, $cart));

            // Kiểm tra số tiền
            if ($amount < 10000 || $amount > 50000000) {
                return redirect()->back()->with('error', 'Số tiền giao dịch phải từ 10,000 VNĐ đến 50,000,000 VNĐ.');
            }

            $orderId = time() . "";
            $redirectUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b"; // URL để nhận thông tin trả về từ MoMo
            $ipnUrl = "http://datn-web.test/"; // URL để MoMo gửi thông tin giao dịch
            $extraData = ""; // Dữ liệu bổ sung nếu cần

            $requestId = time() . "";
            $requestType = "payWithATM";

            // Tạo hash signature
            $rawHash = "accessKey={$accessKey}&amount={$amount}&extraData={$extraData}&ipnUrl={$ipnUrl}&orderId={$orderId}&orderInfo={$orderInfo}&partnerCode={$partnerCode}&redirectUrl={$redirectUrl}&requestId={$requestId}&requestType={$requestType}";
            $signature = hash_hmac("sha256", $rawHash, $secretKey);

            // Dữ liệu gửi đến MoMo
            $data = [
                'partnerCode' => $partnerCode,
                'partnerName' => 'Test',
                'storeId' => 'MomoTestStore',
                'requestId' => $requestId,
                'amount' => $amount,
                'orderId' => $orderId,
                'orderInfo' => $orderInfo,
                'redirectUrl' => $redirectUrl,
                'ipnUrl' => $ipnUrl,
                'lang' => 'vi',
                'extraData' => $extraData,
                'requestType' => $requestType,
                'signature' => $signature
            ];

            // Gửi yêu cầu đến MoMo
            $result = $this->execPostRequest($endpoint, json_encode($data));
            $jsonResult = json_decode($result, true); // Giải mã JSON

            // Kiểm tra và xử lý phản hồi từ MoMo
            if (isset($jsonResult['payUrl'])) {
                return redirect()->to($jsonResult['payUrl']); // Chuyển hướng đến URL thanh toán
            } else {
                // Ghi log phản hồi để dễ dàng gỡ lỗi
                \Log::error('MoMo response error', $jsonResult);
                return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý giao dịch: ' . ($jsonResult['message'] ?? 'Lỗi không xác định.'));
            }
        }

        // Kiểm tra nếu phương thức thanh toán là VNPay
        elseif ($request->has('vnpay')) {
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            $vnp_Returnurl = "https://localhost/vnpay_php/vnpay_return.php";
            $vnp_TmnCode = "BA3K87AH";//Mã website tại VNPAY
            $vnp_HashSecret = "RWHBOVNYWN7HTYWQRXALNNAMVKXJWHT9"; //Chuỗi bí mật

            $vnp_TxnRef = time() . "";
            $vnp_OrderInfo = 'nd thanh toan';
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = 10000 * 100;
            $vnp_Locale = 'VN';
            $vnp_BankCode = 'NCB';
            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            //Add Params of 2.0.1 Version
            // $vnp_ExpireDate = $_POST['txtexpire'];
            //Billing
            // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
            // $vnp_Bill_Email = $_POST['txt_billing_email'];
            // $fullName = trim($_POST['txt_billing_fullname']);
            // if (isset($fullName) && trim($fullName) != '') {
            //     $name = explode(' ', $fullName);
            //     $vnp_Bill_FirstName = array_shift($name);
            //     $vnp_Bill_LastName = array_pop($name);
            // }
            // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
            // $vnp_Bill_City = $_POST['txt_bill_city'];
            // $vnp_Bill_Country = $_POST['txt_bill_country'];
            // $vnp_Bill_State = $_POST['txt_bill_state'];
            // // Invoice
            // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
            // $vnp_Inv_Email = $_POST['txt_inv_email'];
            // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
            // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
            // $vnp_Inv_Company = $_POST['txt_inv_company'];
            // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
            // $vnp_Inv_Type = $_POST['cbo_inv_type'];
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
                // "vnp_ExpireDate" => $vnp_ExpireDate,
                // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
                // "vnp_Bill_Email" => $vnp_Bill_Email,
                // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
                // "vnp_Bill_LastName" => $vnp_Bill_LastName,
                // "vnp_Bill_Address" => $vnp_Bill_Address,
                // "vnp_Bill_City" => $vnp_Bill_City,
                // "vnp_Bill_Country" => $vnp_Bill_Country,
                // "vnp_Inv_Phone" => $vnp_Inv_Phone,
                // "vnp_Inv_Email" => $vnp_Inv_Email,
                // "vnp_Inv_Customer" => $vnp_Inv_Customer,
                // "vnp_Inv_Address" => $vnp_Inv_Address,
                // "vnp_Inv_Company" => $vnp_Inv_Company,
                // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
                // "vnp_Inv_Type" => $vnp_Inv_Type
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            //     $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            // }

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

            $vnp_Url = $vnp_Url . "?" . $query;
            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array(
                'code' => '00'
                ,
                'message' => 'success'
                ,
                'data' => $vnp_Url
            );
            if (isset($_POST['vnpay'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
            // vui lòng tham khảo thêm tại code demo

        }

        // Nếu không có phương thức thanh toán hợp lệ
        return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
    }


    function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            )
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }


}
