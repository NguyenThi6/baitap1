<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\City;
use App\Province;
use App\Wards;
use App\Feeship;
use App\Shipping;
use App\Order;
use App\OrderDetails;
use App\Customer;
use App\Coupon;
use App\Product;
use PDF;

class OrderController extends Controller
{

    public function update_qty(Request $request){
        $data = $request->all();
        
    
        $order_details = OrderDetails::where('product_id', $data['order_product_id'])
                                    ->where('order_code', $data['order_code'])
                                    ->first();
        

        $order_details->product_sales_quantity = $data['order_qty'];
        
 
        $order_details->save();
    }

    public function update_order_qty(Request $request){
       //update order quantity
        $data=$request->all();
        $order =Order::find($data['order_id']);
        $order->order_status=$data['order_status'];
        $order->save();
        if($order->order_status==2){
            foreach($data['order_product_id'] as $key =>$product_id){
                $product=Product::find($product_id);
                $product_quantity=$product->product_quantity;
                $product_sold=$product->product_sold;
                foreach($data['quantity'] as $key2 =>$qty){
                    if($key==$key2){
                        $pro_remain=$product_quantity-$qty;
                        $product->product_quantity=$pro_remain;
                        $product->product_sold=$product_sold+$qty;
                        $product->save();
                    }
                 }
            }
        }elseif($order->order_status!=2 && $order->order_status!=3){
            foreach($data['order_product_id'] as $key =>$product_id){
                $product=Product::find($product_id);
                $product_quantity=$product->product_quantity;
                $product_sold=$product->product_sold;
                foreach($data['quantity'] as $key2 =>$qty){
                    if($key==$key2){
                        $pro_remain=$product_quantity+$qty;
                        $product->product_quantity=$pro_remain;
                        $product->product_sold=$product_sold-$qty;
                        $product->save();
                    }
                 }
            }
        }
    }
    public function print_order($checkout_code){
        $pdf=\App::make('dompdf.wrapper');
        $pdf->loadHTML($this->print_order_convert($checkout_code));
        return $pdf->stream();
    }
    public function print_order_convert($checkout_code){
        $order_details=OrderDetails::where('order_code',$checkout_code)->get();
        $order =Order::where('order_code',$checkout_code)->get();
        foreach($order as $key=>$ord){
            $customer_id=$ord->customer_id;
            $shipping_id=$ord->shipping_id;
            
        }
        $customer=Customer::where('customer_id',$customer_id)->first();
        $shipping=Shipping::where('shipping_id',$shipping_id)->first();

        $order_details_product=OrderDetails::with('product')->where('order_code',$checkout_code)->get();
        foreach($order_details_product as $key=>$order_d){
                    $product_coupon=$order_d->product_coupon;              
                }
                if ($product_coupon != 'no') {
    // Retrieve the coupon details from the database
    $coupon = Coupon::where('coupon_code', $product_coupon)->first();

    if ($coupon) { // Ensure the coupon exists
        $coupon_condition = $coupon->coupon_condition;
        $coupon_number = $coupon->coupon_number;

        if ($coupon_condition == 1) {
          
            $coupon_echo = $coupon_number . '%';
        } elseif ($coupon_condition == 2) {
            $coupon_echo = number_format($coupon_number, 0, ',', '.') . ' VND';
        }
    } else {
        
        $coupon_condition = 2;
        $coupon_number = 0;
        $coupon_echo = 'Coupon not found';
    }
    } else {
       
        $coupon_condition = 2;
        $coupon_number = 0;
        $coupon_echo = 'Không có mã'; 
    }

        $output = '';
$output .= '<style>
                body {
                    font-family: DejaVu Sans;
                }
                .table-styling {
                    border: 1px solid #000;
                }
                .table-styling tr td {
                    border: 1px solid #000;
                }
            </style>
            <h1><center>Công Ty TNHH một thành viên ABCD</center></h1>
            <h1><center>ĐỘC LẬP - TỰ DO - HẠNH PHÚC</center></h1>
            <p>Người đặt hàng </p>
            <table class="table-styling">
                <thead>
                    <tr>
                        <th>Tên khách đặt</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . $customer->customer_name . '</td>
                        <td>' . $customer->customer_phone . '</td>
                        <td>' . $customer->customer_email . '</td>
                    </tr>
                </tbody>
            </table>';

$output .= '
				<p> Ship hàng tới </p>
            <table class="table-styling">
                <thead>
                    <tr>
                        <th>Tên người nhận</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Email</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>' . $shipping->shipping_name . '</td>
                        <td>' . $shipping->shipping_address . '</td>
                        <td>' . $shipping->shipping_phone . '</td>
                        <td>' . $shipping->shipping_email . '</td>
                        <td>' . $shipping->shipping_notes . '</td>
                    </tr>
                </tbody>
            </table>';

$output .= '<p>Đơn đặt hàng </p>
            <table class="table-styling">
                <thead>
                    <tr>
                        <th>Tên sản phẩm</th>
                        <th>Mã giảm giá</th>
                        <th>Phí ship</th>
                        <th>Số lượng</th>
                        <th>Giá sản phẩm</th>
                        <th>Thành tiền</th>
                    </tr>
                </thead>
                <tbody>';
                $total=0;
                foreach($order_details_product as $key => $product) {
                    $subtotal = $product->product_price * $product->product_sales_quantity;
                    $total+=$subtotal;
                    if($product->product_coupon!='no'){
                        $product_coupon=$product->product_coupon;
                    }else{
                            $product_coupon='Không có mã';
                    }
                    $output .= '<tr>
                                    <td>' . $product->product_name . '</td>
                                    <td>' . $product_coupon . '</td>
                                    <td>' . number_format($product->product_feeship, 0, ',', '.') . ' VND</td>
                                    <td>' . $product->product_sales_quantity . '</td>
                                    <td>' . number_format($product->product_price, 0, ',', '.') . ' VND</td>
                                    <td>' . number_format($subtotal, 0, ',', '.') . ' VND</td>
                                </tr>';
                }
                if($coupon_condition==1){
                        $total_after_coupon = ($total * $coupon_number) / 100;
                
                  $total_coupon = $total - $total_after_coupon ;
                    }else{
                        
                        $total_coupon = $total - $coupon_number ;
                    }
                $output.='<tr>
                    <td colspan="2">
                        <p>Tổng giảm :'.$coupon_echo.'</p>
                        <p>Phí ship :'. number_format($product->product_feeship, 0, ',', '.').' VND  </p>
                        <p>Thanh Toán:'.number_format($total_coupon-$product->product_feeship, 0, ',', '.').'VND</p>
                    </td>
                </tr>';

$output .= '</tbody>
            </table>';
            $output .= '<p>Ký tên hoặc xác nhận người nhận</p>
            <table>
                <thead>
                    <tr>
                        <th width="200px">Tên lập phiếu</th>
                        <th width="700px">Người nhận</th>
                     
                    </tr>
                </thead>
                <tbody>
                   
                </tbody>
            </table>';

return $output;




    }
    public function view_order($order_code){
        $order_details=OrderDetails::with('product')->where('order_code',$order_code)->get();
        $order =Order::where('order_code',$order_code)->get();
        foreach($order as $key=>$ord){
            $customer_id=$ord->customer_id;
            $shipping_id=$ord->shipping_id;
            $order_status=$ord->order_status;
            
        }
        $customers=Customer::where('customer_id',$customer_id)->first();
        $shipping=Shipping::where('shipping_id',$shipping_id)->first();


        $order_details_product=OrderDetails::with('product')->where('order_code',$order_code)->get();
                foreach($order_details_product as $key=>$order_d){
                    $product_coupon=$order_d->product_coupon;              
                }
            if($product_coupon!='no'){
                $coupon = Coupon::where('coupon_code', $product_coupon)->first();
                $coupon_condition=$coupon->coupon_condition;
                $coupon_number=$coupon->coupon_number;
            }else{
                $coupon_condition=2;
                    $coupon_number=0;
                
            }
		
        return view('admin.view_order')
        ->with(compact('order_details','customers'
        ,'shipping','coupon_number','coupon_condition','order','order_status'));
            

       
    }
    public function manage_order(){
        $orders =Order::orderby('created_at','DESC')->get();
		
        return view('admin.manage_order')->with(compact('orders'));
    }
	public function deleteOrder($order_code)
	{
  
    $order = Order::where('order_code', $order_code)->first();
    
    if (!$order) {
       
        abort(404, 'Order not found');
    }
    
  
    $order->delete();


    return redirect()->back()->with('success', 'Order deleted successfully');
	}

}
