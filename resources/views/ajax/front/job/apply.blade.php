<style>
    .modal-margin-left{
        margin : 3px;
    }
</style>
<div class="modal fade" id="applyNowModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Apply Here</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form method="post" action="{{ URL::to('apply-to-this-job') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="" id="job_id" name="job_id" />
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Name*</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Email*</label>
                            <input type="text" value="{{ old('email') }}" name="email" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Phone*</label>
                            <input type="text" value="{{ old('phone') }}" name="phone" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('phone'))
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Gender*</label>
                            <div class="col-md-12 d-flex">
                                <div class="form-check form-check-md modal-margin-left">
                                    <input class="form-check-input" type="radio" value="Male" name="gender" id="Radio-md">
                                    <label class="form-check-label" for="Radio-md">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check form-check-md modal-margin-left">
                                    <input class="form-check-input" type="radio" value="Female" name="gender" id="Radio-md">
                                    <label class="form-check-label" for="Radio-md">
                                        Female
                                    </label>
                                </div>
                                <div class="form-check form-check-md modal-margin-left">
                                    <input class="form-check-input" type="radio" value="Other" name="gender" id="Radio-md">
                                    <label class="form-check-label" for="Radio-md">
                                        Other
                                    </label>
                                </div>
                                <div class="form-check form-check-md modal-margin-left">
                                    <input class="form-check-input" type="radio" value="Prefer Not To Say" name="gender" id="Radio-md">
                                    <label class="form-check-label" for="Radio-md">
                                        Prefer Not To Say
                                    </label>
                                </div>
                            </div>
                            @if($errors->has('gender'))
                                <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @endif
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Nationality*</label>
                            <select name="nationality" class="form-select" aria-label="Default select example">
                                <option value="">--Select--</option>
                                @foreach ($nationalities as $row)
                                <option value="{{ $row }}">{{ $row }}</option>
                                @endforeach
                                
                            </select>
                            @if($errors->has('nationality'))
                                <span class="text-danger">{{ $errors->first('nationality') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Date of Birth*</label>
                            <input type="date" value="{{ old('date_of_birth') }}" name="date_of_birth" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('date_of_birth'))
                                <span class="text-danger">{{ $errors->first('date_of_birth') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Resume*</label>
                            <input type="file" name="resume" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('resume'))
                                <span class="text-danger">{{ $errors->first('resume') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Cover Letter (150 words)</label>
                            <textarea class="form-control" id="exampleFormControlTextarea1" name="cover_letter" rows="3">{{ old('cover_letter') }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="captcha col-md-6">
                            <span>{!! captcha_img('math') !!}</span>
                            <button type="button" class="btn btn-danger reload" id="reload">
                                &#x21bb;
                            </button>
                        </div>
                        <div class=" col-md-6">
                            <input name="captcha" type="text" class="form-control" placeholder="Enter Captcha">
                            @if ($errors->has('captcha'))
                                <span class="text-danger">{{ $errors->first('captcha') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('web/js/jquery.js') }}"></script>
@if($errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('gender') || $errors->has('nationality') || $errors->has('date_of_birth') || $errors->has('resume') || $errors->has('cover_letter'))
     <script>
        $(document).ready(function(){
          $('#applyNowModal').modal('show');
        });
     </script>


@endif