@extends('Students.layouts.app')

@section('content')
<link href="{{ URL::to('/') }}/css/jquery-ui.css" rel="stylesheet">
     <script src="{{ URL::to('/') }}/js/jquery-ui.js"></script>
    <meta name="_token" content="{!! csrf_token() !!}" />
        <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default" style="margin-top: 50px;">
                <div class="panel-heading" style="background-color: #3cb371; color:#fff" >Normalized Chart</div>
                <div class="panel-body">

            
                 <div style="display: inline;float: left;" id="stock-div"></div>

                 <div style="display: inline;float: left" id=grades-div></div>

                    {!! \Lava::render('ColumnChart', 'Stocks', 'stock-div') !!}

                    {!! \Lava::render('LineChart', 'Grades', 'grades-div') !!}

        <div id="sliders" style="margin-top: 30%;">
        <div id="slider-range-1" style="width: 250px; margin-left: 50px;" ></div>
        <b><p id="amount-1" class="amount"></p></b>
        
        <div id="slider-range-2" style="width: 250px; margin-left: 50px;" ></div>
        <b><p id="amount-2" class="amount"></p></b>
        
        <div id="slider-range-3" style="width: 250px; margin-left: 50px;" ></div> 
        <b><p id="amount-3" class="amount"></p></b>
        
        <input type="hidden" id="aplus">
        <input type="hidden" id="a">
        <input type="hidden" id="bplus">
        <input type="hidden" id="b">
        <input type="hidden" id="cplus">
        <input type="hidden" id="c">
        <input type="hidden" id="d">      
        </div>
                </div>

            </div>
        </div>
    </div>

        
    </body>
    <script type="text/javascript">
        
    $(function() {
        $( "#slider-range-1" ).slider({
              range: true,
              min: 40,
              max: 102,
              values: [ {!! $a !!}, {!! $aplus !!} ],
              slide: function( event, ui ) {
                $( "#amount-1" ).html( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                $( "#a" ).val(ui.values[ 0 ]);
                $( "#aplus" ).val(ui.values[ 1 ]);

                var url = "/StudentGradingSystem/public/normalize";

                $.ajaxSetup({
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
                });

                var formData = {
                        a : ui.values[0],
                        aplus : ui.values[1],
                        bplus : {!! $bplus !!},
                        b : {!! $b !!},
                        cplus : {!! $cplus !!},
                        c : {!! $c !!},
                        d : {!! $d !!},
                }

        var type = "POST"; 

        console.log(formData);

        $.ajax({

            type: 'POST',
            url: url,
            data: formData,
            success: function (data) {
                console.log((data));
                lava.loadData('Stocks', data, function (chart) {
                console.log(chart);
            });
                lava.loadData('Grades', data, function (chart) {
                console.log(chart);
                });
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
              }
        });

        $( "#amount-1" ).html($( "#slider-range-1" ).slider( "values", 0 ) +
     " - " + $( "#slider-range-1" ).slider( "values", 1 ) );

        $( "#slider-range-2" ).slider({
              range: true,
              min: 40,
              max: 100,
              values: [ {!! $b!!}, {!! $bplus !!} ],
              slide: function( event, ui ) {
                $( "#amount-2" ).html(ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                $( "#b" ).val(ui.values[ 0 ]);
                $( "#bplus" ).val(ui.values[ 1 ]);
            
                var url = "/StudentGradingSystem/public/normalize";

                $.ajaxSetup({
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
                });

                var formData = {
                        a : {!! $a !!},
                        aplus : {!! $aplus !!},
                        bplus : ui.values[1],
                        b : ui.values[0],
                        cplus : {!! $cplus !!},
                        c : {!! $c !!},
                        d : {!! $d !!},
                }

        var type = "POST"; 

        console.log(formData);

        $.ajax({

            type: 'POST',
            url: url,
            data: formData,
            success: function (data) {
                console.log((data));
                lava.loadData('Stocks', data, function (chart) {
                console.log(chart);
            });
                    lava.loadData('Grades', data, function (chart) {
                console.log(chart);
                });

            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
              }
        });
        
        $( "#amount-2" ).html( $( "#slider-range-2" ).slider( "values", 0 ) +
     " - " + $( "#slider-range-2" ).slider( "values", 1 ) );

        $( "#slider-range-3" ).slider({
              range: true,
              min: 40,
              max: 100,
              values: [ {!! $c !!}, {!! $cplus !!} ],
              slide: function( event, ui ) {
                $( "#amount-3" ).html( ui.values[ 0 ] + " - " + ui.values[ 1 ] );
                $( "#c" ).val(ui.values[ 0 ]);
                $( "#cplus" ).val(ui.values[ 1 ]);

                var url = "/StudentGradingSystem/public/normalize";

                $.ajaxSetup({
                 headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                 }
                });

                var formData = {
                        a : {!! $aplus !!},
                        aplus : {!! $a !!},
                        bplus : {!! $bplus !!},
                        b : {!! $b !!},
                        cplus : ui.values[1],
                        c : ui.values[0],
                        d : {!! $d !!},
                }

        var type = "POST"; 

        console.log(formData);

        $.ajax({

            type: 'POST',
            url: url,
            data: formData,
            success: function (data) {
                console.log((data));
                lava.loadData('Stocks', data, function (chart) {
                console.log(chart);
            });
                lava.loadData('Grades', data, function (chart) {
                console.log(chart);
                });
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });

              }
        });
        
        $( "#amount-3" ).html($( "#slider-range-3" ).slider( "values", 0 ) +
     " - " + $( "#slider-range-3" ).slider( "values", 1 ) );
    });
 </script>
@endsection