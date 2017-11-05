@extends('jiegao.layouts.app')

@section('keywords'){!! $category->getKeywords() !!}@endsection
@section('description'){!! $category->getDescription() !!}@endsection
@section('title'){{ Breadcrumbs::pageTitle(' - ', 'category', $category) }}@endsection

@section('content')
    @widget('navigation_bar')
    <!-- 列表正文start -->
    <div class="list_top_bg"></div>
    <div class="list_container container">
        @widget('navigation_sidebar')
        <div class="main_list">
            <div class="header">
                <ol class="breadcrumb">
                    {{ Breadcrumbs::render('category', $category) }}
                </ol>
            </div>
            <ul class="post_list">
                @foreach($posts as $post)
                    <li>
                        <a href="{!! $post->getPresenter()->url() !!}">{{$post->title}}</a>
                        <span class="time">{!! $post->published_at !!}</span>
                    </li>
                @endforeach
            </ul>
            {!! $posts->fragment('list')->links() !!}
        </div>
    </div>
    <!-- 列表正文end -->
    @include('jiegao.layouts.particals.footer')
@endsection

