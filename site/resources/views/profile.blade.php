@extends('layouts.app')

@section('content')

<section class="profile-section">
    <div class="container">
        <div class="container">
            <h2>
                {{ Auth::user()->username }}
            </h2>
        </div>

        @if( $name != "[null]")
        <div class="con">
            <div class='profile-text'>
                <h3 id="name_">{{ Auth::user()->name }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-name"
                    actual_value="Name Surname"><a>Change name</a></button>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#delete-name">
                    <a>Delete name</a>
                </button>
            </div>
        </div>
        @else
        <div class="con">
            <div class='profile-text'>
                <h3 id="name_">You don't have a name</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#add-name">
                    <a>Add name</a>
                </button>
            </div>
        </div>
        @endif

        <div class="con">
            <div class='profile-text'>
                <h3>{{ Auth::user()->email }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-email"
                    actual_value="email_now"><a>Change email</a></button>
            </div>
        </div>
        @error('email')
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="btn custom-btn" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @enderror

        <div class="con">
            <div class='profile-text'>
                <h3>Your weight: {{ $weight }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-weight"
                    actual_value="weight_now"><a>Change weight</a></button>
            </div>
        </div>

        <div class="con">
            <div class='profile-text'>
                <h3>Your height: {{ $height }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-height"
                    actual_value="height_now">
                    <a>Change height</a>
                </button>
            </div>
        </div>

        <div class="con">
            <div class='profile-text'>
                <h3>{{ $days }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-days"
                    actual_value="value_now"><a>Change days</a></button>
            </div>
        </div>

        <div class="con">
            <div class='profile-text'>
                <h3>{{ $experience }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-experience"
                    actual_value="value_now"><a>Change experience</a></button>
            </div>
        </div>

        <div class="con">
            <div class='profile-text'>
                <h3>Equipments</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-equipments"
                    actual_value="value_now"><a>Change equipments</a></button>
            </div>
        </div>

        <div class="con">
            <div class='profile-text'>
                <h3>{{ $goal->name }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-goal"
                    actual_value="value_now"><a>Change goal</a></button>
            </div>
        </div>

        @if( $ind_goal->ind_goal != "")
        <div class="con">
            <div class='profile-text'>
                <h3>{{ $ind_goal->ind_goal }}</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#change-indgoal"
                    actual_value="Name Surname"><a>Change goal</a></button>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#delete-indgoal">
                    <a>Delete goal</a>
                </button>
            </div>
        </div>
        @else
        <div class="con">
            <div class='profile-text'>
                <h3 id="name_">You don't have a individual goal</h3>
            </div>
            <div class='buttons'>
                <button class="btn custom-profile-btn" data-bs-toggle="modal" data-bs-target="#add-indgoal">
                    <a>Add individual goal</a>
                </button>
            </div>
        </div>
        @endif


    </div>
</section>


<div class="modal fade" id="change-email" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Email change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-email') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old email: {{ Auth::user()->email }}</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <input type="email" class="form-control validate" name="email" id="email"
                            pattern="[^ @]*@[^ @]*" placeholder="Email address" autocomplate="email" required></input>
                    </div>
                    <button type="button" id=close-btn class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-height" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Height change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-height') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: {{ $height}} cm</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <input type="number" class="form-control validate" name="height" id="height" min="120"
                            step=".01" max="250" required></input>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-weight" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Weight change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-weight') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: {{ $weight}} kg</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <input type="number" class="form-control validate" name="weight" id="weight" min="50" step=".01"
                            max="500" required></input>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-days" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Days per week change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-days') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: {{ $days}} days per week</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <input type="number" class="form-control validate" name="days" id="days" min="1" max="6"
                            required style="margin-bottom:15px"></input>
                        <select name="idreason" id="reason" class="form-control" required>
                            <option autofocus value="">Choose your reason</option>
                            @foreach($reasons as $reason)
                            <option value="{{ $reason->id }}">{{ $reason->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-name" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Name change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-name') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old name: {{ Auth::user()->name }}</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" autocomplete="name" autofocus>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete-name" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="needs-validation" method="POST" action="{{ route('delete-name') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Do you want to delete your name?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="delete-indgoal" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="needs-validation" method="POST" action="{{ route('delete-indgoal') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <h6>Do you want to delete your individual goal?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="add-name" tabindex="-1" aria-labelledby="saveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add name change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-name') }}">
                    @csrf
                    <label for="new" class="col-form-label">New:</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        value="{{ old('name') }}" autocomplete="name" autofocus>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
        </form>
    </div>
</div>

<div class="modal fade" id="change-experience" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Experience change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-experience') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: {{ $experience }}</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <select name="idexperience" id="experience" class="form-control" required>
                            <option autofocus value="">Choose your reason</option>
                            @foreach($experiences as $experience)
                            <option value="{{ $experience->id }}">{{ $experience->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-equipments" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Experience change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-equipments') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: </label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <div class="col-md-8 col-lg-5 d-flex justify-content-center align-items-center">
                            <div class="d-flex text-left align-items-center w-100">
                                <select class="selectpicker" id="multiple-checkboxes" multiple data-live-search="true"
                                    name="equipments[]">
                                    @foreach($equipments as $equipment)
                                    <option value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="change-goal" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Goal change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('change-goal') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="old" class="col-form-label">Old: {{ $goal->name }}</label>
                    </div>
                    <div class="mb-3">
                        <label for="new" class="col-form-label">New:</label>
                        <select name="idgoal" id="goal" class="form-control" style="margin-bottom: 10px" required>
                            <option autofocus value="">Choose your reason</option>
                            @foreach($goals as $one_goal)
                            <option value="{{ $one_goal->id }}">{{ $one_goal->name }}</option>
                            @endforeach
                        </select>
                        <textarea name="ind-goal" rows="3" class="form-control" id="ind-goal"
                            placeholder="Individual goal" maxlength="200"></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-indgoal" tabindex="-1" aria-labelledby="change_modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Goal change</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" method="POST" action="{{ route('add-indgoal') }}">
                    @csrf
                    <div class="mb-3">
                        <textarea name="ind-goal" rows="3" class="form-control" id="ind-goal"
                            placeholder="Individual goal" maxlength="200" required></textarea>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection