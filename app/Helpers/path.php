<?php

function loadRoutes($filename, $moduleName){
    return modulePath($moduleName).'routes'.DS().$filename;
}

function loadMigrations($moduleName){
    return modulePath($moduleName).'database'.DS().'migrations';
}

function loadViews($moduleName){
    return modulePath($moduleName).'resources'.DS().'views';
}

function loadConfig($filename, $moduleName){
    return modulePath($moduleName).'config'.DS().$filename;
}

function DS(){
    return DIRECTORY_SEPARATOR;
}

function modulePath($moduleName){
    return app_path().DS().'Modules'.DS().$moduleName.DS();
}
