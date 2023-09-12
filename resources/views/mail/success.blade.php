<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <!-- Latest compiled and minified CSS -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <title>Xác nhận đơn hàng</title>
    <style>
        *{
            color: #000;
        }
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <div class="container" style="border-radius: 12px; padding:15px;"></div>
        <div class="col-md-12" >
            <p style="text-align: center;color: #000">Đây là email tự động. Quý khách vui lòng không trả lời email này.</p>
        </div>

        <div class="col-md-6 logo" style="color: #000">
            <p>Chào bạn <strong style="color: #000; text-decoration: underline;">Khang</strong></p>
        </div>

        <div class="col-md-12">
            <p style="color: #000; font-size: 17px;">Bạn hoặc một ai đó đã đăng ký dịch vụ tại shop với thông tin như sau:</p>
            <h4 style="color: #000; text-transform: uppercase;">Thông tin đơn hàng</h4>
            <p>Mã đơn hàng: <strong style="text-transform: uppercase; color:#000">Ordercode</strong></p>
            <p>Dịch vụ: <strong style="text-transform: uppercase; color:#000">Đặt hàng trực tuyến</strong></p>

            <h4 style="color: #000; text-transform: uppercase;">Thông tin người nhận</h4>
            <p>Tên: {{ $data['customer_array']['customer_name'] }}</p>
            <p>Email: {{ $data['customer_array']['customer_email'] }}</p>
            <p>Số điện thoại: {{ $data['customer_array']['customer_phone'] }}</p>
            <p>Địa chỉ: {{ $data['customer_array']['customer_address'] }}</p>
            <p>Trạng thái thanh toán: {{ $data['customer_array']['customer_status'] }}</p>

            <p style="color: #000; font-size: 17px;">Nếu thông tin người nhận hàng không có chúng tôi sẽ liên hệ với người đặt hàng để trao đổi thông tin về đơn hàng đã đặt.</p>
            <h4 style="color: #000; text-transform: uppercase;">Sản phẩm đã đặt</h4>
            <table style="width: 100%">
                <thead>
                    <tr>
                        <td style="color: #000; text-transform: uppercase;">Tên sản phẩm</td>
                        <td style="color: #000; text-transform: uppercase;">Số lượng</td>
                        <td style="color: #000; text-transform: uppercase;">Giá tiền</td>
                        <td style="color: #000; text-transform: uppercase;">Thành tiền</td>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $subtotal = 0;
                    $total = 0;
                    @endphp
                    @foreach($data['emailProductsData'] as $product)
                        @php
                        $subtotal = $product['pty'] * $product['price'];
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $product['product_name'] }}</td>
                            <td>{{ $product['pty'] }}</td>
                            <td>{{ $product['price'] }}</td>
                            <td>{{ $product['price'] * $product['pty'] }}</td>
                        </tr>
                    @endforeach
                        <tr>
                            <td colspan="4" align="right" style="color: #000; text-transform: uppercase;">Tổng tiền thanh toán: {{number_format($total,0,',','.')}} vnđ</td>
                        </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Latest compiled and minified JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>
    
