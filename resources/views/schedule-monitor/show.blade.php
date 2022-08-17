<x-app-layout>
    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $monitoredTask->name }}</h3>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                @foreach ($monitoredTask->getAttributes() as $name => $value)
                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">{{ $name }}</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($name === 'icon')
                                <img src={{ $value }} alt="{{ $monitoredTask->name }}" />
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

    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
          <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
              <table class="min-w-full divide-y divide-gray-300">
                  <thead class="bg-gray-50">
                      <tr>
                          <th scope="col"
                              class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Name
                          </th>
                          <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                              Status</th>
                          <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                              Last Updated</th>
                          <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                              <span class="sr-only">Edit</span>
                          </th>
                      </tr>
                  </thead>
                  <tbody class="divide-y divide-gray-200 bg-white">
                      @foreach ($monitoredTaskLogItems as $monitoredTaskLogItem)
                          <tr>
                              <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                  <div class="flex items-center">
                                      <div class="ml-4">
                                          <div class="font-medium text-gray-900">{{ $monitoredTaskLogItem->type }}</div>
                                      </div>
                                  </div>
                              </td>
                              <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <div class="font-medium text-gray-900">{{ $monitoredTaskLogItem->updated_at->shortRelativeToNowDiffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>
                              {{-- <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                @if($jobExecution->status === 'succeeded')
                                  <span
                                      class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800"
                                    >Successful</span>
                                @else
                                  <span
                                      class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800"
                                    >Failed</span>
                                @endif
                              </td>
                              <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                  {{ $jobExecution->created_at?->shortRelativeToNowDiffForHumans() }}</td>
                              --}}
                              <td
                                  class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                  <a href="/schedule-monitor/{{$monitoredTask->id}}/logs/{{ $monitoredTaskLogItem->id }}"
                                      class="text-indigo-600 hover:text-indigo-900">Open</a>
                              </td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>

          </div>
          <div class="py-2">
              {{ $monitoredTaskLogItems->render() }}
          </div>
      </div>
  </div>
</x-app-layout>
