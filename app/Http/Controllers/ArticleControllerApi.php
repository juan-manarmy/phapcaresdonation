<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleControllerApi extends Controller
{
    public function index()
    {
        $articles = Article::all()->where('is_active', 1);
        return $articles;
    }
    public function show($id)
    {
        $article = Article::find($id);
        return $article;
        //
    }
}
