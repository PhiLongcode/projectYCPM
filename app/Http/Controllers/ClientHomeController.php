<?php

namespace App\Http\Controllers;

use App\Mail\Order as MailOrder;
use App\Models\CategoryProduct;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ClientHomeController extends Controller
{

    public function home($id = '')
    {
        // Lọc sản phẩm theo danh mục================================================================================
        $categories = CategoryProduct::where('parent', 0)->paginate(10);
        $products = collect();
        // Lấy thông tin của category dựa trên id
        $categoryById = CategoryProduct::find($id);
        // Kiểm tra xem category có tồn tại và có parent là 0 không
        if ($id !== '') {
            if ($categoryById && $categoryById->parent == 0) {
                // Lấy tất cả các category con của category có id là $id
                $category = CategoryProduct::where('parent', $id)->paginate(10);
                $categories = CategoryProduct::where('id', $id)->paginate(10);
                foreach ($category as $value) {
                    $products = $products->merge(Product::where('category_id', $value->id)->get());
                }
            } else {
                // Nếu category không tồn tại hoặc có parent khác 0, thêm sản phẩm của category có id là $id vào collection
                $categories = CategoryProduct::where('id', $id)->paginate(10);
                // $products = $products->merge(Product::where('category_id', $id)->get());
                $products = Product::where('category_id', $id)->paginate(10);
            }
        }
        $products = Product::paginate(10);

        return view('client.home', compact('products', 'categories'));
    }


    public function list_product(Request $request, $id = '')
    {
        $categories = CategoryProduct::where('parent', 0)->paginate(10);
        $products = Product::query(); // Khởi tạo một truy vấn Eloquent
        $select = $request->input('select');

        // Kiểm tra xem có tham số $id được truyền vào không
        if ($id !== '') {
            // Lấy thông tin của category dựa trên id
            $categoryById = CategoryProduct::find($id);

            if ($categoryById && $categoryById->parent == 0) {
                // Lấy tất cả các category con của category có id là $id
                $category = CategoryProduct::where('parent', $id)->get();
                $categories = CategoryProduct::where('id', $id)->paginate(10); // lấy ra tên danh mục
                foreach ($category as $value) {
                    $products->orWhere('category_id', $value->id);
                }
            } else {
                $categories = CategoryProduct::where('id', $id)->paginate(10);
                $products->where('category_id', $id);
            }
        }

        $keyword = $request->input('search');
        if (isset($keyword)) {
            $products->where('name', 'LIKE', "%$keyword%");
        }

        // Thực hiện sắp xếp dựa trên lựa chọn
        switch ($select) {
            case 1:
                // Sắp xếp từ A-Z
                $products->orderBy('name');
            case 2:
                // Sắp xếp từ Z-A
                $products->orderByDesc('name');
            case 3:
                // Sắp xếp giá cao xuống thấp
                $products->orderByDesc('price');
            case 4:
                // Sắp xếp giá thấp lên cao
                $products->orderBy('price');
            default:
                // Mặc định không sắp xếp
                break;
        }

        // Thực hiện phân trang
        $products = $products->paginate(10);

        return view('client.list_product', compact('products', 'categories', 'keyword', 'id'));
    }

    public function cart()
    {
        return view('client.cart');
    }

    public function add_cart(Request $request, $productId)
    {
        $product = Product::find($productId);
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'qty' => $request->input('num-order'),
            'price' => $product->price - ($product->price / 100) * $product->discount,
            'weight' => 0,
            'options' => [
                'thumbnail' => $product->thumbnail,
            ],
        ]);
        return redirect(route('cart'));
    }

    public function update_cart(Request $request)
    {
        $data = $request->get('qty');
        foreach ($data as $k => $v) {
            Cart::update($k, $v);
        }
        return redirect(route('cart'))->with('status', "Cập nhật giỏ hàng thành công");
    }

    public function remove_cart($rowId)
    {
        Cart::remove($rowId);
        return redirect(route('cart'));
    }

    public function destroy_cart()
    {
        Cart::destroy();
        return redirect(route('cart'));
    }

    public function update_ajax(Request $request)
    {

        $product_rowId = $request->input('product_rowId');
        $qty = $request->input('qty');
        // Thực hiện cập nhật giỏ hàng
        Cart::update($product_rowId, $qty);
        // Trả về dữ liệu cần thiết, ví dụ:

        $priceTotal = Cart::get($product_rowId)->priceTotal(); // Tính lại thành tiền cho sản phẩm cập nhật
        $total = Cart::total(); // Tính lại tổng giá trị của giỏ hàng

        return response()->json([
            'priceTotal' => $priceTotal,
            'total' => $total
        ]);
    }


    public function detail_product($id)
    {
        $product = Product::where('id', $id)->first();
        return view('client.detail_product', compact('product'));
    }

    public function checkout()
    {

        return view('client.checkout');
    }

    public function execPostRequest($url, $data)
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

    public function sendmail(Request $request)
    {
        if ($request->input('order_now')) {
            $request->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'address' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'phone' => ['required'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                    'in' => 'Vui lòng chọn một phương thức thanh toán hợp lệ'
                ],
                [
                    'fullname' => 'Tên người dùng',
                    'email' => 'Email',
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                ]
            );


            $order = Order::create([
                'fullname' => $request->fullname,
                'address' => $request->address,
                'email' => $request->email,
                'note' => $request->input('note') ? $request->input('note') : '',
                'phone' => $request->phone,
                'total' => str_replace('.', '', Cart::total()),
                'payment_method' => $request->input('payment-method') ? $request->input('payment-method') : "direct-payment"
            ]);
            $order_id = $order->getAttribute('id');
            $date_order = $order->getAttribute('created_at');

            foreach (Cart::content() as $product) {
                $product_name = is_array($product->name) ? implode(',', $product->name) : $product->name;
                $qty = is_array($product->qty) ? implode(',', $product->qty) : $product->qty;
                $thumbnail = is_array($product->options->thumbnail) ? implode(',', $product->options->thumbnail) : $product->options->thumbnail;
                $price = is_array($product->price) ? implode(',', $product->price) : $product->price;
                $sub_total = is_array($product->total) ? implode(',', $product->total) : $product->total;

                Order_detail::create([
                    'order_id' => $order_id,
                    'product_name' => $product_name,
                    'qty' => $qty,
                    'thumbnail' => $thumbnail,
                    'price' => $price,
                    'sub_total' => $sub_total,
                ]);
            }

            $productData = [
                'order' => "<table align='center' bgcolor='#dcf0f8' border='0' cellpadding='0' cellspacing='0' style='margin:0;padding:0 15%;background-color:#ffffff;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px' width='100%'>
                                <tbody>
                                    <tr>
                                        <td>
                                            <h1 style='font-size:18px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0'>Cảm ơn quý khách {$request->input('fullname')} đã đặt hàng tại OfficeStore Store</h1>
                                            <p style='margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal'>
                                                OfficeStore rất vui thông báo đơn hàng DH#{$order_id} của quý khách đã được tiếp nhận và đang trong quá trình xử lý. OfficeStore sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.</p>
                                            <h3 style='font-size:14px;font-weight:bold;color:#02acea;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd'>
                                                Thông tin đơn hàng DH#{$order_id} <span style='font-size:13px;color:#777;text-transform:none;font-weight:normal'>($date_order)</span></h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                                <thead>
                                                    <tr>
                                                        <th align='left' style='padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold' width='50%'>Thông tin thanh toán</th>
                                                        <th align='left' style='padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;font-weight:bold' width='50%'> Địa chỉ giao hàng </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style='padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal' valign='top'><span style='text-transform:capitalize'>Họ và tên: {$request->input('fullname')}</span><br>
                                                            <a href='' target='_blank'>Địa chỉ email: {$request->input('email')}</a><br>
                                                            Số điện thoại: {$request->input('phone')}
                                                        </td>
                                                        <td style='padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal' valign='top'><span style='text-transform:capitalize'>{$request->input('fullname')}</span><br>
                                                            <a href='' target='_blank'>Địa chỉ email: {$request->input('email')}</a><br>
                                                            Địa chỉ giao hàng: {$request->input('address')}<br>
                                                            Số điện thoại: {$request->input('phone')}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan='2' style='padding:7px 9px 0px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444' valign='top'>
                                                            <p style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal'>
                                                                <strong>Phương thức thanh toán: </strong> {$request->input('payment-method')}<br>
                                                                <strong>Thời gian giao hàng dự kiến:</strong> Dự kiến giao hàng trong vòng từ 3-5 ngày<br>
                                                                <strong>Phí vận chuyển: </strong> 0đ<br>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h2 style='text-align:left;margin:10px 0;border-bottom:1px solid #ddd;padding-bottom:5px;font-size:14px;color:#02acea'> CHI TIẾT ĐƠN HÀNG</h2>
                                            <table border='0' cellpadding='0' cellspacing='0' style='background:#f5f5f5' width='100%'>
                                                <thead>
                                                    <tr>
                                                        <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                            Sản phẩm</th>
                                                        <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                            Đơn giá</th>
                                                        <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px;text-align:center'>
                                                            Số lượng</th>
                                                        <th align='right' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                            Tổng tạm</th>
                                                    </tr>
                                                </thead>"
            ];
            foreach (Cart::content() as $product) {
                $price = number_format($product->price, 0, '.', ',');
                $sub_total = number_format($product->total, 0, ',', '.');
                $productData['order'] .= "
                                                <tbody bgcolor='#eee' style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                                    <tr>
                                                        <td align='left' style='padding:3px 9px' valign='top'><span>{$product->name}</span><br></td>
                                                        <td align='left' style='padding:3px 9px' valign='top'><span>$price đ</span></td>
                                                        <td align='left' style='padding:3px 9px;text-align:center' valign='top'>{$product->qty}</td>
                                                        <td align='right' style='padding:3px 9px' valign='top'><span>$sub_total đ </span></td>
                                                    </tr>
                                                </tbody>";
            }

            $total = Cart::total();
            $productData['order'] .= "
                                                <tfoot style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                                    <tr>
                                                        <td align='right' colspan='3' style='padding:5px 9px'>Phí vận chuyển</td>
                                                        <td align='right' style='padding:5px 9px'><span>0đ</span></td>
                                                    </tr>
                                                    <tr bgcolor='#eee'>
                                                        <td align='right' colspan='3' style='padding:7px 9px'><strong><big>Tổng giá trị đơn
                                                                    hàng</big> </strong></td>
                                                        <td align='right' style='padding:7px 9px'><strong><big><span>$total đ</span> </big>
                                                        </strong></td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;
                                            <p>Một lần nữa OfficeStore cảm ơn quý khách.</p><font color='#888888'>
                                            <p><strong><a href='' target='_blank' data-saferedirecturl=''>OfficeStore</a>
                                                </strong></p>
                                        </font></td>
                                    </tr>
                                </tbody>
                            </table>";

            $email = $request->input('email');
            Mail::to($email)->send(new MailOrder($productData));
            Cart::destroy();
            return redirect(route('bill'));
        } elseif (isset($_POST['payUrl'])) {
            $request->validate(
                [
                    'fullname' => ['required', 'string', 'max:255'],
                    'address' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255'],
                    'phone' => ['required'],
                ],
                [
                    'required' => ':attribute không được để trống',
                    'min' => ':attribute có độ dài ít nhất :min ký tự',
                    'max' => ':attribute có độ dài tối đa :max ký tự',
                ],
                [
                    'fullname' => 'Tên người dùng',
                    'email' => 'Email',
                    'address' => 'Địa chỉ',
                    'phone' => 'Số điện thoại',
                ]
            );
            $fullname = $request->fullname;
            $address = $request->address;
            $email = $request->email;
            $note = $request->input('note') ? $request->input('note') : " ";
            $phone = $request->phone;
            $total = str_replace('.', '', Cart::total());
            $payment_method = "Thanh toán momo";

            //momo-------------------------------------------------------------
            $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

            $total = str_replace('.', '', Cart::total());
            $partnerCode = 'MOMOBKUN20180529';
            $accessKey = 'klm05TvNBzhg7h7j';
            $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
            $orderInfo = "Thanh toán qua MoMo";
            $amount = $total;
            $orderId = time() . "";
            $redirectUrl = route('payMomoSendMail', ['fullname' => $fullname, 'address' => $address, 'email' => $email, 'note' => $note, 'phone' => $phone, 'total' => $total, 'payment_method' => $payment_method]);
            $ipnUrl = route('payMomoSendMail', ['fullname' => $fullname, 'address' => $address, 'email' => $email, 'note' => $note, 'phone' => $phone, 'total' => $total, 'payment_method' => $payment_method]);
            $extraData = "";

            $requestId = time() . "";
            $requestType = "payWithATM";
            // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
            //before sign HMAC SHA256 signature
            $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
            $signature = hash_hmac("sha256", $rawHash, $secretKey);
            // dd($signature);
            $data = array(
                'partnerCode' => $partnerCode,
                'partnerName' => "Xác nhận mua hàng",
                "storeId" => "MomoTestStore",
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
            );
            $result = $this->execPostRequest($endpoint, json_encode($data));
            // dd($result);

            $jsonResult = json_decode($result, true);  // decode json
            return redirect()->to($jsonResult['payUrl']);

            // header('Location: ' . $jsonResult['payUrl']);
        } else {
            dd('Error');
        }

        // return redirect(route('bill'));
    }

    public function payMomoSendMail(Request $request, $fullname, $address, $email, $note, $phone, $total, $payment_method)
    {
        // $fullname = $request->input('fullname');
        // $address = $request->input('address');
        // $email = $request->input('email');
        // $note = $request->input('note');
        // $phone = $request->input('phone');
        // $total = $request->input('total');
        // $payment_method = $request->input('payment_method');

        $order = Order::create([
            'fullname' => $fullname,
            'address' => $address,
            'email' => $email,
            'note' => $note,
            'phone' => $phone,
            'total' => $total,
            'payment_method' => $payment_method,
        ]);
        $order_id = $order->getAttribute('id');
        $date_order = $order->getAttribute('created_at');

        foreach (Cart::content() as $product) {
            $product_name = is_array($product->name) ? implode(',', $product->name) : $product->name;
            $qty = is_array($product->qty) ? implode(',', $product->qty) : $product->qty;
            $thumbnail = is_array($product->options->thumbnail) ? implode(',', $product->options->thumbnail) : $product->options->thumbnail;
            $price = is_array($product->price) ? implode(',', $product->price) : $product->price;
            $sub_total = is_array($product->total) ? implode(',', $product->total) : $product->total;

            Order_detail::create([
                'order_id' => $order_id,
                'product_name' => $product_name,
                'qty' => $qty,
                'thumbnail' => $thumbnail,
                'price' => $price,
                'sub_total' => $sub_total,
            ]);
        }

        $productData = [
            'order' => "<table align='center' bgcolor='#dcf0f8' border='0' cellpadding='0' cellspacing='0' style='margin:0;padding:0 15%;background-color:#ffffff;width:100%!important;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px' width='100%'>
                            <tbody>
                                <tr>
                                    <td>
                                        <h1 style='font-size:18px;font-weight:bold;color:#444;padding:0 0 5px 0;margin:0'>Cảm ơn quý khách {$request->input('fullname')} đã đặt hàng tại OfficeStore Store</h1>
                                        <p style='margin:4px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal'>
                                            OfficeStore rất vui thông báo đơn hàng DH#{$order_id} của quý khách đã được tiếp nhận và đang trong quá trình xử lý. OfficeStore sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.</p>
                                        <h3 style='font-size:14px;font-weight:bold;color:#02acea;text-transform:uppercase;margin:20px 0 0 0;border-bottom:1px solid #ddd'>
                                            Thông tin đơn hàng DH#{$order_id} <span style='font-size:13px;color:#777;text-transform:none;font-weight:normal'>($date_order)</span></h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                        <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                                            <thead>
                                                <tr>
                                                    <th align='left' style='padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#444;font-weight:bold' width='50%'>Thông tin thanh toán</th>
                                                    <th align='left' style='padding:6px 9px 0px 9px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;font-weight:bold' width='50%'> Địa chỉ giao hàng </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style='padding:3px 9px 9px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal' valign='top'><span style='text-transform:capitalize'>Họ và tên: {$request->input('fullname')}</span><br>
                                                        <a href='' target='_blank'>Địa chỉ email: {$request->input('email')}</a><br>
                                                        Số điện thoại: {$request->input('phone')}
                                                    </td>
                                                    <td style='padding:3px 9px 9px 9px;border-top:0;border-left:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal' valign='top'><span style='text-transform:capitalize'>{$request->input('fullname')}</span><br>
                                                        <a href='' target='_blank'>Địa chỉ email: {$request->input('email')}</a><br>
                                                        Địa chỉ giao hàng: {$request->input('address')}<br>
                                                        Số điện thoại: {$request->input('phone')}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan='2' style='padding:7px 9px 0px 9px;border-top:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444' valign='top'>
                                                        <p style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px;font-weight:normal'>
                                                            <strong>Phương thức thanh toán: </strong> {$request->input('payment-method')}<br>
                                                            <strong>Thời gian giao hàng dự kiến:</strong> Dự kiến giao hàng trong vòng từ 3-5 ngày<br>
                                                            <strong>Phí vận chuyển: </strong> 0đ<br>
                                                        </p>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h2 style='text-align:left;margin:10px 0;border-bottom:1px solid #ddd;padding-bottom:5px;font-size:14px;color:#02acea'> CHI TIẾT ĐƠN HÀNG</h2>
                                        <table border='0' cellpadding='0' cellspacing='0' style='background:#f5f5f5' width='100%'>
                                            <thead>
                                                <tr>
                                                    <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                        Sản phẩm</th>
                                                    <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                        Đơn giá</th>
                                                    <th align='left' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px;text-align:center'>
                                                        Số lượng</th>
                                                    <th align='right' bgcolor='#02acea' style='padding:6px 9px;color:#fff;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:14px'>
                                                        Tổng tạm</th>
                                                </tr>
                                            </thead>"
        ];
        foreach (Cart::content() as $product) {
            $price = number_format($product->price, 0, '.', ',');
            $sub_total = number_format($product->total, 0, ',', '.');
            $productData['order'] .= "
                                            <tbody bgcolor='#eee' style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                                <tr>
                                                    <td align='left' style='padding:3px 9px' valign='top'><span>{$product->name}</span><br></td>
                                                    <td align='left' style='padding:3px 9px' valign='top'><span>$price đ</span></td>
                                                    <td align='left' style='padding:3px 9px;text-align:center' valign='top'>{$product->qty}</td>
                                                    <td align='right' style='padding:3px 9px' valign='top'><span>$sub_total đ </span></td>
                                                </tr>
                                            </tbody>";
        }

        $total = Cart::total();
        $productData['order'] .= "
                                            <tfoot style='font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#444;line-height:18px'>
                                                <tr>
                                                    <td align='right' colspan='3' style='padding:5px 9px'>Phí vận chuyển</td>
                                                    <td align='right' style='padding:5px 9px'><span>0đ</span></td>
                                                </tr>
                                                <tr bgcolor='#eee'>
                                                    <td align='right' colspan='3' style='padding:7px 9px'><strong><big>Tổng giá trị đơn
                                                                hàng</big> </strong></td>
                                                    <td align='right' style='padding:7px 9px'><strong><big><span>$total đ</span> </big>
                                                    </strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;
                                        <p>Một lần nữa OfficeStore cảm ơn quý khách.</p><font color='#888888'>
                                        <p><strong><a href='' target='_blank' data-saferedirecturl=''>OfficeStore</a>
                                            </strong></p>
                                    </font></td>
                                </tr>
                            </tbody>
                        </table>";

        Mail::to($email)->send(new MailOrder($productData));
        Cart::destroy();
        return redirect(route('bill'));
    }

    public function bill()
    {
        $order = Order::latest()->first();
        return view('client.bill', compact('order'));
    }

    public function product_filter(Request $request)
    {
        $price = $request->input('option');
        $categoryId = $request->input('categoryId'); // Lấy ID của danh mục đã chọn
        $products = Product::query();

        if (!empty($categoryId)) {
            $categoryIds = [$categoryId];
            $category = CategoryProduct::find($categoryId);
            if ($category && $category->parent == 0) {
                $subCategories = CategoryProduct::where('parent', $categoryId)->pluck('id');
                $categoryIds = array_merge($categoryIds, $subCategories->toArray());
            }
            $products->whereIn('category_id', $categoryIds);
        }

        switch ($price) {
            case 1:
                $products->where('price', '<=', 500000);
                break;
            case 2:
                $products->whereBetween('price', [500000, 1000000]);
                break;
            case 3:
                $products->whereBetween('price', [1000000, 5000000]);
                break;
            case 4:
                $products->whereBetween('price', [5000000, 10000000]);
                break;
            case 5:
                $products->whereBetween('price', [10000000, 20000000]);
                break;
            case 6:
                $products->where('price', '>=', 20000000);
                break;
            default:
                // Handle the default case if needed
                break;
        }

        $products = $products->get();

        $str = "";

        foreach ($products as $item) {
            $images = asset($item->thumbnail);
            $name = $item->name;
            $product_id = $item->id;
            $formattedPrice = number_format($item->price - ($item->price / 100) * $item->discount, 0, '', ',') . 'đ';
            $priceOld = number_format($item->price, 0, '', ',') . 'đ';
            $url = route('detail_product', $product_id);

            $str .= "
    <li>
        <a href='$url' title='' class='thumb'>
            <img src='$images' alt='$name'> </a>
        <a href='$url' title='' class='product-name'> $name </a>
        <div class='price'>
            <span class='new'> $formattedPrice </span>
            <span class='old'>";
            if ($item->discount != 0) {
                $str .= "<del style='font-size: 13px; font-style: italic'> $priceOld </del>";
            }
            $str .= "
            </span>
        </div>
        <div class='action clearfix btn-detail pb-2'>
            <a href='$url' title='' class='p-1 rounded text-center'>Xem chi tiết <i class='fa-solid fa-eye'></i></a>
        </div>
    </li>";
        }

        return response()->json([
            'html' => $str
        ]);
    }

    function blog()
    {
        $cats = CategoryProduct::all();
        $posts = Post::paginate(5);

        return view('client.blog', compact('posts', 'cats'));
    }

    function detail_blog($id)
    {
        $cats = CategoryProduct::all();
        $post = Post::find($id);
        return view('client.detail_blog', compact('post', 'cats'));
    }

    function intro()
    {
        $cats = CategoryProduct::all();
        $intro = Page::where('slug', 'gioi-thieu')->first();
        return view('client.intro', compact('intro', 'cats'));
    }

    function contact()
    {
        $cats = CategoryProduct::all();
        $contact = Page::where('slug', 'lien-he')->first();
        return view('client.contact', compact('contact', 'cats'));
    }
}
