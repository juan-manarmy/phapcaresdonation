<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Inventory;

class InventoryControllerApi extends Controller
{
        public function getInventory() {
            $member_id = Auth::user()->member_id;
            $inventory = Inventory::where('member_id', $member_id)
            ->orderBy('id', 'DESC')
            ->get();
            return $inventory;
        }
}
