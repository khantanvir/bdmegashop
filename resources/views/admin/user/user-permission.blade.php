@extends('adminpanel')
@section('admincontent')
<div class="container-fluid">
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Inputs</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Settings</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Permission Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Permission Setup
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row gy-4">
            <div class="widget-content widget-content-area">
                <form method="post" action="{{ URL::to('user-permission-assign') }}">
                    @csrf
                <input type="hidden" name="user_id" value="{{ (!empty($user_id))?$user_id:'' }}" />
                <div class="row">
                    @forelse($main_list as $key => $value)
                    <div class="col-md-12">
                        <h5>{{ (!empty($value->title))?$value->title:'' }}</h5>
                        <ul style="display: flex; flex-wrap: wrap;" class="col-md-12">
                            <!-- Your list items go here -->
                            @forelse($value->sub_menu_list as $key => $val)
                            <li class="pb-2 col-md-2" style="list-style: none; margin: 5px; flex: 0 0 calc(20% - 10px);"> <!-- Adjust width as per your requirement -->
                                <span class="form-check form-check-success">
                                    {{-- <input name="sub_menu_ids[]" value="{{ (!empty($val->id))?$val->id:'' }}" class="form-check-input checkbox_child" type="checkbox"> <p class="ps-2">{{ (!empty($val->title))?$val->title:'' }}</p> --}}
                                    <input {{ (!empty($id_array) && in_array($val->id,$id_array))?'checked':'' }} name="sub_menu_ids[]" value="{{ (!empty($val->id)) ? $val->id : '' }}" class="form-check-input checkbox_child" type="checkbox"> <p class="ps-2">{{ (!empty($val->title)) ? $val->title : '' }}</p>
                                </span>
                            </li>
                            @empty
                            @endforelse
                        </ul>
                    </div>
                    @empty

                    @endforelse
                </div>
                <button class="btn btn-sm btn-warning" type="submit">Update</button>
            </form>
            </div>
        </div>
    </div>
</div>
@endsection