@props(['items'])

<nav class="flex mb-6" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    @foreach($items as $index => $item)
      <li @if($index === 0) class="inline-flex items-center" @endif>
        @if($index > 0)
          <div class="flex items-center">
            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd" />
            </svg>
        @endif

          @if(isset($item['url']) && !$loop->last)
            <a href="{{ $item['url'] }}"
              class="@if($index === 0) text-gray-700 hover:text-primary-600 transition-colors @else ml-1 text-gray-700 hover:text-primary-600 transition-colors @endif">
              @if(isset($item['icon']))
                {!! $item['icon'] !!}
              @endif
              {{ $item['label'] }}
            </a>
          @else
            <span class="@if($index === 0) text-gray-500 @else ml-1 text-gray-500 @endif">
              {{ $item['label'] }}
            </span>
          @endif

          @if($index > 0)
            </div>
          @endif
      </li>
    @endforeach
  </ol>
</nav>