<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use MJS\TopSort\Implementations\StringSort;

class CacheCraftingCost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:crafting-cost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $items = Item::with(['recipes.recipeIngredients', 'listing'])->get()->keyBy('remote_id');
      $sorter = new StringSort();

      foreach ($items as $item) {
        if ($item->recipes->isEmpty()) {
          $sorter->add($item->remote_id, []);
        } else {
          foreach ($item->recipes as $recipe) {

            $sorter->add($item->remote_id, $recipe->recipeIngredients->pluck('remote_item_id')->toArray());
          }
        }
      }

      $ids = $sorter->sort();
      $this->withProgressBar($ids, function ($id) use ($items) {
        $item = $items[$id];
        $lowest_cost = null;
        foreach ($item->recipes as $recipe) {
          $cost = 0;

          foreach ($recipe->recipeIngredients as $recipeIngredient) {
            $ingredient = $items[$recipeIngredient->remote_item_id];
            $listing = $ingredient->lowest_sell_listing;
            if ($listing) {
              $cost += min($listing['unit_price'], $ingredient->crafting_cost ?? PHP_INT_MAX) * $recipeIngredient->count;
            } elseif($ingredient->crafting_cost) {
              $cost += $ingredient->crafting_cost * $recipeIngredient->count;
            }
          }

          $recipe->crafting_cost = $cost === 0 ? null : $cost;
          $lowest_cost = min($cost, $lowest_cost ?? PHP_INT_MAX);
          $recipe->save();
        }

        $item->crafting_cost = $lowest_cost === PHP_INT_MAX ? null : $lowest_cost;
        $item->save();
      });


      return 0;
    }
}
