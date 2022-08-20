<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class SearchController extends Controller
{
  public function search(Request $request)
  {
    return Item::search($request->get('q'))->paginate(100);
  }
}
