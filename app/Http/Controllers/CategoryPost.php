<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Slider;

use App\CategoryProductModel;
use Session;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Redirect;
use App\CatePost;

class CategoryPost extends Controller
{
    public function AuthLogin(){
        $admin_id = Auth::id();
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function add_category_post(){
        $this->AuthLogin();

    	return view('admin.category_post.add_category');
    }
    public function save_category_post(Request $request) {
        $this->AuthLogin();
        $data = $request->all();
        $category_post=new CatePost();
        $category_post->cate_post_name=$data['cate_post_name'];
        $category_post->cate_post_slug=$data['cate_post_slug'];
        $category_post->cate_post_desc=$data['cate_post_desc'];
        $category_post->cate_post_status=$data['cate_post_status'];
        $category_post->save();
        
            Session::put('message', 'Thêm danh mục bài viết thành công');
            return redirect()->back();
        }
    public function all_category_post() {
   
        $this->AuthLogin();
        $category_post= CatePost::orderby('cate_post_id','DESC')->paginate(5);
        
   
        return view('admin.category_post.list_category')->with(compact('category_post'));
    }
    public function edit_category_post($category_post_id) {
    // Check if the user is authenticated
    $this->AuthLogin();
    $category_post= CatePost::find($category_post_id);
    return view('admin.category_post.edit_category')->with(compact('category_post'));
    }

    public function update_category_post(Request $request, $cate_id){
       
        $data = $request->all();
        $category_post = CatePost::find($cate_id);
        $category_post->cate_post_name=$data['cate_post_name'];
        $category_post->cate_post_slug=$data['cate_post_slug'];
        $category_post->cate_post_desc=$data['cate_post_desc'];
        $category_post->cate_post_status=$data['cate_post_status'];
        $category_post->save();     
        Session::put('message', 'Cập nhật danh mục bài viết thành công');
        return redirect('/all-category-post'); 

    }
    public function delete_category_post($cate_id){
        $category_post = CatePost::find($cate_id);
        $category_post->delete();     
        Session::put('message', 'Xóa danh mục bài viết thành công');
        return redirect()->back();
    }
    public function danh_muc_bai_viet(Request $request,$cate_post_slug){
        //post 
        $category_post=CatePost::orderby('cate_post_id','DESC')->get();
        //slide
        $slider = Slider::orderBy('slider_id','DESC')->where('slider_status','1')->take(4)->get();
        //seo 
        $meta_desc = "Chuyên bán quần áo"; 
        $meta_keywords = "Trang web chuyên bán quần áo";
        $meta_title = "Tin tức";
        $url_canonical = $request->url();
        //--seo
        
    	$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get(); 
        
        // $all_product = DB::table('tbl_product')
        // ->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
        // ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        // ->orderby('tbl_product.product_id','desc')->get();
        
        $all_product = DB::table('tbl_product')->where('product_status','0')->orderby(DB::raw('RAND()'))->paginate(6); 

    	return view('pages.tintuc.show_tintuc')->with('category',$cate_product)
        ->with('brand',$brand_product)->with('all_product',$all_product)
        ->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)
        ->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)
        ->with('slider',$slider)->with('category_post',$category_post); //1
        // return view('pages.home')->with(compact('cate_product','brand_product','all_product')); //2
       
    }

}
