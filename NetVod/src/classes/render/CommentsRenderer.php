<?php

namespace iutnc\netvod\render;

class CommentsRenderer implements Renderer
{

    protected array $comments;

    public function __construct(array $comments)
    {
        $this->comments = $comments;
    }


    public function render(int $selector = 1): string
    {
        $html = "<div class='comments'>";
        foreach ($this->comments as $v)
        {
            $html .= (new CommentRenderer($v))->render();
        }
        $html .= "</div>";
        return $html;
    }
}