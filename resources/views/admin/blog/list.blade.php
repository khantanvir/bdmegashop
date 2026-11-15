@extends('adminpanel')
@section('admincontent')
<div class="">
    <div class="">
        <div class="secondary-nav">
            <div class="breadcrumbs-container" data-page-heading="Analytics">
                <header class="header navbar navbar-expand-sm">
                    <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="feather feather-menu">
                            <line x1="3" y1="12" x2="21" y2="12"></line>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <line x1="3" y1="18" x2="21" y2="18"></line>
                        </svg> --}}
                    </a>
                    <div class="d-flex breadcrumb-content">
                        <div class="page-header">
                            <div class="page-title">
                            </div>
                            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Blog</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">All Blog</li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </header>
            </div>
        </div>
        <h5 class="fw-bold py-3">Blog List</h5>
        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Category</th>
                            <th scope="col">Publish Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($blog_list as $list)
                        <tr>
                            <td>{{ App\Models\Blog\Blog::stringSubstrLimit($list->title,25) }}</td>
                            <td>
                                {!! App\Models\Blog\Blog::stringSubstrLimit($list->long_description,30) !!}
                            </td>
                            <td>{{ (!empty($list->category->title))?$list->category->title:'' }}</td>
                            <td>{{ date('F d Y',strtotime($list->publish_time)) }}</td>
                            <td>{{ (!empty($list->blog_status))?$list->blog_status:'' }}</td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ URL::to('create-blog/'.$list->id) }}" class="btn btn-warning rounded-pill btn-wave waves-effect waves-light">
                                        Edit
                                    </a>&nbsp; &nbsp;
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="custom-toggle-switch ms-2">
                                            <input class="blog-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('blog-status-change') }}" data-id="{{ $list->id }}" type="checkbox" {{ ($list->status==0)?'checked':'' }}>
                                            <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                          </tr>
                        @empty
                            <tr>
                                No Data Found
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                <div style="text-align: center;" class="pagination-custom_solid">
                    {{ $blog_list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(function(){
       $('.blog-status-change').change(function(){
           var status = $(this).prop('checked') == true ? 0 : 1;
           var blog_id = $(this).data('id');
           var url = $(this).data('action');
               $.post(url,
               {
                   blog_id: blog_id,
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
                       setTimeout(function () {
                           location.reload(true);
                       }, 2000);
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