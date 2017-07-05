@extends ('layouts.master')

@section ('content')

<!DOCTYPE html>
<html>
<head>
<div class="col-sm-8 blog-main">
    <div class="blog-post">
            <h2 class="blog-post-title">


                {{ $post -> title }}

            </h2>
            <p class="blog-post-meta"> {{ $post -> created_at -> toFormattedDateString()}} </p>

            {{ $post -> body}}
        <div class="comments">
        <hr>
            <u1 class="list-group">
                @foreach ($post ->comments as $comment )
                    <li class="list-group-item">
                        <strong>
                            {{$comment->created_at->diffForHumans()}}:
                        </strong>
                        {{$comment->body}}
                    </li>
                @endforeach
            </u1>
        </div>


        <div class="card">
            <div class="card-block">
                <form method="POST" action="/posts/{{ $post->id }}/comments">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea name="body" placeholder="Your Comment here." class="form-control"> </textarea>

                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add Comment</button>

                    </div>
                </form>
                @include ('layouts.errors')
            </div>
        </div>

    </div>
</div>


</html>
@endsection