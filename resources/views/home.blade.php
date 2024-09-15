@extends('layouts.app')

@push('meta')
    <meta name="description" content="{{ $page_title }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if($walls->isNotEmpty())
        <div class="col-12">
            <div class="nav-wall mb-4">
                <h1>
                    {{ $wall->title }}
                </h1>
                @foreach($walls as $item)
                    <a href="{{ route('home', $item->slug) }}">{{ $item->title }}</a>
                @endforeach
            </div>
        </div>
        @endif

        @if($posts->isNotEmpty())
        <div class="col-12">
            <div class="cards {{ $compact ? 'compact' : '' }}">
                <masonry :cols="{{ $columns }}" gutter="30">
                    @if($tags->isNotEmpty())
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-tags"></i> {{ __('Tags') }}
                            </div>
                            <div class="card-body">
                                <tag-limiter>
                                    @foreach($tags as $tag)
                                        <a href="{{ $tag->url }}" class="btn btn-primary btn-sm mb-1">
                                            {{ $tag->name }}
                                            <span class="badge badge-pill badge-light">{{ $tag->posts_count }}</span>
                                        </a>
                                    @endforeach
                                </tag-limiter>
                            </div>
                        </div>
                    @endif
                    @foreach($posts as $post)
                        @if($post->postable instanceof \App\Models\Link)
                            <link-card :single="false" :link="{{ json_encode(\App\Http\Resources\LinkResource::make($post->postable)) }}"></link-card>
                        @elseif($post->postable instanceof \App\Models\Story)
                            <story-card :single="false" :story="{{ json_encode(\App\Http\Resources\StoryResource::make($post->postable)) }}"></story-card>
                        @elseif($post->postable instanceof \App\Models\Chest)
                            <chest-card :single="false" :chest="{{ json_encode(\App\Http\Resources\ChestResource::make($post->postable)) }}"></chest-card>
                        @elseif($post->postable instanceof \App\Models\Album)
                            <album-card :single="false" :album="{{ json_encode(\App\Http\Resources\AlbumResource::make($post->postable)) }}"></album-card>
                        @endif
                    @endforeach
                </masonry>
            </div>

            {{ $posts->links() }}
        </div>
        @else
            <div class="col-12 col-md-auto">
                <div class="card">
                    <div class="card-body">
                        <p class="text-center lead">
                            {{ __('Whoops!') }}
                        </p>

                        <p class="text-center">
                            <a href="{{ route('link.create') }}" class="btn btn-outline-primary">{{ __('Add link') }}</a>
                            <a href="{{ route('story.create') }}" class="btn btn-outline-primary">{{ __('Add story') }}</a>
                            <a href="{{ route('chest.create') }}" class="btn btn-outline-primary">{{ __('Add chest') }}</a>
                            <a href="{{ route('album.create') }}" class="btn btn-outline-primary">{{ __('Add album') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
