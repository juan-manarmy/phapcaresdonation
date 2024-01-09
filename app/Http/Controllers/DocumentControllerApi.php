<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Document;
use Auth;

class DocumentControllerApi extends Controller
{
    public function getDocuments($contribution_id) {
        $documents = Document::where('contribution_id', $contribution_id)
        ->where('type', 'NOD')
        ->get();
        return $documents;
    }
}
