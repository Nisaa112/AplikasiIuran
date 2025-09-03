<?php

namespace App\Http\Controllers;

// Pastikan use statement ini ada dan benar
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController // Pastikan 'extends BaseController' ada
{
    use AuthorizesRequests, ValidatesRequests;
}