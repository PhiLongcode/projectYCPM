@extends('layouts.admin')
@section('title')
    {{ 'Danh sách bài viết' }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách bài viết</h5>
                <div class="form-search form-inline">
                    <form action="#">@csrf
                        <input type="text" value="{{ request()->input('keyword') }}" class="form-control form-search"
                            placeholder="Tìm kiếm" name="keyword">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <form action="{{ route('post_action') }}" method="post">@csrf
                <div class="card-body">
                    <div class="analytic">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'public']) }}" class="text-primary">Công
                            khai<span class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ
                            duyệt<span class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Thùng
                            rác<span class="text-muted">({{ $count[2] }})</span></a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="action" name="act">
                            <option>--Chọn--</option>
                            @foreach ($list_act as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = ($posts->currentPage() - 1) * $posts->perPage();
                            @endphp
                            @foreach ($posts as $post)
                                @php
                                    $qty++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="listcheck[]" value="{{ $post->id }}">
                                    </td>
                                    <td scope="row">{{ $qty }}</td>
                                    <td><img style="    max-width: 50%;
                                    height: auto;"
                                            src="{{ asset("$post->thumbnail") }}" alt=""></td>
                                    <td style="width:30%"><a
                                            href="{{ route('edit_post', $post->id) }}">{{ $post->post_title }}</a>
                                    </td>
                                    <td>
                                        @foreach ($pages as $page)
                                            @foreach ($page->cat_post as $catPost)
                                                @if ($post->post_cat == $catPost->id)
                                                    {{ $catPost->cat_post_name }}
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                    <td>{{ $post->created_at }}</td>
                                    <td><a href="{{ route('edit_post', $post->id) }}"
                                            class="btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                        <a href="{{ route('delete_post', $post->id) }}"
                                            class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $posts->links() }}
                </div>
            </form>
        </div>
    </div>
@endsection
