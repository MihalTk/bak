@extends('layouts.app')

@section('content')



<section class="last-section">
    <div class="container">
        @if( $ex != 'none' )
        <h2 class="text-black mb-4">Your last training was {{ $name }}</h2>


        @foreach ($final as $exercise)
        <i class="btn collapsible">{{ $exercise[0] }}</i>

        <div class="content">

            <table class="table table-hover">
                <tr>
                    <th>Set</th>
                    <th>Weight</th>
                    <th>Repetitions</th>
                </tr>
                @for($i = 1; $i < count($exercise); $i++) <tr>
                    <td>{{ $exercise[$i]['sets'] }}</td>
                    <td>{{ $exercise[$i]['weight'] }}</td>
                    <td>{{ $exercise[$i]['reps'] }}</td>
                    </tr>
                    @endfor
            </table>
        </div>
        @endforeach
        @else
        <h2 class="text-black" style="margin-top: 30px">Here you will see your last training and graphs based on your
            trainings</h2>
        @endif

        @if ( $cardio[0]['name'] != 'none')

        <h3 class="text-black" style="margin-top: 20px">Cardio:</h3>
        <i class="btn collapsible">{{ $cardio[0]['name'] }} {{$cardio[0]['min']}} minutes</i>

        @endif
    </div>
</section>

<section class="graph-section" id="section_1">

    <h2 class="text-black" style="text-align: center">Monitoring</h2>


    <div class="container">
        <h3 class="text-black" style='margin: 20px'>Weight lifted per month in the last year </h3>
        {!! $chart_last_year->container() !!}
    </div>




    <div class="container">
        <h3 class="text-black" style='margin: 20px'>Weight lifted per day in the last month </h3>
        {!! $chart_last_month->container() !!}
    </div>


    <div class="container">
        <h3 class="text-black" style='margin: 20px'>Weight lifted per day in the last 6 months </h3>
        {!! $chart_last_six->container() !!}
    </div>

    <div class="container">
        <h3 class="text-black" style='margin: 20px'>Weight in last year</h3>
        {!! $chart_weight_year->container() !!}
    </div>

    <div class="container">
        <h3 class="text-black" style='margin: 20px'>Cardio in minutes in the last year</h3>
        {!! $chart_cardio->container() !!}
    </div>

    {!! $chart_last_year->script() !!}
    {!! $chart_last_month->script() !!}
    {!! $chart_last_six->script() !!}
    {!! $chart_weight_year->script() !!}
    {!! $chart_cardio->script() !!}


@endsection