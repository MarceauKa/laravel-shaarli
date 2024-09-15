@extends('layouts.app')

@push('meta')
    <meta name="description" content="{{ $page_title }}">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="nav-wall d-flex justify-content-between align-items-center mb-4">
                <h1 class="text-uppercase">{{ $tag->name }}</h1>
                <span class="badge badge-secondary">{{ $tag->posts_count }}</span>
            </div>

            <div class="cards">
                <masonry :cols="{default: 2, 700: 1}" :gutter="30">
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
    </div>
</div>
@endsection
