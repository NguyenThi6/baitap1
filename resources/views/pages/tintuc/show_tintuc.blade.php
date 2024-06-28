@extends('layout')
@section('content')
<div class="features_items"><!--features_items-->
       
                        <h2 class="title text-center">Tin tức</h2>
                        
                     
<div class="col-sm-4">
    <div class="product-image-wrapper">
        <div class="single-products">
            <div class="productinfo text-center">
                <form>
                   @csrf
                    <input type="button" value="Tin thức"  name="add-to-cart">
                    <p>{{$category_post->$category_post_name}}</p>
                </form>
            </div>
        </div>

        <div class="choose">
            <ul class="nav nav-pills nav-justified">
                
                <li>
                    <i class="fa fa-plus-square"></i>
                    <button type="button" class="button_wishlist" id="" 
                    onclick="add_wishlist(this.id);"><span>Yêu thích</span></button>
                </li>
                <li><a href="#"><i class="fa fa-plus-square"></i>So sánh</a></li>
            </ul>
        </div>
    </div>
</div>


@endsection