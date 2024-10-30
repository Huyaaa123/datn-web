<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy giỏ hàng từ session
        $user = Auth::user();
        $cart = session()->get('cart');
        $categories = Category::all(); // Lấy tất cả danh mục

        // Kiểm tra xem người dùng có địa chỉ nào không
        $address = $user->addresses()->first(); // Thay đổi từ $user->address sang $user->addresses()->first()

        // Nếu giỏ hàng rỗng, chuyển hướng về giỏ hàng
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống!');
        }

        return view('cart.checkout', compact('cart', 'categories', 'address', 'user'));
    }

    public function online_checkout(Request $request)
    {
        // Kiểm tra nếu phương thức thanh toán là COD
        if ($request->has('cod')) {
            if ($request->has('cod')) {
                $cart = session()->get('cart', []);

                // Tính tổng tiền đơn hàng
                $amount = array_sum(array_map(function ($product) {
                    return $product['quantity'] * $product['price'];
                }, $cart));

                // Lưu thông tin đơn hàng vào cơ sở dữ liệu
                $order = new Order();
                $order->user_id = auth()->check() ? auth()->id() : null; // Lưu ID người dùng nếu đã đăng nhập
                $order->status = 'Đang xử lý';
                $order->total_amount = $amount;
                $order->order_date = now();
                $order->payment_method = 'COD';
                $order->telephone = $request->phone;
                $order->shipping_address = $request->city . ', ' . $request->district . ', ' . $request->ward . ', ' . $request->address;
                $order->notes = 'Chưa thanh toán'; // Ghi chú từ request
                $order->save();

                // Lưu các sản phẩm trong giỏ hàng vào bảng chi tiết đơn hàng
                foreach ($cart as $productId => $product) {
                    $order->orderDetails()->create([
                        'product_id' => $productId,
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'total' => $product['quantity'] * $product['price'] // Calculate total if missing
                    ]);
                }


                // Xóa giỏ hàng khỏi session
                session()->forget('cart');
                // Xóa giỏ hàng khỏi cơ sở dữ liệu
                $user = Auth::user();
                $cartItems = $user->carts; // Lấy tất cả bản ghi giỏ hàng của người dùng
                foreach ($cartItems as $cartItem) {
                    $cartItem->delete(); // Xóa từng bản ghi
                }
                // Chuyển hướng đến trang cảm ơn
                return redirect()->route('order.success')->with('success', 'Đơn hàng đã được tạo thành công!');
            }

            // Xử lý thanh toán MoMo và VNPay (phần này giữ nguyên như ban đầu)


            // Nếu không có phương thức thanh toán hợp lệ
            return redirect()->back()->with('error', 'Phương thức thanh toán không hợp lệ.');
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
            $redirectUrl = "http://datn-web.test/thanks"; // URL để nhận thông tin trả về từ MoMo
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

            // Lưu đơn hàng vào cơ sở dữ liệu
            $order = new Order();
            $order->user_id = auth()->id(); // Nếu người dùng đã đăng nhập
            $order->status = 'Đang xử lý'; // Hoặc trạng thái khác
            $order->total_amount = $amount;
            $order->order_date = now();
            $order->shipping_address = implode(', ', [
                $request->input('address'),
                $request->input('ward'),
                $request->input('district'),
                $request->input('city')
            ]);
            $order->telephone = $request->phone;
            $order->payment_method = 'MoMo';
            $order->notes = 'Đã thanh toán'; // Ghi chú từ request
            $order->save();

            // Lưu các chi tiết đơn hàng
            foreach ($cart as $productId => $product) {
                $order->orderDetails()->create([
                    'product_id' => $productId,
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['quantity'] * $product['price']
                ]);
            }

            session()->forget('cart');
            $user = Auth::user();
            $cartItems = $user->carts; // Lấy tất cả bản ghi giỏ hàng của người dùng
            foreach ($cartItems as $cartItem) {
                $cartItem->delete(); // Xóa từng bản ghi
            }
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

            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
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
        $user = Auth::user();
        $cartItems = $user->carts; // Lấy tất cả bản ghi giỏ hàng của người dùng
        foreach ($cartItems as $cartItem) {
            $cartItem->delete(); // Xóa từng bản ghi
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
