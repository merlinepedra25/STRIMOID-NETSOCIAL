<?php

use Strimoid\Models\Comment;

$fromTime = Carbon::now()->subDays(3)->minute(0)->second(0);

$builder = Comment::with([
    'user' => function($q) { $q->select(['id', 'avatar', 'name']); },
    'content' => function($q) { $q->select(['id', 'title']); }
])->where('created_at', '>', $fromTime);

if (isset($group))
{
    $builder->where('group_id', $group->id);
}

$popularComments = $builder->orderBy('uv', 'desc')->take(5)->get();

?>

<div class="well popular_contents_widget">
    <h4>Popularne komentarze</h4>

    <ul class="media-list popular_contents_list">
        @foreach ($popularComments as $comment)
        <?php
        $text = preg_replace('/<a class="show_spoiler">(.*?)<\/a>/s', '', $comment->text);
        $text = preg_replace('/<span class="spoiler">(.*?)<\/span>/s', '', $text);
        $text = strip_tags($text);

        $url = route('content_comments', ['content' => $comment->content_id]) .'#'. $comment->id;

        ?>
        <li class="media">
            <a class="pull-left" href="{!! $url !!}" rel="nofollow">
                <img src="{!! $comment->user->getAvatarPath(40, 40) !!}" alt="{!! $comment->user->name !!}" style="height: 40px; width: 40px; border-radius: 3px;">
            </a>
            <div class="media-body">
                <h6 class="media-heading"><a href="{!! $url !!}">{!! Str::limit($text, 100) !!}</a></h6>
                <small>
                    <span class="glyphicon glyphicon-thumbs-up"></span> {!! $comment->uv !!}
                    <span class="glyphicon glyphicon-thumbs-down"></span> {!! $comment->dv !!}

                    @if ($comment->content)
                        <span class="glyphicon glyphicon-share-alt"></span> {!! Str::limit($comment->content->title, 20) !!}
                    @endif
                </small>
            </div>
        </li>
        @endforeach
    </ul>
</div>