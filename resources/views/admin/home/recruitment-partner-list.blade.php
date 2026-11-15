@extends('adminpanel')
@section('admincontent')
<div class="">
    <div class="">
        <div class="secondary-nav">
            <div class="breadcrumbs-container" data-page-heading="Analytics">
                <header class="header navbar navbar-expand-sm">
                    <a href="javascript:void(0);" class="btn-toggle sidebarCollapse" data-placement="bottom">
                    </a>
                    <div class="d-flex breadcrumb-content">
                        <div class="page-header">
                            <div class="page-title">
                            </div>
                            <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Recruitment Partner</li>
                                </ol>
                            </nav>

                        </div>
                    </div>
                </header>
            </div>
        </div>
        <h5 class="fw-bold py-3">Recruitment Partner List</h5>
        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Company Name</th>
                            <th scope="col">Contact Person</th>
                            <th scope="col">Email</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Website</th>
                            <th scope="col">Experience</th>
                            <th scope="col">Religion</th>
                            <th scope="col">Hear About Us</th>
                            <th scope="col">Message</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recruitment_partner_list as $list)
                        <tr>
                            <td>{{ $list->company_name ?? '' }}</td>
                            <td>{{ $list->contact_person ?? '' }}</td>
                            <td>{{ $list->email ?? '' }}</td>
                            <td>{{ $list->phone ?? '' }}</td>
                            <td>{{ $list->website ?? '' }}</td>
                            <td>{{ $list->experience ?? '' }}</td>
                            <td>{{ $list->religion ?? '' }}</td>
                            <td>{{ $list->hear_about_us ?? '' }}</td>
                            <td>{{ $list->message ?? '' }}</td>
                          </tr>
                        @empty
                            <tr>
                                No Data Found
                            </tr>
                        @endforelse

                    </tbody>
                </table>
                <div style="text-align: center;" class="pagination-custom_solid">
                    {{ $recruitment_partner_list->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection