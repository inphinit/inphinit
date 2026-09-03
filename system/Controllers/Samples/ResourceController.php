<?php
namespace Controllers\Samples;

class ResourceController extends \Inphinit\Routing\Resource
{
    public function index()
    {
        return 'index';
    }

    public function create()
    {
        return 'create';
    }

    public function store()
    {
        return 'store';
    }

    public function show($app, $params)
    {
        return 'show: ' . $params['id'];
    }

    public function edit()
    {
        return 'edit';
    }

    public function update()
    {
        return 'update';
    }

    public function destroy()
    {
        return 'destroy';
    }
}
