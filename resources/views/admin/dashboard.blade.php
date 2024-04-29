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
                    <h4 class="card-title">{{ __('global.visitors_by_country') }} ({{ __('global.total') }})</h4>

                    <div id="total-visitors-by-country" class="chart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.top_x_read_poems', ['x' => 10]) }}</h4>
                    
                    <div id="poems-reads" class="chart"></div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ __('global.top_x_liked_poems', ['x' => 10]) }}</h4>
                    
                    <div id="poems-likes" class="chart"></div>
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
                lineColors: [ '#425795' ],
                lineWidth: 1,
                hideHover: 'auto'
            } );

            // total visitors (grouped by country)
            Morris.Donut({
                element: 'total-visitors-by-country',
                data: [
                    @foreach($visitors['by_country'] as $key => $item)
                    {
                        label: '{{ \Carbon\Language::regions()[ $item['country_code'] ] ?? $item['country_code'] }}',
                        value: {{ $item['visitors'] }},
                    },
                    @endforeach
                ],
                colors: [ 'green', 'red', 'blue', 'purple', 'orange' ]
            });

            // poems reads
            Morris.Bar( {
                element: 'poems-reads',
                data: [
                    @foreach($poems['reads'] as $stats)
                    {
                        y: '{{ $stats->statsable->title }}',
                        a: {{ $stats->total_impressions }},
                    },
                    @endforeach
                ],
                xkey: 'y',
                ykeys: [ 'a', ],
                labels: [ '{{ __('global.reads') }}', ],
                barColors: [ '#425795' ],
                hideHover: 'auto',
            } );

            // poems likes
            Morris.Bar( {
                element: 'poems-likes',
                data: [
                    @foreach($poems['likes'] as $stats)
                    {
                        y: '{{ $stats->statsable->title }}',
                        a: {{ $stats->total_likes }},
                    },
                    @endforeach
                ],
                xkey: 'y',
                ykeys: [ 'a', ],
                labels: [ '{{ __('global.likes') }}', ],
                barColors: [ 'purple' ],
                hideHover: 'auto',
            } );
        };
    </script>

</x-admin.layout>