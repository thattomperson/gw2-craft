<x-app-layout>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $jobExecution->name }}</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                @foreach ($jobExecution->getAttributes() as $name => $value)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ $name }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($name === 'icon')
                                <img src={{ $value }} alt="{{ $jobExecution->name }}" />
                            @elseif ($name === 'details')
                                <pre><code>{{ json_encode(json_decode($value), JSON_PRETTY_PRINT) }}</code></pre>
                            @else
                                <span class="whitespace-pre-line">{{ str_replace('<br>', "\n", $value) }}</span>
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </div>
</x-app-layout>
