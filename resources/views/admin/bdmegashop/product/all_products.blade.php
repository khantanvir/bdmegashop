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
                                    <li class="breadcrumb-item"><a href="#">Product</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">All Products</li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </header>
            </div>
        </div>
        {{-- <h5 class="fw-bold py-3">Product List</h5> --}}
        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Category</th>
                            <th scope="col">Sub Category</th>
                            <th scope="col">Image</th>
                            <th scope="col">Selling Price</th>
                            <th scope="col">Origin</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $list)
                        <tr>
                            <td>{{ $list->id }}</td>
                            <td>{{ App\Models\Blog\Blog::stringSubstrLimit($list->title,25) }}</td>
                            <td>
                                {!! App\Models\Blog\Blog::stringSubstrLimit($list->description,30) !!}
                            </td>
                            <td>{{ (!empty($list->category->title))?$list->category->title:'' }}</td>
                            <td>{{ (!empty($list->subCategory->title))?$list->subCategory->title:'' }}</td>
                            <td>
                                @if (!empty($list->image))
                                    <img src="{{ asset($list->image) }}" alt="Product Image" class="img-thumbnail" style="max-width: 70px;">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ (!empty($list->selling_price))?$list->selling_price:'' }}</td>
                            <td>{{ (!empty($list->origin))?$list->origin:'' }}</td>
                            
                            <td>
                                <div class="d-flex">
                                    <div class="d-flex align-items-center flex-wrap">
                                        <div class="custom-toggle-switch ms-2">
                                            <input class="product-data-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('product-status-change') }}" data-id="{{ $list->id }}" type="checkbox" {{ ($list->status==0)?'checked':'' }}>
                                            <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a title="Edit Product" href="{{ URL::to('create-product/'.$list->id) }}" class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                </a>
                                <a title="Add Product Details" href="{{ URL::to('create-product-details/'.$list->id) }}" class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-plus" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5"/>
                                        <path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383m.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z"/>
                                    </svg>
                                </a>
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
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}
<script>
    $(function(){
       $('.product-data-status-change').change(function(){
           var status = $(this).prop('checked') == true ? 0 : 1;
           var product_id = $(this).data('id');
           var url = $(this).data('action');
               $.post(url,
               {
                   product_id: product_id,
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