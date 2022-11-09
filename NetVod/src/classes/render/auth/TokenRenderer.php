<?php

namespace iutnc\netvod\render\auth;

class TokenRenderer implements \iutnc\netvod\render\Renderer
{

    /**
     * @var string
     */
    private string $message;
    private string $style;

    public function __construct(string $message = "", string $style = "error-text")
    {
        $this->message = $message;
        $this->style = $style;
    }

    public function render(int $selector = 1): string
    {
        $content = <<<EOF
            <div class="bg-form">
                <div class="form">
                    <h1>Token</h1>
                        <p class="{$this->style}">{$this->message}</p>
                        <div style="float: right;min-height: 50px;">
                            <a href="?action=signin" class="btn">Se connecter</a>
                        </div>
                      
                </div>
            </div>
         EOF;
        return $content;
    }
}