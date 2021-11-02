@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1>{{ __("Change " . ucwords($type) . " Access to Post") }}</h1>
            <div class="card">

              <div class="card-body">
                <form method="POST" action={{ route('post.access.save', ['id' => $post->id, 'type' => $type]) }}>
                  @csrf 
                  @forelse ($users as $user)
                    <div class="form-group">
                      <label for="user_{{ $user->id }}">
                        <input type="checkbox" name="users[]" id="user_{{ $user->id }}" value="{{ $user->id }}" 
                        class="form-control d-inline mr-3" @if ($user->can($type, $post)) checked @endif 
                        style="width: fit-content; vertical-align: middle;" />
                         <span>{{ $user->name }}</span>
                      </label>
                    </div>
                  @empty
                    {{ __('There are no users') }}
                  @endforelse
                  <div class="form-group">
                    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                  </div>
                </form>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection
