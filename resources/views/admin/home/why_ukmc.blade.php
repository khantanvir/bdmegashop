@extends('adminpanel')
@section('admincontent')
<link rel="stylesheet" href="{{ asset('front/css/switch.css') }}">
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
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
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Why UKMC</li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </header>
            </div>
        </div>
        <form method="post" action="{{ URL::to('store-why-ukmc-data') }}" enctype="multipart/form-data">
            @csrf
            <div id="card_1" class="col-lg-12 layout-spacing layout-top-spacing">
                <div class="statbox widget box box-shadow">
                    <div class="widget-content widget-content-area">
                        <div class="row mb-2">
                            <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                <div class="d-flex align-items-start justify-content-between">
                                    <h5>Add Why UKMC Data</h5>
                                </div><br>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col">
                                <input type="hidden" name="why_ukmc_id" value="{{ (!empty($why_ukmc_data->id))?$why_ukmc_data->id:'' }}" />
                                <div class="form-group mb-2"><label for="exampleFormControlTextarea1">Title*</label>
                                    <input type="text" name="title" value="{{ (!empty($why_ukmc_data->title))?$why_ukmc_data->title:old('title') }}" class="form-control">
                                    @if ($errors->has('title'))
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Description</label>
    
                                <textarea tabindex="-1" name="description" id="" class="form-control" rows="10">{{ (!empty($why_ukmc_data->description))?$why_ukmc_data->description:old('description') }}</textarea>
                                @if($errors->has('description'))
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Homepage Image (JPEG, PNG, JPG)</label>
                                <input type="file" name="image" class="form-control" id="fname" aria-describedby="fname">
                                <span class="text-warning">width: not more than 196px, height: 73px</span>
                            </div>
                        </div>
                        <div class="row">
                            @if(!empty($why_ukmc_data->image))
                            <div class="mb-3">
                                <img src="{{ asset($why_ukmc_data->image) }}" width="450px" height="280px" />
                            </div>
                            @endif
                        </div>
                        <div class="row mt-5">
                            <div class="col">
                                <button type="submit" class="btn btn-primary btn-lg mr-2"> Submit </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        <h5 class="fw-bold py-3">Why UKMC Data List</h5>
        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Image</th>
                        <th scope="col">Cteated at</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($why_ukmc_list as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->title ?? '' }}</td>
                        <td>{!! $row->description ?? '' !!}</td>
                        <td><img src="{{ asset($row->image) }}" height="72px" width="162px" /></td>
                        <td>{{ date('F d Y',strtotime($row->created_at)) }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ URL::to('admin-why-ukmc/'.$row->id) }}" class="btn btn-warning rounded-pill btn-wave waves-effect waves-light">
                                    Edit
                                </a>&nbsp; &nbsp;
                                <div class="d-flex align-items-center flex-wrap">
                                    <div class="custom-toggle-switch ms-2">
                                        <input class="why-ukmc-status-change" id="size-default" name="toggleswitchsize" data-action="{{ URL::to('why-ukmc-status-change') }}" data-id="{{ $row->id }}" type="checkbox" {{ ($row->status==0)?'checked':'' }}>
                                        <label class="label-secondary mb-1" onclick="$(this).prev('input').trigger('click')"></label>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty

                    @endforelse

                    </tbody>
                </table>
                <div class="pagination">
                    {{ $why_ukmc_list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
 $(function(){
    $('.why-ukmc-status-change').change(function(){
        var status = $(this).prop('checked') == true ? 0 : 1;
        var why_ukmc_id = $(this).data('id');
        var url = $(this).data('action');
            $.post(url,
            {
                why_ukmc_id: why_ukmc_id,
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
                    setTimeout(function () {
                        location.reload(true);
                    }, 2000);
                }
                //alert("Data: " + data + "\nStatus: " + status);
            });

    });
});
</script>
@endsection