<?php

namespace iutnc\netvod\render;

use iutnc\netvod\model\Comment2user;

class CommentRenderer implements Renderer
{

    protected Comment2user $comment;

    public function __construct(Comment2user $comment)
    {
        $this->comment = $comment;
    }


    public function render(int $selector = 1): string
    {
        $html = <<<EOF
            <div class="comment">
                <p><strong>{$this->comment->getUsername()}</strong> 
                <p> {$this->comment->commentaire}</p>
                <p style="font-size: 0.8em; font-style: italic;">{$this->comment->note}/5</p>
            </div>
        EOF;
        return $html;
    }
}