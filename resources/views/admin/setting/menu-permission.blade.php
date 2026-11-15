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
                        <li class="breadcrumb-item active" aria-current="page">Menu Permission</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                 Menu for Permission
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('sub-menu-data-post') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Add menu permission flat -->
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <input type="hidden" name="sub_menu_id" value="{{ $sub_menu->id ?? '' }}">
                            <label for="title" class="form-label">Main Menu</label>
                            <select name="main_menu_id" id="main_menu_id" class="form-select">
                                <option value="">Select Menu</option>
                                @foreach($menu_list as $key => $list)
                                <option {{ (!empty($sub_menu->main_menu_id) && $sub_menu->main_menu_id==$list->id)?'selected':'' }} value="{{ (!empty($list->id))?$list->id:'' }}">{{ (!empty($list->title))?$list->title:'' }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('main_menu_id'))
                                <span class="text-danger">{{ $errors->first('main_menu_id') }}</span>
                            @endif
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input value="{{ (!empty($sub_menu->title))?$sub_menu->title:'' }}" name="title" id="title" type="text" class="form-control">
                                @if ($errors->has('title'))
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="mb-3">
                                <label for="process" class="form-label">Process</label>
                                <input value="{{ (!empty($sub_menu->process))?$sub_menu->process:'' }}" name="process" id="process" type="text" class="form-control">
                                @if ($errors->has('process'))
                                    <span class="text-danger">{{ $errors->first('process') }}</span>
                                @endif
                           </div>
                        </div>

                        <div class="col-2">
                            <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light">Submit</button>
                            <button type="submit" class="btn btn-warning _effect--ripple waves-effect waves-light">Cancel</button>
                        </div>
                    </div>
                    <!-- End Add menu permission flat -->
                </form>

                <hr>

                <form method="get">
                    @csrf
                    <!-- Add menu permission flat -->
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <input type="hidden" name="blog_id" value="">
                            <label for="title" class="form-label">Sub Menu List</label>
                            <select name="list_main_menu_id" class="form-select">
                                <option value="">--Select Menu--</option>
                                @foreach($menu_list as $key => $list)
                                <option {{ (!empty($get_list_main_menu_id) && $get_list_main_menu_id==$list->id)?'selected':'' }} value="{{ (!empty($list->id))?$list->id:'' }}">{{ (!empty($list->title))?$list->title:'' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input class="form-control" type="text" placeholder="Enter Title..." name="search" value="{{ (!empty($get_search))?$get_search:'' }}" />
                           </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                            <div class="mb-3 mt-4">
                                <button type="submit" class="btn btn-primary _effect--ripple waves-effect waves-light mt-1">Filter</button>
                                <button type="submit" class="btn btn-warning _effect--ripple waves-effect waves-light mt-1">Reset</button>
                           </div>
                        </div>
                    </div>
                    <!-- End Add menu permission flat -->
                </form>

                <div class="row mt-4">
                        <div class="col-md-12">
                            <table id="zero-config" class="table dt-table-hover table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>Title</th>
                                            <th>Main Menu</th>
                                            <th>Process</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($sub_menu_list as $list)
                                        <tr>
                                            <td>{{ (!empty($list->id))?$list->id:'' }}</td>
                                            <td>{{ (!empty($list->title))?$list->title:'' }}</td>
                                            <td>{{ (!empty($list->main_menu->title))?$list->main_menu->title:'' }}</td>
                                            <td>{{ (!empty($list->process))?$list->process:'' }}</td>
                                            <td>
                                                <div class="custom-toggle-switch ms-2">
                                                    <input class="sub-menu-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('sub-menu-status-change') }}" data-id="{{ (!empty($list->id))?$list->id:'' }}" type="checkbox" {{ ($list->status==0)?'checked':'' }}>
                                                    <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="{{ URL::to('menu-permission/'.$list->id) }}" class="btn btn-warning rounded-pill btn-wave waves-effect waves-light">
                                                        Edit
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



            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function(){
       $('.sub-menu-status-change').change(function(){
           var status = $(this).prop('checked') == true ? 2 : 1;
           var sub_menu_id = $(this).data('id');
           var url = $(this).data('action');
               $.post(url,
               {
                   sub_menu_id: sub_menu_id,
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