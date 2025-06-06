@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm sản phẩm
            </div>
            <div class="card-body">
                <form action="{{ route('product_store') }}" method="POST" enctype="multipart/form-data">@csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input class="form-control" type="text" name="name" id="name">
                                @error("name")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Giá</label>
                                <input class="form-control" type="text" name="price" id="name">
                                @error("price")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            </div>

                            <div class="form-group">
                                <label for="name">Giảm giá (%)</label>
                                <input class="form-control" type="text" name="discount" id="name">
                                @error("discount")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="intro">Mô tả sản phẩm</label>
                                <textarea name="desc" class="form-control" id="intro" cols="30" rows="5"></textarea>
                                @error("desc")
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="intro">Chi tiết sản phẩm</label>
                        <textarea name="detail" class="form-control" id="tiny" cols="30" rows="5"></textarea>
                        @error("detail")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    </div>


                    <div class="mb-3">
                        <label for="formFile" class="form-label">Ảnh chính sản phẩm</label>
                        <input class="form-control" type="file" id="thumbnail" name="thumbnail">
                        @error("thumbnail")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    </div>

                    <div class="mb-3">
                        <label for="formFileMultiple" class="form-label">Các ảnh chi tiết</label>
                        <input class="form-control" type="file" id="listFile" multiple name="listFile[]">
                        @error("listFile")
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                    </div>
                    <div class="show-image"></div>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            const thumbnailInput = document.getElementById("thumbnail");
                            const listFileInput = document.getElementById("listFile");
                            const showImageContainer = document.querySelector(".show-image");

                            thumbnailInput.addEventListener("change", function() {
                                const file = this.files[0];
                                const reader = new FileReader();

                                reader.onload = function(e) {
                                    const img = document.createElement("img");
                                    img.src = e.target.result;
                                    showImageContainer.appendChild(img);
                                };

                                if (file) {
                                    reader.readAsDataURL(file);
                                }
                            });

                            listFileInput.addEventListener("change", function() {
                                const files = this.files;

                                for (let i = 0; i < files.length; i++) {
                                    const reader = new FileReader();

                                    reader.onload = function(e) {
                                        const img = document.createElement("img");
                                        img.src = e.target.result;
                                        showImageContainer.appendChild(img);
                                    };

                                    reader.readAsDataURL(files[i]);
                                }
                            });
                        });
                    </script>
                    <div class="form-group">
                        <label for="">Danh mục</label>
                        <select class="form-control" id="" name="category_id">
                            <option value="0">Chọn danh mục</option>
                           {!! $htmlOption !!}
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                value="pending" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                value="public">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                    </div>



                    <button class="btn btn-primary">Thêm mới sản phẩm</button>
                </form>
            </div>
        </div>
    </div>

@endsection
