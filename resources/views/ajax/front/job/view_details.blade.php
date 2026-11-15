<style>
    .modal-margin-left{
        margin : 3px;
    }
</style>
<!-- The Modal -->

{{-- End Modal --}}
<script src="{{ asset('web/js/jquery.js') }}"></script>
@if($errors->has('name') || $errors->has('email') || $errors->has('phone') || $errors->has('gender') || $errors->has('nationality') || $errors->has('date_of_birth') || $errors->has('resume') || $errors->has('cover_letter'))
     <script>
        $(document).ready(function(){
          $('#applyNowModal').modal('show');
        });
     </script>

     <script>
        $(document).ready(function(){
          $('#myModal').modal('show');
        });
     </script>
@endif