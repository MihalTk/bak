@extends('layouts.app')

@section('content')

<section class="training-section">
    <div class="container">
        <div class="row">
            <div>
                <form method="POST" action="{{ route('next') }}">
                    @csrf
                    <h2 class="text-black mb-4">Your next training is {{ $name }}</h2>
                    <input name='training-name' value='{{ $name }}' hidden>
                    <div class="save">
                        <button name='submit' value="save" class="btn custom-btn-bg-success d-lg-block">Save training</button>
                    </div>

                    @if( $ex != 'none' )
                    @foreach ($final as $exercise)
                    <i class="btn collapsible">{{ $exercise[0] }}</i>

                    <div class="content">
                        <div class='delete'>
                            <button name='submit' value="delete-{{$exercise[1]['idExercise']}}" type="submit"
                                class="btn custom-btn-bg-danger d-lg-block">Delete exercise</button>
                        </div>
                        <table class="table table-hover">
                            <tr>
                                <th>Set</th>
                                <th>Weight</th>
                                <th>Repetitions</th>
                                <th></th>
                            </tr>
                            @for($i = 1; $i < count($exercise)+1; $i++) @if($i<count($exercise)) <tr>
                                <td>{{ $exercise[$i]['sets'] }}</td>
                                <td><input id="weight"
                                        name='weight-{{ $exercise[$i]['idExercise'] }}-{{ $exercise[$i]['sets'] }}'
                                        type="number" class="form-control @error('wight') is-invalid @enderror"
                                        name="age" value="{{ $exercise[$i]['weight'] }}" min="0.00" max="1000"
                                        step=".01" style="max-width: 100px" required></td>
                                <td><input id="reps"
                                        name='reps-{{ $exercise[$i]['idExercise'] }}-{{ $exercise[$i]['sets'] }}'
                                        type="number" class="form-control @error('wight') is-invalid @enderror"
                                        name="age" value="{{ $exercise[$i]['reps'] }}" min="0" max="1000"
                                        style="max-width: 100px" required>
                                </td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" />
                                    </div>
                                </td>
                                </tr>
                                @else
                        </table>
                        <div class='button-group'>
                            <button name='submit' value="addset-{{$exercise[1]['idExercise']}}" type="submit"
                                class="btn custom-btn-bg-alert d-lg-block">Add set</button>
                            <button name='submit' value="deleteset-{{$exercise[1]['idExercise']}}" type="submit"
                                class="btn custom-btn-bg-alert d-lg-block">Delete set</button>
                        </div>
                    </div>
                    @endif
                    @endfor

                    @endforeach
                    @endif

                    @if( $cardio[0]['is_cardio'] != 'no' )
                    <h3 class="text-black mb-4" style='margin-top: 10px'>Cardio:</h3>
                    <i class="btn collapsible" style='margin-bottom: 10px'>{{ $cardio[0]['name'] }}</i>
                    <div class="content">
                        <div class="cardio">
                            <div class='s'>
                                <input id="cardio" name='cardio-{{$cardio[0]['id']}}' type="number"
                                    style='max-width:70px' class="form-control @error('wight') is-invalid @enderror"
                                    name="age" value="{{$cardio[0]['min']}}" min="0.00" max="1000" step=".01" required>
                                <h5 style='margin-bottom: 0px; padding-right: 20px'>minutes</h5>
                            </div>
                            <div>
                                <button name='submit' value="cardiodelete" type="submit"
                                    class="btn custom-btn d-lg-block">Delete cardio</button>
                            </div>
                        </div>
                    </div>
                    @else
                    <h3 class="text-black mb-4" style="margin-top: 20px">Cardio:</h3>

                    <div class='add'>
                        <select name="idcardio" id="idcardio" class="select form-control">
                            <option autofocus value="">Choose cardio</option>
                            @foreach($all_cardio as $one_cardio)
                            <option value="{{ $one_cardio[0] }}">{{ $one_cardio[1] }}</option>
                            @endforeach
                        </select>
                        <button name='submit' value="cardioadd" type="submit" class="btn custom-btn d-lg-block" style="width:126.13px">Add
                            cardio</button>
                    </div>
            </div>
            @endif

            <div class='container' style='margin-top: 50px'>
                <div class='add'>
                    <select name="part" id="part" class="select form-control">
                        <option autofocus value="">Choose part you want to exericse</option>
                        <option value="abductors">Abductors</option>
                        <option value="abs">Abs</option>
                        <option value="adductors">Adductors</option>
                        <option value="back">Back</option>
                        <option value="biceps">Biceps</option>
                        <option value="calfs">Calfs</option>
                        <option value="forearms">Forearms</option>
                        <option value="glutes">Glutes</option>
                        <option value="hamstrings">Hamstrings</option>
                        <option value="chest">Chest</option>
                        <option value="lower back">Lower back</option>
                        <option value="quads">Quads</option>
                        <option value="shoulders">Shoulders</option>
                        <option value="traps">Traps</option>
                        <option value="triceps">Triceps</option>
                        <option value="lats">Lats</option>
                    </select>
                    <button name='submit' value="exerciseaddpart" type="submit" class="btn custom-btn d-lg-block">Add
                        exercise</button>
                </div>

                <div class='add'>
                    <select name="exerciseid" id="exerciseid" class="select form-control">
                        <option autofocus value="">Choose exercise you want to exericse</option>
                        @foreach($allexercises as $allexercise)
                        <option value="{{ $allexercise->id }}">{{ $allexercise->name }}</option>
                        @endforeach
                    </select>
                    <button name='submit' value="exerciseaddid" type="submit" class="btn custom-btn d-lg-block">Add
                        exercise</button>
                </div>
            </div>

            </form>
        </div>
</section>

@endsection