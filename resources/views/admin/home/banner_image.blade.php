@extends('adminpanel')
@section('admincontent')
<script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
<div class="container-fluid">
    <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
            <h1 class="page-title fw-medium fs-18 mb-2">Inputs</h1>
            <div class="">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Banner Change</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Input Types
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('create-banner-data-post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="hidden" name="banner_id" value="{{ (!empty($banner_data->id))?$banner_data->id:'' }}" />
                            <input type="text" value="{{ (!empty($banner_data->title))?$banner_data->title:old('title') }}" name="title" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Description</label>

                            <textarea tabindex="-1" name="description" id="post_content" class="form-control" rows="10">{{ (!empty($banner_data->description))?$banner_data->description:old('description') }}</textarea>
                            @if($errors->has('description'))
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            @endif
                            <script>
                                CKEDITOR.replace('post_content');
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Homepage Image (JPEG, PNG, JPG)</label>
                            <input type="file" name="image" class="form-control" id="fname" aria-describedby="fname">
                            <span class="text-warning">width: 1280px, height: 853px</span>
                        </div>
                    </div>
                    <div class="row">
                        @if(!empty($banner_data->image))
                        <div class="mb-3">
                            <img src="{{ asset($banner_data->image) }}" width="450px" height="280px" />
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="left_btn_text" class="form-label">Left Button Text</label>
                            <input type="text" value="{{ $banner_data->left_btn_text ?? '' }}" name="left_btn_text" class="form-control" id="left_btn_text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="left_btn_link" class="form-label">Left Button Link</label>
                            <input type="text" value="{{ $banner_data->left_btn_link ?? '' }}" name="left_btn_link" class="form-control" id="left_btn_link">
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex">
                            <div class="m-2">
                                <div class="form-check">
                                    <input class="form-check-input" value="yes" type="checkbox" id="is_view_left_btn" name="is_view_left_btn" {{ (!empty($banner_data->is_view_left_btn) && $banner_data->is_view_left_btn=='yes')?'checked':'' }}>
                                    <label class="form-check-label" for="left_btn_status">Is View Left Button</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="right_btn_text" class="form-label">Right Button Text</label>
                            <input type="text" value="{{ $banner_data->right_btn_text ?? '' }}" name="right_btn_text" class="form-control" id="right_btn_text">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="right_btn_link" class="form-label">Right Button Link</label>
                            <input type="text" value="{{ $banner_data->right_btn_link ?? '' }}" name="right_btn_link" class="form-control" id="right_btn_link">
                        </div>
                    </div>
                    <div class="row">
                        <div class="d-flex">
                            <div class="m-2">
                                <div class="form-check">
                                    <input class="form-check-input" value="yes" type="checkbox" id="is_view_right_btn" name="is_view_right_btn" {{ (!empty($banner_data->is_view_right_btn) && $banner_data->is_view_right_btn=='yes')?'checked':'' }}>
                                    <label class="form-check-label" for="left_btn_status">Is View Right Button</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-start py-2">
                        <button type="submit" class="px-5 py-2 common-button rounded-pill">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection