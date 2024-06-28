@extends('admin_layout')
@section('admin_content')
<div class="row">
            <div class="col-lg-12">
                    <section class="panel">
                        <header class="panel-heading">
                           Thêm thông tin website
                        </header>
                         <?php
                            $message = Session::get('message');
                            if($message){
                                echo '<span class="text-alert">'.$message.'</span>';
                                Session::put('message',null);
                            }
                            ?>
                        <div class="panel-body">

                            <div class="position-center">
                                <form role="form" action="{{URL::to('/save-info')}}" method="post" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Thông tin liên hệ</label>
                                    <textarea style="resize: none" data-validation-length="min10" data-validation-error-msg="Làm ơn điền ít nhất 10 ký tự" rows="8" class="form-control" name="info_contact" id="ckeditor" placeholder="Mô tả danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Bản đồ</label>
                                    <textarea style="resize: none"data-validation-length="min10" data-validation-error-msg="Làm ơn điền ít nhất 10 ký tự" rows="8" class="form-control" name="info_map" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">fanpage</label>
                                    <textarea style="resize: none" rows="8" class="form-control" name="info_fanpage" id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                                </div>
                               <div class="form-group">
                                    <label for="exampleInputEmail1">Hình ảnh logo</label>
                                    <input type="file" name="info_image" class="form-control" id="exampleInputEmail1">
                                </div>
                                <button type="submit" name="add_info" class="btn btn-info">Thêm thông tin</button>
                                </form>
                            </div>

                        </div>
                    </section>

            </div>
@endsection