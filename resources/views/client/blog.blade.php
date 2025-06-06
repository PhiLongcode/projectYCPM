@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix blog-page">
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
                <div class="section" id="list-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">Blog</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            @foreach ($posts as $post)
                                <li class="clearfix">
                                    <a href="{{route('detail_blog',$post->id)}}" title="" class="thumb fl-left">
                                        <img src="{{asset($post->thumbnail)}}" alt="">
                                    </a>
                                    <div class="info fl-right">
                                        <a href="{{route('detail_blog',$post->id)}}" title="" class="title">{{$post->post_title}}</a>
                                        <span class="create-date">{{$post->created_at}}</span>
                                        <p class="desc">{{$post->post_title}}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                {{ $posts->links() }}
            </div>
            @include('layouts.sidebar.sidebar');
        </div>
    </div>
@endsection
