@extends('layouts.admin')
@section('title')
    {{ "Thêm bài viết" }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm bài viết
            </div>
            <div class="card-body">
                <form action="{{ route('store_post') }}" enctype="multipart/form-data" method="post">@csrf
                    <div class="form-group">
                        <label for="name">Tiêu đề bài viết</label>
                        <input class="form-control" type="text" name="name" id="name">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="content">Nội dung bài viết</label>
                        <textarea name="content" class="form-control my-editor" id="tiny" cols="30" rows="5"></textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="thumbnail" id="thumbnail"
                            aria-describedby="inputGroupFileAddon04">
                        <label class="custom-file-label" for="thumbnail">Chọn ảnh nổi bật</label>
                    </div>
                    @error('thumbnail')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror

                    <div class="show-image">
                        <img src="" alt="">
                    </div>
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
                        <label for="category">Danh mục</label>
                        <select class="form-control" name="category" id="category">
                            <option>Chọn danh mục</option>
                            @foreach ($pages as $page)
                                <option value="{{ $page->id }}">{{ $page->name_page }}</option>
                                @foreach ($page->cat_post as $cat_posts)
                                    <option value="{{ $cat_posts->id }}">---{{ $cat_posts->cat_post_name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('category')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1"
                                value="pending" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios2"
                                value="public">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                        @error('thumbnail')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm bài viết</button>
                </form>
            </div>
        </div>
    </div>
@endsection
