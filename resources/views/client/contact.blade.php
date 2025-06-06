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
                            <a href="{{ route('contact') }}" title="">Liên hệ</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-blog-wp">
                    <div class="section-head clearfix">
                        <h3 class="section-title">{{ $contact->name_page }}
                        </h3>
                    </div>
                    <div class="section-detail">
                        <span class="create-date">{{ $contact->created_at }}</span>
                        <div class="detail">
                            {!! $contact->content !!} </div>
                    </div>
                </div>
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d917.594470356951!2d106.8950419893517!3d10.977900155027012!3m2!1i1024!2i768!4f13.1!5e0!3m2!1svi!2s!4v1687711442011!5m2!1svi!2s"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
                <div class="section" id="social-wp">
                    <div class="section-detail">
                        <div class="fb-like" data-href="" data-layout="button_count" data-action="like" data-size="small"
                            data-show-faces="true" data-share="true"></div>
                        <div class="g-plusone-wp">
                            <div class="g-plusone" data-size="medium"></div>
                        </div>
                        <div class="fb-comments" id="fb-comment" data-href="" data-numposts="5"></div>
                    </div>
                </div>
            </div>
            @include('layouts.sidebar.sidebar')
        </div>
    </div>
@endsection
