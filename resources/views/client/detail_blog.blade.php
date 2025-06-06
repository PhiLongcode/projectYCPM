@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-blog-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('/') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ route('blog') }}" title="">Blog</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">{{ $post->post_title }}
                        </h3>
                    </div>
                    <div class="section-detail">
                        <span class="create-date">{{ $post->created_at }}</span>
                        <div class="detail">
                            {!! $post->content_post !!} </div>
                    </div>
                </div>
            </div>
            @include('layouts.sidebar.sidebar')
        </div>
    </div>
@endsection
