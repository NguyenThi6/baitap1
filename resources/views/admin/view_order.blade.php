@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
  
  <div class="panel panel-default">
    <div class="panel-heading">
     Thông tin khách hàng
    </div>
    
    <div class="table-responsive">
        @php
            $message = Session::get('message');
            if($message) {
                echo '<span class="text-alert">'.$message.'</span>';
                Session::put('message', null);
            }
        @endphp
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên khách hàng</th>
            <th>Số điện thoại</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$customers->customer_name}}</td>
            <td>{{$customers->customer_phone}}</td>
            <td>{{$customers->customer_email}}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br>

<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
     Thông tin vận chuyển hàng
    </div>
    <div class="table-responsive">
        @php
            $message = Session::get('message');
            if($message) {
                echo '<span class="text-alert">'.$message.'</span>';
                Session::put('message', null);
            }
        @endphp
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Tên người vận chuyển</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Email</th>
            <th>Ghi chú</th>
            <th>Hình thức thanh toán</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{$shipping->shipping_name}}</td>
            <td>{{$shipping->shipping_address}}</td>
            <td>{{$shipping->shipping_phone}}</td>
            <td>{{$shipping->shipping_email}}</td>
            <td>{{$shipping->shipping_notes}}</td>
            <td>@if($shipping->shipping_method == 0) 
                    Chuyển khoản 
                @else 
                    Tiền mặt 
                @endif
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br><br>

<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Chi tiết đơn hàng
    </div>
    <div class="table-responsive">
        @php
            $message = Session::get('message');
            if($message) {
                echo '<span class="text-alert">'.$message.'</span>';
                Session::put('message', null);
            }
        @endphp
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th style="width:20px;">
              <label class="i-checks m-b-none">
                <input type="checkbox"><i></i>
              </label>
            </th>
            <th>Tên sản phẩm</th>
            <th>Số lượng kho còn</th>
            <th>Mã giảm giá</th>
            <th>Phí ship hàng</th>
            <th>Số lượng</th>
            <th>Giá sản phẩm</th>
            <th>Tổng tiền</th>
          </tr>
        </thead>
        <tbody>
          @php 
            $i = 0;
            $total = 0;
          @endphp
          @foreach($order_details as $key => $details)
            @php 
                $i++;
                $subtotal = $details->product_price * $details->product_sales_quantity;
                $total += $subtotal;
            @endphp
            <tr class="color_qty_{{$details->product_id}}">
              <td><i>{{$i}}</i></td>
              <td>{{$details->product_name}}</td>
              <td>{{$details->product->product_quantity}}</td>
              <td>@if($details->product_coupon != 'no')
                      {{$details->product_coupon}}
                  @else 
                      Không mã
                  @endif
              </td>
              <td>{{number_format($details->product_feeship, 0, ',', '.')}}VND</td>
              <td>
                <input type="number" min="1" {{$order_status == 2 ? 'disabled' : ''}} class="order_qty_{{$details->product_id}}" value="{{$details->product_sales_quantity}}" name="product_sales_quantity">
                <input type="hidden" name="order_qty_storage" class="order_qty_storage_{{$details->product_id}}" value="{{$details->product->product_quantity}}">
                <input type="hidden" name="order_code" class="order_code" value="{{$details->order_code}}">
                <input type="hidden" name="order_product_id" class="order_product_id" value="{{$details->product_id}}">
                @if($order_status != 2) 
                  <button class="btn btn-default update_quantity_order" data-product_id="{{$details->product_id}}" name="update_quantity_order">Cập nhật</button>
                @endif
              </td>
              <td>{{number_format($details->product_price, 0, ',', '.')}}VND</td>
              <td>{{number_format($subtotal, 0, ',', '.')}}VND</td>
            </tr>
          @endforeach
          <tr>
          <td colspan="2">
                      @php
                      $total_coupon = 0;
                      @endphp
                      @if($coupon_condition == 1)
              @php
              $total_after_coupon = ($total * $coupon_number) / 100;
              echo 'Tổng giảm: ' . number_format($total_after_coupon, 0, ',', '.') . ' VND<br>';
              $total_coupon = $total - $total_after_coupon - $details->product_feeship;
              @endphp
          @else
              @php
              echo 'Tổng giảm: ' . number_format($coupon_number, 0, ',', '.') . ' VND<br>';
              $total_coupon = $total - $coupon_number - $details->product_feeship;
              @endphp
          @endif
          Phí Ship: {{ number_format($details->product_feeship, 0, ',', '.') }} VND <br>
          Thanh toán: {{ number_format($total_coupon, 0, ',', '.') }} VND

                  </td>

          </tr>
        </tbody>
      </table>
      <a target="_blank" href="{{url('/print-order/'.$details->order_code)}}">In đơn hàng</a>
    </div>
  </div>
</div>
@endsection
