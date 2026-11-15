@extends('adminpanel')
@section('admincontent')
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<div class="container-fluid">
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            {{-- <h1 class="page-title fw-medium fs-18 mb-2"></h1> --}}
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Menu Permission Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Create Menu for Permission
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('store-main-menu-data') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Home page -->
                    <div class="row">
                        <div class="col-6">
                            <input type="hidden" name="main_menu_id" value="{{ (!empty($menu_data->id))?$menu_data->id:'' }}" />
                            <input value="{{ (!empty($menu_data->title))?$menu_data->title:'' }}" name="title" class="form-control" type="text" id="title" placeholder="Menu Title Here... "><br>
                            @if ($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">Submit</button>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <table id="zero-config" class="table dt-table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($menu_list as $list)
                                        <tr>
                                            <td>{{ (!empty($list->id))?$list->id:'' }}</td>
                                            <td>{{ (!empty($list->title))?$list->title:'' }}</td>
                                            <td>
                                                <div class="custom-toggle-switch ms-2">
                                                    <input class="main-menu-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('main-menu-status-change') }}" data-id="{{ (!empty($list->id))?$list->id:'' }}" type="checkbox" {{ ($list->status==0)?'checked':'' }}>
                                                    <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ URL::to('create-main-menu/'.$list->id) }}" class="btn btn-warning rounded-pill btn-wave waves-effect waves-light">
                                                        Edit
                                                    </a>&nbsp; &nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        
                                    </tbody>
                                    @empty

                                    @endforelse
                                </table>
                        </div>
                    </div>
                    <!-- End Home page -->


                    

                    <div class="text-start py-5">
                        <button type="submit" class="px-5 py-2 common-button rounded-pill">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function(){
       $('.main-menu-status-change').change(function(){
           var status = $(this).prop('checked') == true ? 2 : 1;
           var menu_id = $(this).data('id');
           var url = $(this).data('action');
               $.post(url,
               {
                   menu_id: menu_id,
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