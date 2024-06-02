<?php
namespace Controller;

use Inphinit\Viewing\View;

class Home
{
    public function index()
    {
        $items = [
            [
                'link' => '#',
                'title' => 'Routing',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Controllers',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Resource routes',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Implicity routes',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'HTTP',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'HTTP Accept headers',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Debugging',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'DOM',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Configurations',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Maintenance mode',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Events',
                'content' => ''
            ], [
                'link' => '#',
                'title' => 'Controllers',
                'content' => ''
            ]
        ];

        View::render('home', [
            'items' => $items,
            'intro' => 'PHP framework'
        ]);
    }
}
