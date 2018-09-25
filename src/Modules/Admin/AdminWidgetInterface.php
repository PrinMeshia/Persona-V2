<?php

namespace Modules\Admin;

interface AdminWidgetInterface{
    
    public function render():string;
    public function renderMenu():string;
}