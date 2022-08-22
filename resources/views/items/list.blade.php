<x-app-layout>
   <!-- This example requires Tailwind CSS v2.0+ -->
        <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-xl font-semibold text-gray-900">Items</h1>
            <p class="mt-2 text-sm text-gray-700">A list of all the users in your account including their name, title, email and role.</p>
        </div>
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            <button type="button" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">Add user</button>
        </div>
        </div>
        <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-gray-50">
                    <tr>
                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Raity</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Trading Post</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Crafting</th>
                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last Updated</th>
                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                        <span class="sr-only">Edit</span>
                    </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($items as $item)
                    <tr>
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              <div class="h-10 w-10 flex-shrink-0">
                                  <img class="h-10 w-10 rounded-sm" src="{{$item->icon}}" alt="">
                              </div>
                              <div class="ml-4">
                                  <div class="font-medium text-gray-900">{{$item->name}}</div>
                                  <div class="text-gray-500">{{$item->type}}</div>
                              </div>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <div class="text-gray-900">{{$item->rarity}}</div>
                            <div class="text-gray-500">{{$item->level}}</div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">

                            <div class="text-gray-900">Purchace for <x-price price="{{$item->lowest_sell_listing['unit_price'] ?? null}}" /> </div>
                            <div class="text-gray-500">Selling for <x-price price="{{$item->highest_buy_listing['unit_price'] ?? null}}" /></div>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                          <div class="text-gray-900">Cost <x-price price="{{$item->crafting_cost}}" /></div>
                      </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                            <div class="text-gray-900">{{$item->updated_at->shortRelativeToNowDiffForHumans()}}</div>
                            <div class="text-gray-500">{{$item->listing?->updated_at?->shortRelativeToNowDiffForHumans()}}</div>
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <a href="/items/{{$item->id}}" class="text-indigo-600 hover:text-indigo-900">Edit<span class="sr-only">, {{$item->name}}</span></a>
                        </td>
                    </tr>
                    @endforeach

                    <!-- More people... -->
                </tbody>
                </table>

            </div>
            <div class="py-2">
                {{$items->render()}}
            </div>
            </div>
        </div>
        </div>
</x-app-layout>