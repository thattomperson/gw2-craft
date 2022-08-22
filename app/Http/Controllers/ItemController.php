<?php

namespace App\Http\Controllers;

use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Item::with('listing')
          ->has('listing')
          ->paginate();

        return view('items.list', ['items' => $items]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
      $item->load('recipes.ingredients');
      $item->load('ingredientIn.item');
      return view('items.show', ['item' => $item]);
    }
}
