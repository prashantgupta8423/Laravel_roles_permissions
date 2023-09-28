@extends('layout')
@section('content')
    <div class="card push-top mt-5">
        <div class="card-header">
            Update Student
            <a href="{{ route('students.index') }}" class="btn btn-danger btn-sm float-right">Back</a>
        </div>
        <div class="card-body">
            <form method="post" action="{{ route('students.update', $student->student_id) }}" id="studentForm"
                enctype="multipart/form-data">
                <div class="form-group">
                    @csrf
                    @method('PATCH')
                    <label for="name">Name</label>
                    <input type="text" class="form-control" name="name" value="{{ $student->name }}" />
                    @error('name')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" value="{{ $student->email }}" />
                    @error('email')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" class="form-control" name="phone" value="{{ $student->phone }}" />
                    @error('phone')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type=" " class="form-control" name="password" value="{{ $student->password }}" />
                    @error('password')
                        <span class="text-danger">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="phone">Image</label>
                    <input type="file" name="image" class="image" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="image_base64">
                    <img src="{{ asset('/Images/' .  $student->photo) }}" style="width: 200px;" class="show-image">
                    @error('image_base64')
                    <span class="text-danger">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                </div>
                <button type="submit" class="btn btn-success">Update Student</button>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Laravel Crop Image Before Upload Example - ItSolutionStuff.com
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-md-8">
                                <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                            </div>
                            <div class="col-md-4">
                                <div class="preview"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="crop">Crop</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function() {
            $(function() {
                $("#studentForm").validate({
                    // Specify the validation rules
                    rules: {
                        name: "required",
                        phone: {
                            required: true,
                            minlength: 10,
                            maxlength: 12
                        },
                        password:{
                            required:true,
                        }
                        email: {
                            required: true,
                            email: true
                        },
                    },
                    messages: {
                        name: "Name field is required.",
                        phone: {
                            required: "Phone field is required.",
                            minlength: "Your Phone must be at least 10 characters long.",
                            maxlength: "Your Phone must be less than 12 characters."
                        },
                        password:{
                            required: "Password field is required.",
                        },
                        email: "Email field is required."
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                });
            });
        })
    </script>
    <script>
        var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;

        /*------------------------------------------
        --------------------------------------------
        Image Change Event
        --------------------------------------------
        --------------------------------------------*/
        $("body").on("change", ".image", function(e) {
            var files = e.target.files;
            var done = function(url) {
                image.src = url;
                $modal.modal('show');
            };

            var reader;
            var file;
            var url;

            if (files && files.length > 0) {
                file = files[0];

                if (URL) {
                    done(URL.createObjectURL(file));
                } else if (FileReader) {
                    reader = new FileReader();
                    reader.onload = function(e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(file);
                }
            }
        });

        /*------------------------------------------
        --------------------------------------------
        Show Model Event
        --------------------------------------------
        --------------------------------------------*/
        $modal.on('shown.bs.modal', function() {
            cropper = new Cropper(image, {
                aspectRatio: 1,
                viewMode: 3,
                preview: '.preview'
            });
        }).on('hidden.bs.modal', function() {
            cropper.destroy();
            cropper = null;
        });

        /*------------------------------------------
        --------------------------------------------
        Crop Button Click Event
        --------------------------------------------
        --------------------------------------------*/
        $("#crop").click(function() {
            canvas = cropper.getCroppedCanvas({
                width: 160,
                height: 160,
            });

            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                reader.readAsDataURL(blob);
                reader.onloadend = function() {
                    var base64data = reader.result;
                    $("input[name='image_base64']").val(base64data);
                    $(".show-image").show();
                    $(".show-image").attr("src", base64data);
                    $("#modal").modal('toggle');
                }
            });
        });
    </script>
@endsection
