@php
    $menu = SiteMenu::get('main-menu');
    $is_burger = $menu && $menu->items->count() > $menu->max_visible_items;
@endphp

<main-menu>
    <div class="flex-grow-0 flex-shrink-0 bg-gray-400 min-h-" slot-scope="{menuVisible,toggleMenu}">
        <div class="max-w-screen-lg px-5 mx-auto flex justify-between items-center">
            <a href="{{ url('/') }}" class="block w-10 h-10 my-2">
                <img src="{{ asset('img/enso/enso-square-logo.png') }}" alt="{{ config('app.name') }}">
            </a>
            <div class="flex items-center">
                @if ($menu)
                    @if(!$is_burger)
                        @foreach ($menu->items as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target_str }}" rel="{{ $item->rel }}" class="block p-2 hidden md:block ml-10">
                            {{ $item->label }}
                            </a>
                        @endforeach
                    @endif
                    <button class="w-6 h-6 my-2 ml-5 {{ $is_burger ? '' : 'md:hidden' }}" @click="toggleMenu">
                        <span class="h-1 mb-1 bg-white block" aria-hidden="true"></span>
                        <span class="h-1 mb-1 bg-white block" aria-hidden="true"></span>
                        <span class="h-1 bg-white block" aria-hidden="true"></span>
                        <span class="sr-only">Menu</span>
                    </button>
                @endif
            </div>
        </div>

        <div :class="{hidden: !menuVisible}" class="absolute top-0 left-0 w-full h-full bg-gray-400">
        <button type="button" @click="toggleMenu" class="absolute top-0 right-0 p-10 text-lg leading-none">
            ✖ <span class="sr-only">Close Menu</span>
        </button>
            <div class="h-full flex flex-col items-center justify-center">
                @if ($menu)
                    @foreach ($menu->items as $item)
                        <a href="{{ $item->url }}" target="{{ $item->target_str }}" rel="{{ $item->rel }}" class="block text-lg my-3">
                            {{ $item->label }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</main-menu>
