@extends('layouts.app')

@push('head_scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.css">
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $post ? __('View Post') : __('New Post') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.save', ['id' => $post ? $post->id : null]) }}">
                        @csrf
                        @error('post')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @if ($post && !Auth::user()->can('edit', $post))
                            <div class="alert alert-info">{{ __('You have view permissions only') }}</div>
                        @endif
                        <div class="form-group">
                            <label for="title">{{ __('Title') }}</label>
                            <input type="text" name="title" id="title" placeholder="Title" required 
                               value="{{ $post ? $post->title : old('title') }}" class="form-control @error('title') is-invalid @enderror" 
                               @if($post && !Auth::user()->can('edit', $post)) disabled="true" @endif />
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="content">{{ __('Content') }}</label>
                            @error('content')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @if($post && !Auth::user()->can('edit', $post))
                                {!! $post->content !!}
                            @else 
                                <input id="content" type="hidden" name="content" value="{{ $post ? $post->content : old('content') }}">
                                <trix-editor input="content"></trix-editor>
                            @endif
                        </div>
                        @if(!$post || Auth::user()->can('edit', $post))
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/trix@1.3.1/dist/trix.js"></script>
@endsection
