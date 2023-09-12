<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Arr;
use App\Models\Product;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Customer;
use App\Jobs\SendMail;

class CartService
{
    public function create($request)
    {
        $qty = (int)$request->input('num_product');
        $product_id = (int)$request->input('product_id');

        if ($qty <= 0 || $product_id <= 0) {
            Session::flash('error', 'Số lượng hoặc Sản phẩm không chính xác');
            return false;
        }

        $carts = Session::get('carts');
        if (is_null($carts)) {
            Session::put('carts', [
                $product_id => $qty
            ]);
            return true;
        }

        $exists = Arr::exists($carts, $product_id);
        if ($exists) {
            $carts[$product_id] = $carts[$product_id] + $qty;
            Session::put('carts', $carts);
            return true;
        }

        $carts[$product_id] = $qty;
        Session::put('carts', $carts);

        return true;

    }

    public function getProduct()
    {
        $carts = Session::get('carts');
        if (is_null($carts)) return [];

        $productId = array_keys($carts);

        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->whereIn('id', $productId)
            ->get();
    }

    public function update($request)
    {
        Session::put('carts', $request->input('num_product'));

        return true;
    }

    public function remove($id)
    {
        $carts = Session::get('carts');

        unset($carts[$id]);
        Session::put('carts', $carts);

        return true;
    }

    public function addCart($request)
    {
        try {
            DB::beginTransaction();
            $carts = Session::get('carts');
            if (is_null($carts)) return false;
            $customer = Customer::create([
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
                'email' => $request->input('email'),
                'content' => $request->input('content'),
                'status' => 'Chưa thanh toán'
            ]);

            $customer_array = array(
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_address' => $customer->address,
                'customer_email' => $customer->email,
                'customer_content' => $customer->content,
                'customer_status' => $customer->status,
            );

            $emailProductsData = $this->infoProductCart($carts, $customer->id);

            $data = array(
                'customer_array' => $customer_array,
                'emailProductsData' =>$emailProductsData,
            );

            DB::commit($emailProductsData);

            Session::flash('success', 'Đặt hàng thành công');

            SendMail::dispatch($request->input('email'), $data)->delay(now()->addSeconds(2));

            Session::forget('carts');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', 'Đặt hàng lỗi');
            return false;
        }

        return true;
    }

    public function addCartWithPayment($vnp_Bill_Name, $vnp_Bill_Mobile, $vnp_Bill_Address,$vnp_Bill_Email, $vnp_Bill_Content)
    {
        try {
            DB::beginTransaction();
            $carts = Session::get('carts');
            if (is_null($carts)) return false;

            $customer = Customer::create([
                'name' => $vnp_Bill_Name,
                'phone' =>  $vnp_Bill_Mobile,
                'address' => $vnp_Bill_Address,
                'email' => $vnp_Bill_Email,
                'content' => $vnp_Bill_Content,
                'status' => "Đã thanh toán"
            ]);

            $customer_array = array(
                'customer_name' => $customer->name,
                'customer_phone' => $customer->phone,
                'customer_address' => $customer->address,
                'customer_email' => $customer->email,
                'customer_content' => $customer->content,
                'customer_status' => $customer->status,
            );

            $emailProductsData = $this->infoProductCart($carts, $customer->id);

            $data = array(
                'customer_array' => $customer_array,
                'emailProductsData' =>$emailProductsData,
            );

            $this->infoProductCart($carts, $customer->id);
            DB::commit();

            Session::flash('success', 'Đặt hàng thành công');

            SendMail::dispatch($vnp_Bill_Email, $data)->delay(now()->addSeconds(2));

            Session::forget('carts');
        } catch (\Throwable $th) {
            DB::rollBack();
            Session::flash('error', 'Đặt hàng lỗi');
            return false;
        }

        return true;
    }

    public function infoProductCart($carts, $customer_id)
    {
        $productId = array_keys($carts);

            $products = Product::select('id', 'name', 'price', 'price_sale', 'thumb')
                ->where('active', 1)
                ->whereIn('id', $productId)
                ->get();

        $data = [];
        foreach($products as $key => $product){
            $data[] = [
                'customer_id' => $customer_id,
                'product_id' => $product->id,
                'pty' => $carts[$product->id],
                'price' => $product->price_sale !== 0 ? $product->price_sale : $product->price
            ];
            $emailProductsData[] = [
                'customer_id' => $customer_id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'pty' => $carts[$product->id],
                'price' => $product->price_sale !== 0 ? $product->price_sale : $product->price
            ];
        }

        Cart::insert($data);

        return $emailProductsData;
    }

    public function getCustomer()
    {
        return Customer::orderByDesc('id')->get();
    }

    public function getProductForCart($customer)
    {
        return $customer->carts()->with(['product' => function ($query) {
            $query->select('id', 'name', 'thumb');
        }])->get();
    }
}