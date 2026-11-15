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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Blogs</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Blog</li>
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
                <form method="post" action="{{ URL::to('create-blog-data-post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <input type="hidden" name="blog_id" value="{{ (!empty($blog_data->id))?$blog_data->id:'' }}" />
                            <label for="title" class="form-label">Blog Category*</label>
                            <select name="blog_category_id" id="blog_category_id" class="form-select">
                                <option value="" selected>Select Category</option>
                                @foreach ($blog_categories as $row)
                                <option {{ (!empty($blog_data->blog_category_id) && $blog_data->blog_category_id==$row->id)?'selected':'' }} value="{{ $row->id }}" >{{ $row->title }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('blog_category_id'))
                                <span class="text-danger">{{ $errors->first('blog_category_id') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="title" class="form-label">Blog Author*</label>
                                <input type="text" value="{{ (!empty($blog_data->author_name))?$blog_data->author_name:'Author' }}" name="author_name" class="form-control" id="author_name" aria-describedby="fname">
                                @if($errors->has('author_name'))
                                    <span class="text-danger">{{ $errors->first('author_name') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Author Image (JPEG, PNG):</label>
                                <input type="file" name="author_image" class="form-control" id="author_image" aria-describedby="fname">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Blog Title</label>
                            <input type="text" value="{{ (!empty($blog_data->title))?$blog_data->title:old('title') }}" name="title" class="form-control" id="title" aria-describedby="fname">
                            @if($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Short Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ (!empty($blog_data->description))?$blog_data->description:old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Feature Image (JPEG, PNG, JPG)</label>
                            <input type="file" name="image" class="form-control" id="fname" aria-describedby="fname">
                            <span class="text-warning">width: 856px, height: 406px</span>
                        </div>
                    </div>
                    
                    <div class="row">
                        @if(!empty($blog_data->image))
                        <div class="mb-3">
                            <img src="{{ asset($blog_data->image) }}" width="300px" height="180px" />
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Blog Alt Tag</label>
                            <input type="text" value="{{ (!empty($blog_data->tags))?$blog_data->tags:old('tags') }}" name="basic" autofocus class="form-control" id="fname" aria-describedby="fname">
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Description Page Image (JPEG, PNG, JPG)</label>
                            <input type="file" name="desc_image" class="form-control">
                            <span class="text-warning">width: 1209px, height: 574px</span>
                        </div>
                    </div>
                    <div class="row">
                        @if(!empty($blog_data->desc_image))
                        <div class="mb-3">
                            <img src="{{ asset($blog_data->desc_image) }}" width="300px" height="120px" />
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Meta Description (Optional)</label>
                            <textarea name="meta_description" class="form-control" rows="3">{{ (!empty($blog_data->meta_description))?$blog_data->meta_description:old('meta_description') }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Banner Image (JPEG, PNG, JPG)</label>
                            <input type="file" name="detail_image" class="form-control">
                            <span class="text-warning">width: 1080px, height: 240px</span>
                        </div>
                    </div>
                    <div class="row">
                        @if(!empty($blog_data->detail_image))
                        <div class="mb-3">
                            <img src="{{ asset($blog_data->detail_image) }}" width="300px" height="120px" />
                        </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Blog Description</label>

                            <textarea tabindex="-1" name="long_description" id="post_content" class="form-control" rows="10">{{ (!empty($blog_data->long_description))?$blog_data->long_description:old('long_description') }}</textarea>
                            @if($errors->has('long_description'))
                                <span class="text-danger">{{ $errors->first('long_description') }}</span>
                            @endif
                            <script>
                                CKEDITOR.replace('post_content');

                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">URL (Optional)</label>
                            <textarea name="slug" class="form-control" rows="3">{{ (!empty($blog_data->slug))?$blog_data->slug:'' }}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Blog Status</label>
                                <select name="blog_status" id="blog_status" class="form-select">
                                    <option value="" selected="">Select status</option>
                                    <option {{ (!empty($blog_data->blog_status) && $blog_data->blog_status=='Publish')?'selected':'' }} value="Publish">Publish</option>
                                    <option {{ (!empty($blog_data->blog_status) && $blog_data->blog_status=='Draft')?'selected':'' }} value="Draft">Draft</option>
                                    <option {{ (!empty($blog_data->blog_status) && $blog_data->blog_status=='Schedule')?'selected':'' }} value="Schedule">Schedule</option>
                                </select>
                                @if($errors->has('blog_status'))
                                    <span class="text-danger">{{ $errors->first('blog_status') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="fname" class="form-label">Publish time</label>
                                <input name="publish_time" value="{{ (!empty($blog_data->publish_time))?$blog_data->publish_time:now()->format('Y-m-d\TH:i') }}" type="datetime-local" class="form-control" id="dob" aria-describedby="dob">
                                @if($errors->has('publish_time'))
                                    <span class="text-danger">{{ $errors->first('publish_time') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-start py-2">
                        <button type="submit" class="px-5 py-2 common-button rounded-pill">Save</button>
                        @if(!empty($blog_data->slug))
                        {{-- <a href="{{ env('FRONT_URL').'news-details/'.$blog_data->slug }}" target="_blank" class="btn btn-warning px-5 py-2 common-button rounded-pill">Preview</a> --}}
                        <a href="{{ url('news-details/'.$blog_data->slug) }}" target="_blank" class="btn btn-warning px-5 py-2 common-button rounded-pill">Preview</a>
                        <a href="{{ URL::to('list-blog') }}" class="btn btn-success px-5 py-2 common-button rounded-pill">Blog List</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection