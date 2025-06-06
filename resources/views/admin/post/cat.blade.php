@extends('layouts.admin')
@section('title')
    {{ "Thêm danh mục" }}
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
                            Loại danh mục
                        </div>
                        <div class="card-body">
                            <form action="{{ route('add_cat_post') }}" method="POST">@csrf
                                <div class="form-group">
                                    <label for="cat_post_name">Tên loại danh mục</label>
                                    <input class="form-control" type="text" name="cat_post_name" id="cat_post_name">
                                    @error('cat_post_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="cat">Danh mục</label>
                                    <select class="form-control" id="cat" name="post_cat_id">
                                        <option value="">--Chọn danh mục--</option>
                                        @foreach ($pages as $k => $page)
                                            <option value="{{ $page->id }}">{{ $page->name_page }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_pending"
                                            value="pending" checked>
                                        <label class="form-check-label" for="status_pending">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="status_public"
                                            value="public">
                                        <label class="form-check-label" for="status_public">
                                            Công khai
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm loại danh mục bài viết</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Stt</th>
                                    <th scope="col">Tên danh mục</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $qty = ($cat_pages->currentPage() - 1) * $cat_pages->perPage();
                                @endphp
                                @foreach ($cat_pages as $cat_page)
                                    @php
                                        $qty++;
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $qty }}</th>
                                        <td>{{ $cat_page->name_page }}</td>
                                        <td>{{ $cat_page->slug }}</td>
                                        <td>
                                            <span
                                                class="
                                        @if ($cat_page->status == 'pending') badge badge-warning
                                            @elseif ($cat_page->status == 'public')
                                            badge badge-success @endif
                                        ">
                                                @if ($cat_page->status == 'pending')
                                                    {{ 'Chờ duyệt' }}
                                                @else
                                                    {{ 'Công khai' }}
                                                @endif
                                            </span>

                                        </td>
                                        <td><a href="{{ route('edit_page', $cat_page->id) }}"
                                                class="btn btn-success btn-sm rounded-0" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ route('delete_page', $cat_page->id) }}"
                                                class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                                data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                    {{-- @dd($cat_page->cat_post) --}}

                                    @foreach ($cat_page->cat_post as $post)
                                        <tr>
                                            <th scope="row">*</th>
                                            <td>---{{ $post->cat_post_name }}</td>
                                            <td>{{ $post->slug }}</td>
                                            <td>
                                                <span
                                                    class="
                                            @if ($post->status == 'pending') badge badge-warning
                                                @elseif ($post->status == 'public')
                                                badge badge-success @endif
                                            ">
                                                    @if ($post->status == 'pending')
                                                        {{ 'Chờ duyệt' }}
                                                    @else
                                                        {{ 'Công khai' }}
                                                    @endif
                                                </span>

                                            </td>
                                            <td>
                                                <a href="{{route('edit_cat_post',$post->id)}}" class="btn btn-success btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="{{route('delete_cat_post',$post->id)}}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
