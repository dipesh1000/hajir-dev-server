<?php

namespace Files\Repositories;

interface FileInterface{


    public function getFiles($request);

    public function store($request);

    public function storeFile($file);

    public function delete($id);

    public function showFile($id);

}
