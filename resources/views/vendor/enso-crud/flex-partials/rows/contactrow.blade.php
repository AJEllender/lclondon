@php
  /**
   * $row_data consists of:
   *   ->row_id - string
   *   ->row_label - string
   *   ->row_type - string
   *   ->location - \App\Utilities\Location
   *   ->title - string
   */
  $row_data = $row->unpack();
@endphp

<div
    data-label="{{ $row_data->row_label }}"
    id="{{  $row_data->row_id }}"
    class="w-full my-12 sm:my-16 md:my-20 lg:my-24 px-6 sm:px-12"
>
    <div class="w-full md:max-w-5xl m-auto flex flex-col-reverse md:flex-row">
        <div class="shrink-0 grow-0 basis-1/2 bg-white p-8 flex flex-col">
            @if (!empty($row_data->title))
                @component('enso-crud::flex-partials.components.title', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10'
                ])
                    {{ $row_data->title }}
                @endcomponent
            @endif

            @if (!empty($row_data->location->getAddress()))
                @component('enso-crud::flex-partials.components.content', [
                    'class' => 'mb-4 md:mb-6 lg:mb-10 font-bold'
                ])
                    {!! nl2br($row_data->location->getAddress()) !!}
                @endcomponent
            @endif

            @if (!empty($row_data->location->getEmail()))
                @component('enso-crud::flex-partials.components.content', [
                    'class' => 'mb-4'
                ])
                    Email: <a
                        href="mailto:{{ $row_data->location->getEmail() }}"
                        class="text-blue-500 hover:text-blue-700 font-bold"
                        rel="noopener noreferrer"
                        target="_blank"
                    >
                        {{ $row_data->location->getEmail() }}
                    </a>
                @endcomponent
            @endif

            @if (!empty($row_data->location->getPhone()))
                @component('enso-crud::flex-partials.components.content', [
                    'class' => 'mb-4'
                ])
                    Tel: <a
                        href="tel:{{ $row_data->location->getPhone() }}"
                        class="text-blue-500 hover:text-blue-700 font-bold"
                        rel="noopener noreferrer"
                        target="_blank"
                    >
                        {{ $row_data->location->getPhone() }}
                    </a>
                @endcomponent
            @endif

            @if (!empty($row_data->location->getDirectionsUrl()))
                @component('enso-crud::flex-partials.components.button', [
                    'url' => $row_data->location->getDirectionsUrl(),
                    'rel' => 'noopener noreferrer',
                    'target' => '_blank',
                    'hover' => 'Get directions from googlemaps',
                    'type' => 'secondary',
                    'class' => 'mt-auto',
                ])
                    Get Directions
                @endcomponent
            @endif
        </div>
        <div class="shrink-0 grow-0 basis-1/2">
            <div class="contact-map w-full aspect-w-1 aspect-h-1"></div>
        </div>
    </div>
</div>

@once
    @push('pre-scripts')
        <script>
            var map;
            var marker;
            var latLng = {
                lat: {{ $row_data->location->getLatitude() }},
                lng: {{ $row_data->location->getLongitude() }},
            };

            function initMap() {
                if (!google || !google.maps) {
                    return;
                }

                document.getElementsByClassName('contact-map').forEach(function (element) {
                    map = new google.maps.Map(element, {
                        center: latLng,
                        zoom: 15,
                    });

                    marker = new google.maps.Marker({
                        position: latLng,
                        map: map,
                    });
                });
            }
        </script>
    @endpush

    @push('pre-scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key') }}&callback=initMap" async defer></script>
    @endpush
@endonce



