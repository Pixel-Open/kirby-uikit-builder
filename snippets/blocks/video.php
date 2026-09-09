<?php
snippet('ui/video', [
    'source'       => $block->source()->value() ?: 'youtube',
    'youtube_url'  => $block->youtube_url()->value(),
    'vimeo_url'    => $block->vimeo_url()->value(),
    'video_file'   => $block->video_file()->toFile(),
    'thumbnail'    => $block->thumbnail()->toFile(),
    'title'        => $block->video_title()->value() ?: 'Video',
    'ratio'        => $block->ratio()->value() ?: '16:9',
    'nocookie'     => !$block->nocookie()->isFalse(),
    'click_to_play'=> $block->click_to_play()->isTrue(),
    'autoplay'     => $block->autoplay()->isTrue(),
    'loop'         => $block->loop()->isTrue(),
    'muted'        => $block->muted()->isTrue(),
    'uid'          => 'video-' . $block->id(),
]);
