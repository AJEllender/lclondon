@php
    $menu = SiteMenu::get('footer-menu');
@endphp

<div class="flex-grow-0 flex-shrink-0 bg-gray-200 py-10">
    <div class="max-w-screen-lg px-5 mx-auto">
        <div class="sm:flex">
        <div class="sm:w-2/3">
            @if ($menu && $menu->items->isNotEmpty())
                @if (!empty($menu->title))
                    <p class="mb-5">{{ $menu->title }}</p>
                @endif
                @foreach ($menu->items as $item)
                    <p class="my-2">
                        <a href="{{ $item->url }}" target="{{ $item->target }}" class="text-gray-500 hover:text-gray-800">
                            {{ $item->label }}
                        </a>
                    </p>
                @endforeach
            @endif
        </div>
        <div class="sm:w-1/3 mt-5 sm:mt-0">
            <p class="mb-5">Social Media</p>
            @if (EnsoSettings::get('twitter_url'))
                <a href="{{ EnsoSettings::get('twitter_url') }}" class="inline-block mr-5 mb-5 text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 30 30"><path fill="currentColor" d="M30 6.2c-1 1.2-2 2.3-3.1 3.1v.8a17.4 17.4 0 01-3 9.7 15.6 15.6 0 01-8.4 6.8A18 18 0 010 24.8l1.5.1A12 12 0 009 22.3 6 6 0 015.5 21a6 6 0 01-2.1-3h1.1c.6 0 1.1 0 1.6-.2a6 6 0 01-3.5-2 6 6 0 01-1.4-4 6 6 0 002.8.7 6.1 6.1 0 01-2-8.2 17.4 17.4 0 0012.7 6.4l-.1-1.4a6 6 0 011.8-4.3 6 6 0 014.3-1.8 6 6 0 014.5 2 12 12 0 003.9-1.6 6 6 0 01-2.7 3.4c1.2-.1 2.4-.4 3.5-1z"/></svg>
                </a>
            @endif
            @if (EnsoSettings::get('facebook_url'))
                <a href="{{ EnsoSettings::get('facebook_url') }}" class="inline-block mr-5 mb-5 text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 30 30"><path fill="currentColor" d="M30 15a15 15 0 10-17.3 14.8V19.3H8.9V15h3.8v-3.3c0-3.8 2.2-5.8 5.6-5.8l3.4.2v3.7h-1.9c-1.9 0-2.4 1.2-2.4 2.4V15h4.1l-.7 4.3h-3.5v10.5A15 15 0 0030 15z"/></svg>
                </a>
            @endif
            @if (EnsoSettings::get('instagram_url'))
                <a href="{{ EnsoSettings::get('instagram_url') }}" class="inline-block mr-5 mb-5 text-gray-500 hover:text-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 30 30"><path fill="currentColor" d="M11.6 0H18.5a68 68 0 014.2.2l2 .5c1.6.6 3 1.7 3.9 3.1.5 1 .9 2 1.1 3l.2 2.2v1.6l.1.2v8.4a49.4 49.4 0 01-.2 3.5 10 10 0 01-.5 2 7.6 7.6 0 01-5.6 4.9l-2.4.3h-2l-.1.1H9.3a37.5 37.5 0 01-2.6-.3c-.8-.2-1.5-.4-2.2-.8a7.6 7.6 0 01-3.6-4c-.5-1-.7-2-.8-3v-1.7-1.5l-.1-.3v-6.8-.6a1333 1333 0 01.2-3.5l.4-1.9a7.6 7.6 0 014-4.6C5.9.5 7 .2 8.2.1h3.3l.2-.1zM2.7 15v3.2a50 50 0 00.4 5.1 5.2 5.2 0 003.4 3.5c.7.3 1.5.4 2.2.4a36.3 36.3 0 003.7.1h5.8a62.2 62.2 0 004-.2c.6 0 1.1-.2 1.6-.4 1.2-.5 2.1-1.3 2.7-2.4.4-.8.6-1.6.7-2.4v-1-2l.1-2v-4.5-1.2V9L27 7.7c0-.6-.2-1-.4-1.6-.5-1.2-1.3-2-2.4-2.6-.8-.4-1.6-.6-2.4-.6l-1-.1h-8a74 74 0 00-3.4 0H8l-1.4.4a5 5 0 00-3.4 3.3l-.4 2.2a37.8 37.8 0 000 3.3v3zM15 22.7a7.7 7.7 0 110-15.4 7.7 7.7 0 010 15.4zm5-7.7a5 5 0 10-10 0 5 5 0 0010 0zm1.2-8a1.8 1.8 0 103.6 0 1.8 1.8 0 00-3.6 0z"/></svg>
                </a>
            @endif
        </div>
        </div>
    </div>
</div>
