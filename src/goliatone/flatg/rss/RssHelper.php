<?php

namespace goliatone\flatg\rss;

use \Suin\RSSWriter\Feed;
use \Suin\RSSWriter\Channel;
use \Suin\RSSWriter\Item;

class RssHelper
{
    
    public function __construct()
    {
        
    }
    
    public function initialize($config)
    {
        
    }
    
    public function render()
    {
        $feed = new Feed();
        
        $channel = new Channel();
        
        $channel->title("Channel Title")
                ->description("Channel Description")
                ->url('http://blog.example.com')
                ->language('en-US')
                ->copyright('Copyright 2012, Foo Bar')
                ->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
                ->lastBuildDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
                ->ttl(60)
                ->appendTo($feed);
        $item = new Item();
        $item
            ->title("Blog Entry Title")
            ->description("<div>Blog body</div>")
            ->url('http://blog.example.com/2012/08/21/blog-entry/')
            ->pubDate(strtotime('Tue, 21 Aug 2012 19:50:37 +0900'))
            ->guid('http://blog.example.com/2012/08/21/blog-entry/', true)
            ->appendTo($channel);
            
        return $feed->render();
    }
    
}
