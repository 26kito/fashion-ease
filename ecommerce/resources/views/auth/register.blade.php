@extends('layouts.app')

@section('title'){{ $title }}@endsection

@section('content')
<!-- Modal -->
<div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Terms & Conditions</h5>
            </div>
            <div class="modal-body">
                <p>Lorem ipsum dolor sit amet.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cumque suscipit nisi necessitatibus nemo ipsam soluta autem vel ad perspiciatis temporibus.</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum rerum numquam deserunt culpa pariatur distinctio delectus vitae tempora debitis ducimus cum at placeat repellendus fugiat perferendis, beatae nihil quisquam itaque non! Earum magni necessitatibus officiis beatae dignissimos delectus quidem, quisquam dolorem laboriosam dolor, est autem debitis aut esse ipsum inventore quibusdam ex velit odit eos error? Quam ab accusamus beatae laborum odit quis reiciendis voluptate, sint dicta harum eos? Sunt quae dolores quasi quidem repellat. Et praesentium at provident eum harum voluptatum, ipsam dolores fugiat. Expedita dignissimos ducimus porro labore corrupti, delectus minima vitae cum magnam, iste excepturi aliquam aspernatur.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Vero quia modi obcaecati illum illo quo harum aperiam. Quasi corrupti odio perspiciatis voluptates similique accusantium possimus libero iure quas itaque, cupiditate neque quia ut. In ex, culpa aperiam odio ratione repellat natus sunt tempore! Quibusdam eum laudantium recusandae nulla, illum perferendis soluta provident vel, adipisci, omnis nam non aut dolorem tempore at rem consequuntur ea odio fugit a amet? Harum similique eius obcaecati ullam amet? Quam, veritatis obcaecati, itaque, iure dolore a quaerat sed id nihil delectus iste explicabo temporibus. Voluptatibus natus veniam quasi deleniti nam. Quaerat quas suscipit rerum. Dolorem pariatur dolor culpa porro facere itaque sunt? Iure et sequi commodi pariatur, illum, accusantium minus veritatis officiis est quam officia.</p>
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ex a culpa ipsam ut iusto beatae tempora pariatur ab placeat recusandae, architecto dolorum aliquam aut maiores dicta illum omnis natus eaque ratione deleniti possimus voluptate! Fuga natus veniam tempora odit nesciunt molestias debitis sapiente. Ratione harum iste quia rerum, vel quos ab iusto eos, error dicta at est? Fugit, tempore. Quibusdam maxime tempore quas molestias consequuntur sapiente unde, obcaecati est facere. Minima nobis aperiam temporibus deleniti? Tenetur repellendus saepe possimus laboriosam est fugit rerum nemo quasi eaque. Quis ipsum labore rerum reprehenderit dolorem laborum et debitis itaque. Nesciunt laborum facilis, modi tempora illum unde sint fuga asperiores. Exercitationem nesciunt earum quod iusto odio vero ratione vel error in voluptates, consequuntur ducimus neque ad atque inventore sequi odit hic provident facere! Beatae, illum autem? Mollitia porro, architecto ipsam, veritatis maiores nisi accusamus sint, voluptatem vitae cupiditate quos! Amet inventore culpa doloremque debitis at consequatur non molestias asperiores, vitae itaque placeat magnam praesentium aliquid sapiente. Repellat beatae ad dolore, ex possimus ipsum unde, suscipit incidunt placeat asperiores similique tenetur magni doloribus consequatur, nihil nemo accusamus sunt voluptatum esse eum perspiciatis atque iste? Voluptatem autem animi vitae, sit ipsum quos illo sint provident aspernatur, error enim. Saepe, quibusdam facere? Vero repudiandae necessitatibus labore incidunt corporis rerum dolore minus tempora tempore nemo earum impedit, officia eos. Necessitatibus repellendus eaque hic est nihil sunt nesciunt ducimus sequi unde, cumque natus incidunt qui fugit, ut sapiente inventore magni cum assumenda ad culpa corporis. Necessitatibus rerum eveniet sequi ipsa maiores deserunt temporibus impedit sit non iusto consequuntur totam quam quasi, praesentium aliquam asperiores. Nihil explicabo quod repellat magnam voluptas sint, eaque error veniam enim illum, consectetur, recusandae unde iusto incidunt. Et voluptatibus unde autem aut beatae itaque repellendus adipisci sapiente qui laborum, odio molestias, possimus at in enim?</p>
            </div>
        </div>
    </div>
</div>
<div class="register-box">
    <div class="register-logo">
        <a><b>E-</b>Commerce</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">{{ __('Register') }}</p>

            <form method="POST" action="{{ route('postregister') }}">
                @csrf

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                        placeholder="First Name" name="firstname" value="{{ old('firstname') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    @error('firstname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="input-group mb-3">
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                        placeholder="Last Name" name="lastname" value="{{ old('lastname') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>

                    @error('lastname')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="form-group">
                    <p class="form-check form-check-inline">Gender</p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="male" value="M">
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="gender" id="female" value="F">
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" autocomplete="email" placeholder="E-Mail Address">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>

                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="input-group mb-3">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" autocomplete="new-password" placeholder="Password"
                        onkeypress="return noSpaces()">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <a class="mr-2" id="seePassword" role="button">
                                <img src="{{ asset('asset/img/hide.png') }}" width="20" height="20"
                                    id="seePasswordIcon">
                            </a>
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                            <label for="agreeTerms">
                                I agree to the <a href="#" class="terms text-decoration-none" data-bs-toggle="modal" data-bs-target="#exampleModalLong">terms</a>
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

            <a href="{{ route('login') }}" class="text-center text-decoration-none">I already have a account</a>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    function noSpaces() {
            if (event.keyCode == 32) {
                event.returnValue = false;
                return false;
            }
        }

    // $(document).on('click', '.terms', () => {
    //     $('#exampleModalLong').modal('show');
    //     // $('#exampleModalLong').modal('show', {backdrop: 'static', keyboard: false});
    // })

    $('#seePassword').on('click', () => {
        if ($('#password').attr('type') == 'password') {
            $('#password').attr('type', 'text');
            $('#seePasswordIcon').prop('src', '{{ asset('asset/img/show.png') }}');
        } else {
            $('#password').attr('type', 'password');
            $('#seePasswordIcon').prop('src', '{{ asset('asset/img/hide.png') }}');
        }
    })
</script>
@endpush