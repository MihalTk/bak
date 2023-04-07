@extends('layouts.app')

@section('content')
<section class="register-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Register') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-3">
                                <label for="username"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Username') }}</label>

                                <div class="col-md-6">
                                    <input id="username" type="username"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username">

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="password-confirm"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="idgender"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Gender') }}</label>
                                <div class="col-md-6">
                                    <select name="idgender" id="idgender" class="form-control" required>
                                        <option autofocus value="">Choose your gender</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
                                        <option value="3">Don't want to share</option>
                                    </select>
                                    @error('idgender')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="age" class="col-md-4 col-form-label text-md-end">{{ __('Age') }}</label>
                                <div class="col-md-6">
                                    <input id="age" type="number"
                                        class="form-control @error('age') is-invalid @enderror" name="age"
                                        value="{{ old('age') }}" min="13" max="100" required autocomplete="age">

                                    @error('age')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="idexperience"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Experience') }}</label>
                                <div class="col-md-6">
                                    <select name="idexperience" id="idexperience" class="form-control" required>
                                        <option autofocus value="">Choose your experience</option>
                                        <option value="1">Beginner</option>
                                        <option value="3">Experienced</option>
                                        <option value="4">Expert</option>
                                    </select>
                                    @error('idexperience')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="days_per_week"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Days per week you want to exercise') }}</label>
                                <div class="col-md-6">
                                    <input id="days_per_week" type="number"
                                        class="form-control @error('days_per_week') is-invalid @enderror"
                                        name="days_per_week" value="{{ old('days_per_week') }}" min="1" max="6" required
                                        autocomplete="days_per_week">

                                    @error('days_per_week')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="height"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Height') }}</label>
                                <div class="col-md-6">
                                    <input id="height" type="number"
                                        class="form-control @error('height') is-invalid @enderror" name="height"
                                        value="{{ old('height') }}" min="120" step=".01" max="250" required
                                        autocomplete="height">

                                    @error('height')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="weight"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Weight') }}</label>
                                <div class="col-md-6">
                                    <input id="weight" type="number"
                                        class="form-control @error('weight') is-invalid @enderror" name="weight"
                                        value="{{ old('weight') }}" min="50" step=".01" max="500" required
                                        autocomplete="weight">

                                    @error('weight')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="weight"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Equipments') }}</label>
                                <div class="col-md-6">
                                    <select class="selectpicker" id="multiple-checkboxes" multiple
                                        data-live-search="true" name="equipments[]">
                                        <option value="barbell">Barbell</option>
                                        <option value="bars">Bars</option>
                                        <option value="bench">Bench</option>
                                        <option value="box">Box</option>
                                        <option value="cable">Cable</option>
                                        <option value="dumbbells">Dumbbells</option>
                                        <option value="EZ bar">EZ bar</option>
                                        <option value="hacken">Hacken</option>
                                        <option value="horizontal bar">Horizontal bar</option>
                                        <option value="machine">Machines</option>
                                        <option value="Smith machine">Smith machine</option>
                                        <option value="trapbar">Trapbar</option>
                                        <option value="weights">Weights</option>
                                        <option value="bench for hyperextension">Bench for hyperextension</option>
                                        <option value="Scott bench">Scott bench</option>
                                        <option value="Air bike">Air bike</option>
                                        <option value="Eliptical">Eliptical</option>
                                        <option value="Rowing machine">Rowing machine</option>
                                        <option value="Skierg">Skierg</option>
                                        <option value="Spin bike">Spin bike</option>
                                        <option value="Stepmill">Stepmill</option>
                                        <option value="Treadmill">Treadmill</option>
                                    </select>
                                </div>

                                @error('weight')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>


                            <div class="row mb-3">
                                <label for="idgoal" class="col-md-4 col-form-label text-md-end">{{ __('Goal') }}</label>
                                <div class="col-md-6">
                                    <select name="idgoal" id="idgoal" class="form-control" required>
                                        <option autofocus value="">Choose your goal</option>
                                        <option value="1">Bodybuilding</option>
                                        <option value="4">Loose weight</option>
                                        <option value="5">Athletic</option>
                                    </select>
                                    @error('idgoal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="ind-goal"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Individual goal') }}</label>
                                <div class="col-md-6">
                                    <textarea name="ind-goal" rows="3" class="form-control" id="ind-goal"
                                        placeholder="Individual goal" maxlength="200"></textarea>
                                    @error('ind-goal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn custom-btn-bg-success">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
        $(document).ready(function() {
            $('select').selectpicker();
        });
        </script>
    </div>
</section>
@endsection