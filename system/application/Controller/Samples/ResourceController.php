<?php
namespace Controller\Samples;

class ResourceController extends \Inphinit\Routing\Resource
{
    public function index() // ($params)
    {
        return 'index';
    }

    public function create() // ($params)
    {
        return 'create';
    }

    public function store() // ($params)
    {
        return 'store';
    }

    public function show() // ($params)
    {
        return 'show';
    }

    public function edit() // ($params)
    {
        return 'edit';
    }

    public function update() // ($params)
    {
        return 'update';
    }

    public function destroy() // ($params)
    {
        return 'destroy';
    }
}
