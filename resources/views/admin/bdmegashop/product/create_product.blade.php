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
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Product</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="card custom-card">
        <div class="card-header justify-content-between">
            <div class="card-title">
                Product Create/Edit Page
            </div>
        </div>
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('create-product-data-post') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <input type="hidden" name="product_id" value="{{ $product_data->id ?? '' }}" />
                        <div class="mb-3">
                            <label for="short_title" class="form-label">Category</label>
                            <select id="category_id" name="category_id" class="form-control" onchange="get_sub_category(this.value)">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id ?? '' }}" {{ (!empty($product_data->category_id) && $product_data->category_id == $category->id) ? 'selected' : '' }}>{{ $category->title ?? '' }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('category_id'))
                                <span class="text-danger">{{ $errors->first('category_id') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label class="form-label">Sub Category</label>
                            <select id="sub_category_id" name="sub_category_id" class="form-control">
                                <option value="">Sub Category</option>
                                @if(!empty($product_data->id))
                                    @foreach($sub_categories ?? [] as $sub_category)
                                        <option value="{{ $sub_category->id }}"
                                            {{ $product_data->sub_category_id == $sub_category->id ? 'selected' : '' }}>
                                            {{ $sub_category->title }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('sub_category_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="mb-3">
                            <label for="short_title" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id ?? '' }}" {{ (!empty($product_data->brand_id) && $product_data->brand_id == $brand->id) ? 'selected' : '' }}>{{ $brand->title ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="short_title" class="form-label">Owner</label>
                            <select name="product_owner" id="product_owner" class="form-control">
                                <option value="">Select Owner</option>
                                @foreach($owner_list as $owner)
                                    <option value="{{ $owner->id ?? '' }}" {{ (!empty($product_data->product_owner) && $product_data->product_owner == $owner->id) ? 'selected' : '' }}>{{ $owner->name ?? '' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" value="{{ $product_data->title ?? old('title') }}" name="title" class="form-control" id="title" >
                            @if($errors->has('title'))
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="fname" class="form-label">Description</label>
                            <textarea tabindex="-1" name="description" id="post_content" class="form-control" rows="10">{{ (!empty($product_data->description))?$product_data->description:old('description') }}</textarea>
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
                            <label for="long_description" class="form-label">Long Description</label>
                            <textarea tabindex="-1" name="long_description" id="long_description" class="form-control" rows="10">{{ (!empty($product_data->long_description))?$product_data->long_description:old('long_description') }}</textarea>
                            @if($errors->has('long_description'))
                                <span class="text-danger">{{ $errors->first('long_description') }}</span>
                            @endif
                            <script>
                                CKEDITOR.replace('long_description');
                            </script>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="title" class="form-label">Main Image</label>
                            <input type="file" value="" name="image" class="form-control" id="image" >
                            <span class="text-danger">Image size should be 370px * 340px</span>
                            @if($errors->has('image'))
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            @endif
                        </div>
                        @if (!empty($product_data->image))
                            <div class="mb-3">
                                <label for="current_image" class="form-label">Main Image</label>
                                <img src="{{ asset($product_data->image) }}" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="purchase_price" class="form-label">Purchase Price</label>
                            <input type="text" value="{{ $product_data->purchase_price ?? old('purchase_price') }}" name="purchase_price" class="form-control" id="purchase_price" >
                            @if($errors->has('purchase_price'))
                                <span class="text-danger">{{ $errors->first('purchase_price') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="text" value="{{ $product_data->price ?? old('price') }}" name="price" class="form-control" id="price" >
                            @if($errors->has('price'))
                                <span class="text-danger">{{ $errors->first('price') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="discount" class="form-label">Discount</label>
                            <input onchange="calculateSellingPrice()" type="text" value="{{ $product_data->discount ?? old('discount') }}" name="discount" class="form-control" id="discount" >
                            @if($errors->has('discount'))
                                <span class="text-danger">{{ $errors->first('discount') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="text" value="{{ $product_data->selling_price ?? old('selling_price') }}" name="selling_price" class="form-control" id="selling_price" >
                            @if($errors->has('selling_price'))
                                <span class="text-danger">{{ $errors->first('selling_price') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="shipping_charge" class="form-label">Shipping Charge (BDT)</label>
                            <input type="text" value="{{ $product_data->shipping_charge ?? old('shipping_charge') }}" name="shipping_charge" class="form-control" id="shipping_charge" >
                            @if($errors->has('shipping_charge'))
                                <span class="text-danger">{{ $errors->first('shipping_charge') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3">
                            <label for="origin" class="form-label">Origin</label>
                            <input type="text" value="{{ $product_data->origin ?? old('origin') }}" name="origin" class="form-control" id="origin" >
                            @if($errors->has('origin'))
                                <span class="text-danger">{{ $errors->first('origin') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="text-start py-2">
                        <button type="submit" class="px-5 py-2 common-button rounded-pill">Save</button>
                        @if(!empty($product_data->id))
                        <a href="{{ URL::to('all-products') }}" class="btn btn-success px-5 py-2 common-button rounded-pill">List</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function get_sub_category(id){
        if(id===null){
            return false;
        }
        $('#category_id').val(id);
        $.get('{{ URL::to('get_sub_categories_by_category') }}/'+id,function(data,status){
            if(data['result']['key']===200){
                console.log(data['result']['val']);
                $('#sub_category_id').html(data['result']['val']);
            }
        });

    }
</script>
<script>
    function calculateSellingPrice() {
        var regularPrice = parseFloat($('#price').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var sellingPrice = regularPrice - (regularPrice * discount / 100);
        $('#selling_price').val(sellingPrice.toFixed(2));
    }
</script>
@endsection
