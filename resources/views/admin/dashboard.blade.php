<x-admin.layout route="admin.dashboard">

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.visitors') }} {{ Str::lower(__('global.last_x_months', ['x' => 12])) }}</h4>
                    
                    <div id="visitors-by-month" class="chart"></div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.visitors_by_country') }}</h4>
                    
                    <div id="visitors-by-country" class="chart"></div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        window.onload = function () {
            // visitors by month
            new Morris.Line( {
                element: 'visitors-by-month',
                resize: true,
                data: [
                    @foreach($visitors['monthly'] as $item)
                    {
                        month:   '{{ \Carbon\Carbon::createFromFormat('Y-m', $item['month'])->translatedFormat('M Y') }}',
                        visitors: {{ $item['visitors'] }}
                    },
                    @endforeach
                ],
                parseTime: false,
                xkey: 'month',
                ykeys: [ 'visitors' ],
                labels: [ '{{ __('global.visitors') }}' ],
                lineColors: [ '#4680ff' ],
                lineWidth: 1,
                hideHover: 'auto'
            } );

            // visitors by country
            Morris.Bar( {
                element: 'visitors-by-country',
                data: [
                    @foreach($visitors['by_country']['total'] as $key => $item)
                    {
                        y: '{{ \Carbon\Language::regions()[ $item['country_code'] ] ?? $item['country_code'] }}',
                        a: {{ $item['visitors'] }},
                        b: {{ $visitors['by_country']['recent'][$key]['visitors'] ?? 0 }},
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