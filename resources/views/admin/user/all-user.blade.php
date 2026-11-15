@extends('adminpanel')
@section('admincontent')
@php use Illuminate\Support\Str; @endphp
<div class="container-fluid">
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            {{-- <h1 class="page-title fw-medium fs-18 mb-2"></h1> --}}
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ALL User</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                All User
            </div>
        </div>
        <div class="card-body">
            <div class="gy-4">
                <div class="row mt-4">
                    <div class="col-md-12">
                        <table id="zero-config" class="table dt-table-hover table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Role</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($user_list as $list)
                                        <tr>
                                            <td>{{ $list->id ?? '' }}</td>
                                            <td>{{ $list->first_name ?? '' }}</td>
                                            <td>{{ $list->last_name ?? '' }}</td>
                                            <td>{{ Str::ucfirst(Str::lower($list->role)) }}</td>
                                            <td>{{ $list->email ?? '' }}</td>
                                            <td>{{ $list->phone ?? '' }}</td>
                                            <td>
                                                <div class="d-flex align-items-center flex-wrap">
                                                    <div class="custom-toggle-switch ms-2">
                                                        <input class="user-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('user-status-change') }}" data-id="{{ $list->id }}" type="checkbox" {{ ($list->active==1)?'checked':'' }}>
                                                        <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ URL::to('edit-user/'.$list->id) }}" class="btn btn-warning rounded-pill btn-wave waves-effect waves-light">
                                                        Edit
                                                    </a>&nbsp; &nbsp;
                                                    <a href="{{ URL::to('user-permission/'.$list->id) }}" class="btn btn-primary rounded-pill btn-wave waves-effect waves-light">
                                                        Permission Setup
                                                    </a>&nbsp; &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        
                                    @endforelse
                                    
                                </tbody>
                            </table>
                    </div>
                </div>
                <!-- End Home page -->
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function(){
       $('.user-status-change').change(function(){
           var status = $(this).prop('checked') == true ? 1 : 0;
           var user_id = $(this).data('id');
           var url = $(this).data('action');
               $.post(url,
               {
                   user_id: user_id,
                   status: status
               },
               function(data, status){
                   console.log(data);
                   if(data['result']['key']===101){
                       iziToast.show({
                           title: 'Info',
                           message: data['result']['val'],
                           position: 'topRight',
                           timeout: 8000,
                           color: 'red',
                           balloon: true,
                           close: true,
                           progressBarColor: 'yellow',
                       });
                   }
                   if(data['result']['key']===200){
                       iziToast.show({
                           title: 'Info',
                           message: data['result']['val'],
                           position: 'topRight',
                           timeout: 8000,
                           color: 'green',
                           balloon: true,
                           close: true,
                           progressBarColor: 'yellow',
                       });

                   }
                   //alert("Data: " + data + "\nStatus: " + status);
               });

       });
   });
   </script>
@endsection