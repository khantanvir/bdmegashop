<div class="modal fade" id="joinPeopleModalList" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel1">Join Event</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form method="post" action="{{ URL::to('join-event') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" value="" id="join_event_id" name="event_id" />
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Name</label>
                            <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Email</label>
                            <input type="text" value="{{ old('email') }}" name="email" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Phone</label>
                            <input type="text" value="{{ old('phone') }}" name="phone" class="form-control" id="title" aria-describedby="fname">
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
@if($errors->has('name') || $errors->has('email') || $errors->has('phone'))
     <script>
        $(document).ready(function(){
          $('#joinPeopleModalList').modal('show');
        });
     </script>
@endif