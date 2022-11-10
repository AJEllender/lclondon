@php
    $menu = SiteMenu::get('main-menu');
    $is_burger = $menu && $menu->items->count() > $menu->max_visible_items;
@endphp

<main-menu>
    <div class="fixed w-full top-0 h-14 bg-blue-400 flex z-30 drop-shadow-lg" slot-scope="{menuVisible,toggleMenu}">
        <div class="max-w-screen-lg px-5 xl:px-0 mx-auto flex justify-between items-center grow">
            <a href="{{ url('/') }}" class="block text-xl font-title font-bold">
                {{ \Yadda\Enso\Settings\Facades\EnsoSettings::get('site-name', Config::get('app.name')) }}
            </a>
            <div class="flex items-center">
                @if ($menu)
                    @if(!$is_burger)
                        @foreach ($menu->items as $item)
                            <a href="{{ $item->url }}" target="{{ $item->target_str }}" rel="{{ $item->rel }}" class="font-title block p-2 hidden md:block ml-10">
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

        <div :class="{hidden: !menuVisible}" class="absolute top-0 left-0 w-screen h-screen bg-gray-400">
            <button type="button" @click="toggleMenu" class="absolute top-0 right-0 p-10 text-lg leading-none z-20">
                ✖ <span class="sr-only">Close Menu</span>
            </button>
            <div class="relative h-full flex flex-col items-center justify-center z-10">
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
