<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Contact;
use Session;
use App\Http\Requests;
use App\Slider;
use App\CatePost;
use Illuminate\Support\Facades\Redirect;

session_start();

class ContactController extends Controller
{
    public function lien_he(Request $request){
        //post 
        $category_post=CatePost::orderby('cate_post_id','DESC')->get();
        //slide
        $slider = Slider::orderBy('slider_id','DESC')->where('slider_status','1')->take(4)->get();
        //seo 
        $meta_desc = "Liên hệ"; 
        $meta_keywords = "Liên hệ";
        $meta_title = "Liên hệ với chúng tôi";
        $url_canonical = $request->url();
        //--seo
        
    	$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get(); 
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get(); 
        return view('pages.lienhe.contact')->with('category',$cate_product)
        ->with('brand',$brand_product)
        ->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)
        ->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)
        ->with('slider',$slider)->with('category_post',$category_post);;
    }
    public function information(){
        return view('admin.information.add_information');
    }
    public function save_info(Request $request){
    $data = $request->all();
    $contact = new Contact();
    $contact->info_contact = $data['info_contact'];
    $contact->info_map = $data['info_map'];
    $contact->info_fanpage = $data['info_fanpage'];
    $get_image = $request->file('info_image');
    $path = 'public/uploads/contact/';
    
    if($get_image){
        $get_name_image = $get_image->getClientOriginalName();
        $name_image = current(explode('.', $get_name_image));
        $new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
        $get_image->move($path, $new_image);
        $contact->info_image = $new_image;
    }
    
    $contact->save();
    return redirect()->back()->with('message', 'Cập nhật thông tin website thành công');
    }
}
