<x-app-layout>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $item->name }}</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                @foreach ($item->getAttributes() as $name => $value)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ $name }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($name === 'icon')
                                <img src={{ $value }} alt="{{ $item->name }}" />
                            @elseif ($name === 'details')
                                <pre><code>{{ json_encode(json_decode($value), JSON_PRETTY_PRINT) }}</code></pre>
                            @else
                                <span class="whitespace-pre-line">{{ str_replace('<br>', "\n", $value) }}</span>
                            @endif
                        </dd>
                    </div>
                @endforeach
                @if ($item->recipes->isNotEmpty())
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Recipes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach ($item->recipes as $recipe)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <!-- Heroicon name: solid/paper-clip -->
                                            <img src="{{ $recipe->item->icon }}" class="flex-shrink-0 h-5 w-5"
                                                aria-hidden="true" />
                                            <span class="ml-2 flex-1 w-0 truncate"> {{ $recipe->item->name }} </span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="/recipes/{{ $recipe->id }}"
                                                class="font-medium text-indigo-600 hover:text-indigo-500"> Open </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif
                @if ($item->ingredientIn->isNotEmpty())
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Ingredient In</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach ($item->ingredientIn as $recipe)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <!-- Heroicon name: solid/paper-clip -->
                                            <img src="{{ $recipe->item->icon }}" class="flex-shrink-0 h-5 w-5"
                                                aria-hidden="true" />
                                            <span class="ml-2 flex-1 w-0 truncate"> {{ $recipe->item->name }} </span>
                                        </div>
                                        <div class="ml-4 flex-shrink-0">
                                            <a href="/recipes/{{ $recipe->id }}"
                                                class="font-medium text-indigo-600 hover:text-indigo-500"> Open </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif
                @if ($item->listing)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Sell Listings</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach ($item->listing->sell as $listing)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <span class="ml-2 flex-1 w-0 truncate"> {{ $listing['unit_price'] }} -
                                                {{ $listing['quantity'] }} </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Buy Listings</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <ul role="list" class="border border-gray-200 rounded-md divide-y divide-gray-200">
                                @foreach ($item->listing->buy as $listing)
                                    <li class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                        <div class="w-0 flex-1 flex items-center">
                                            <span class="ml-2 flex-1 w-0 truncate"> {{ $listing['unit_price'] }} -
                                                {{ $listing['quantity'] }} </span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>
</x-app-layout>
