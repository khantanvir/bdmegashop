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
        <div class="card-body">
            <div class="row gy-4">
                <form method="post" action="{{ URL::to('create-banner-data-post') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Home page -->
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Home Page</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="banner" name="banner">
                                <label class="form-check-label" for="banner">
                                    Banner
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="awarding_body" name="awarding_body">
                                <label class="form-check-label" for="awarding_body">
                                    Awarding Body
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="why_ukmc" name="why_ukmc">
                                <label class="form-check-label" for="why_ukmc">
                                    Why UKMC
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="recruitment_partner" name="recruitment_partner">
                                <label class="form-check-label" for="recruitment_partner">
                                    Recruitment Partner
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Home page -->

                    <!-- Blogs page -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Blogs</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_blog" name="create_blog">
                                <label class="form-check-label" for="create_blog">
                                    Create Blog
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="blog_list" name="blog_list">
                                <label class="form-check-label" for="blog_list">
                                    Blog List
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="blog_categories" name="blog_categories">
                                <label class="form-check-label" for="blog_categories">
                                    Blog Categories
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="blog_category_list" name="blog_category_list">
                                <label class="form-check-label" for="blog_category_list">
                                    Blog Category List
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Blogs page -->

                    <!-- Events page -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Events</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_event" name="create_event">
                                <label class="form-check-label" for="create_event">
                                    Create Event
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="event_list" name="event_list">
                                <label class="form-check-label" for="event_list">
                                   Event List
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Events page -->

                    <!-- Campus page -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Campus</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_campus" name="create_campus">
                                <label class="form-check-label" for="create_campus">
                                    Create Campus
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="campus_list" name="campus_list">
                                <label class="form-check-label" for="campus_list">
                                   Campus List
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Campus page -->

                    <!-- Course page -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Course</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_course_category" name="create_course_category">
                                <label class="form-check-label" for="create_course_category">
                                    Course Category
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="course_category_list" name="course_category_list">
                                <label class="form-check-label" for="course_category_list">
                                   Course Category List
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_course" name="create_course">
                                <label class="form-check-label" for="create_course">
                                   Create Course
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="course_list" name="course_list">
                                <label class="form-check-label" for="course_list">
                                   Course List
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Course page -->

                    <!-- Career page -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>Career</h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="job_list" name="job_list">
                                <label class="form-check-label" for="job_list">
                                   Job List
                                </label>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_job" name="create_job">
                                <label class="form-check-label" for="create_job">
                                   Create Job
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- End Career page -->

                    <fieldset class="border rounded-3 p-3 mt-3">
                        <legend class="float-none w-auto px-3" >Office</legend>
                       
                        <!-- Career page -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5>Career</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="job_list" name="job_list">
                                    <label class="form-check-label" for="job_list">
                                    Job List
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="create_job" name="create_job">
                                    <label class="form-check-label" for="create_job">
                                    Create Job
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- End Career page -->

                        <!-- Career page -->
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Career</h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="job_list" name="job_list">
                                    <label class="form-check-label" for="job_list">
                                    Job List
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="create_job" name="create_job">
                                    <label class="form-check-label" for="create_job">
                                    Create Job
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- End Career page -->

                    </fieldset>

                    

                    <div class="text-start py-5">
                        <button type="submit" class="px-5 py-2 common-button rounded-pill">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection