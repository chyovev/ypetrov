<x-admin.layout route="admin.dashboard">

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.visitors_by_country') }}</h4>
                    
                    <div id="visitors-by-country"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function () {
            Morris.Bar( {
                element: 'visitors-by-country',
                data: [
                    @foreach($visitors['total'] as $key => $item)
                    {
                        y: '{{ $item['country_code'] }}',
                        a: {{ $item['visitors'] }},
                        b: {{ $visitors['recent'][$key]['visitors'] ?? 0 }},
                    },
                    @endforeach
                ],
                xkey: 'y',
                ykeys: [ 'a', 'b', ],
                labels: [ '{{ __('global.total') }}', '{{ __('global.last_x_months', ['x' => 6]) }}', ],
                barColors: [ '#1976d2', '#95b8ee', ],
                hideHover: 'auto',
            } );
        };
    </script>

</x-admin.layout>