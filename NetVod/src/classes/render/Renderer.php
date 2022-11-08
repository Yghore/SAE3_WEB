<?php

namespace iutnc\netvod\render;

use ViewException;

class Renderer
{

    private static $html;

    public function __construct()
    {
        Renderer::$html = "";
    }

    public static function echo()
    {
        echo Renderer::$html;
    }

    /**
     * @throws ViewException Si la vue n'existe pas
     */
    public function getView($name) : string
    {
        $file = 'view/'.$name .'.gview.html';
        if(file_exists($file))
        {
            return file_get_contents($file);
        }
        throw new ViewException("La view n'existe pas!");
    }

    /**
     * @param string $view Le nom de la vue
     * @param array $data le tableau de data doit etre cohérent avec la vue si dans vue {{$etre}} alors le tableau doit contenit $etre => value
     * @return void ajoute au HTML principale
     */
    public function addHtmlWithData(string $view, array $data)
    {
        $view = $this->getView($view);
        foreach ($data as $k => $v)
        {
            $view = str_replace("{{$k}}", $v, $view);
        }
        Renderer::$html .= $view;
    }

}