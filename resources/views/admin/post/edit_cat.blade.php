@extends('layouts.admin')
@section('title')
    {{ "Chỉnh sửa danh mục" }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-4">
                @if (request()->input('status') != 'trash')
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            Cập nhật danh mục
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update_cat_post', $cat_post->id ) }}" method="POST">@csrf
                                <div class="form-group">
                                    <label for="cat_post_name">Tên loại danh mục</label>
                                    <input class="form-control" value="{{ $cat_post->cat_post_name }}" type="text"
                                        name="cat_post_name" id="cat_post_name">
                                    @error('cat_post_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="cat">Danh mục</label>
                                    <select class="form-control" id="cat" name="post_cat_id">
                                        <option value="">--Chọn danh mục--</option>
                                        @foreach ($pages as $k => $page)
                                            <option value="{{ $page->id }}" @if ($cat_post->post_cat_id == $page->id) selected @endif>{{ $page->name_page }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_pending"
                                            value="pending" @if ($cat_post->status == 'pending') checked @endif>
                                        <label class="form-check-label" for="status_pending">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_public"
                                            value="public" @if ($cat_post->status == 'public') checked @endif>
                                        <label class="form-check-label" for="status_public">
                                            Công khai
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Cập nhật danh mục bài viết</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
