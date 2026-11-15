<aside class="app-sidebar sticky" id="sidebar">
    <!-- Start::main-sidebar-header -->
    <div class="main-sidebar-header">
        <a href="index.html" class="header-logo">
            <img src="{{ asset('admin/assets/images/brand-logos/desktop-logo.png') }}" alt="logo" class="desktop-logo">
            <img src="{{ asset('admin/assets/images/brand-logos/toggle-dark.png') }}" alt="logo" class="toggle-dark">
            <img src="{{ asset('admin/assets/images/brand-logos/desktop-dark.png') }}" alt="logo" class="desktop-dark">
            <img src="{{ asset('admin/assets/images/brand-logos/toggle-logo.png') }}" alt="logo" class="toggle-logo">
        </a>
    </div>
    <!-- End::main-sidebar-header -->

    <!-- Start::main-sidebar -->
    <div class="main-sidebar" id="sidebar-scroll">

        <!-- Start::nav -->
        <nav class="main-menu-container nav nav-pills flex-column sub-open">
            <div class="slide-left" id="slide-left">
                <svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z"></path> </svg>
            </div>
            <ul class="main-menu">
                @if (Auth::check() && Auth::user()->role=='admin')
                    <li class="slide__category"><span class="category-name">Home Page</span></li>
                    <li class="slide has-sub {{ (!empty($product_menu) && $product_menu == true)?'active open':'' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ (!empty($product_menu) && $product_menu == true)?'active':'' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><rect x="48" y="48" width="64" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="144" y="48" width="64" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="48" y="144" width="64" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><rect x="144" y="144" width="64" height="64" rx="8" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            <span class="side-menu__label">Products</span>
                            <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1 {{ (!empty($product_menu) && $product_menu == true)?'active':'' }}">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">Product Page</a>
                            </li>
                            @if (Auth::check() && Auth::user()->role=='admin')
                            <li class="slide {{ (!empty($all_product_menu) && $all_product_menu == true)?'active':'' }}">
                                <a href="{{ url('all-products') }}" class="side-menu__item {{ (!empty($all_product_menu) && $all_product_menu == true)?'active':'' }}">All Products</a>
                            </li>
                            @endif
                            @if (Auth::check() && Auth::user()->role=='admin')
                            <li class="slide {{ (!empty($create_product_menu) && $create_product_menu == true)?'active':'' }}">
                                <a href="{{ url('create-product') }}" class="side-menu__item {{ (!empty($create_product_menu) && $create_product_menu == true)?'active':'' }}">Create Product</a>
                            </li>
                            @endif
                        </ul>
                    </li>
                    <li class="slide has-sub {{ (!empty($order_menu) && $order_menu == true)?'active open':'' }}">
                        <a href="javascript:void(0);" class="side-menu__item {{ (!empty($order_menu) && $order_menu == true)?'active':'' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><path d="M184,184H69.8L41.9,30.6A8,8,0,0,0,34.1,24H16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="80" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><circle cx="184" cy="204" r="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M62.5,144H188.1a15.9,15.9,0,0,0,15.7-13.1L216,64H48" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                            <span class="side-menu__label">Orders</span>
                            <i class="ri-arrow-right-s-line side-menu__angle"></i>
                        </a>
                        <ul class="slide-menu child1 {{ (!empty($order_menu) && $order_menu == true)?'active':'' }}">
                            <li class="slide side-menu__label1">
                                <a href="javascript:void(0)">Orders</a>
                            </li>
                            <li class="slide {{ (!empty($sale_menu) && $sale_menu == true)?'active':'' }}">
                                <a href="{{ url('admin-sale') }}" class="side-menu__item {{ (!empty($sale_menu) && $sale_menu == true)?'active':'' }}">Admin Sale (POS)</a>
                            </li>
                            <li class="slide {{ (!empty($all_order_menu) && $all_order_menu == true)?'active':'' }}">
                                <a href="{{ url('order-list') }}" class="side-menu__item {{ (!empty($all_order_menu) && $all_order_menu == true)?'active':'' }}">Order List</a>
                            </li>
                        </ul>
                    </li>
                @endif

                <!-- End::slide -->

                <!-- Start::slide -->
                <li class="slide__category"><span class="category-name">Social</span></li>
                @if (Auth::check() && Auth::user()->role=='admin' || Auth::user()->role=='marketing' || in_array('blog.main',$menu_items))
                <li class="slide has-sub {{ (!empty($blog) && $blog == true)?'active open':'' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ (!empty($blog) && $blog == true)?'active':'' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polyline points="32 176 128 232 224 176" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><polyline points="32 128 128 184 224 128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><polygon points="32 80 128 136 224 80 128 24 32 80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                        <span class="side-menu__label">Blogs</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1" style="{{ (!empty($blog) && $blog == true)?'active':'' }}">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Blogs</a>
                        </li>
                        @if (Auth::check() && Auth::user()->role=='admin' || Auth::user()->role=='marketing' || in_array('blog.add',$menu_items))
                        <li class="slide {{ (!empty($add_blog) && $add_blog == true)?'active':'' }}">
                            <a href="{{ url('create-blog') }}" class="side-menu__item {{ (!empty($add_blog) && $add_blog == true)?'active':'' }}">Add Blog</a>
                        </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role=='admin' || Auth::user()->role=='marketing' || in_array('blog.all',$menu_items))
                        <li class="slide {{ (!empty($blog_list_menu) && $blog_list_menu == true)?'active':'' }}">
                            <a href="{{ url('list-blog') }}" class="side-menu__item {{ (!empty($blog_list_menu) && $blog_list_menu == true)?'active':'' }}">All Blog</a>
                        </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role=='admin' || Auth::user()->role=='marketing' || in_array('blog.category',$menu_items))
                        <li class="slide {{ (!empty($blog_categories_menu1) && $blog_categories_menu1 == true)?'active':'' }}">
                            <a href="{{ url('blog-categories') }}" class="side-menu__item {{ (!empty($blog_categories_menu1) && $blog_categories_menu1 == true)?'active':'' }}">Blog Categories</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                <li class="slide__category"><span class="category-name">Office</span></li>
                @if (Auth::check() && Auth::user()->role=='admin' || in_array('user.main',$menu_items))
                <li class="slide has-sub {{ (!empty($user_menu) && $user_menu == true)?'active open':'' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ (!empty($user_menu) && $user_menu == true)?'active':'' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                        </svg>
                        <span class="side-menu__label">Users</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1 {{ (!empty($user_menu) && $user_menu == true)?'active':'' }}">
                        <li class="slide side-menu__label1">
                            <a href="javascript:void(0)">Users</a>
                        </li>
                        @if (Auth::check() && Auth::user()->role=='admin' || in_array('user.add',$menu_items))
                        <li class="slide {{ (!empty($add_user_menu) && $add_user_menu == true)?'active':'' }}">
                            <a href="{{ url('add-user') }}" class="side-menu__item {{ (!empty($add_user_menu) && $add_user_menu == true)?'active':'' }}">Add User</a>
                        </li>
                        @endif
                        @if (Auth::check() && Auth::user()->role=='admin' || in_array('user.all',$menu_items))
                        <li class="slide {{ (!empty($all_user_menu) && $all_user_menu == true)?'active':'' }}">
                            <a href="{{ url('all-user') }}" class="side-menu__item {{ (!empty($all_user_menu) && $all_user_menu == true)?'active':'' }}">All User</a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                @if (Auth::check() && Auth::user()->role=='admin')
                <li class="slide has-sub {{ (!empty($setting_menu) && $setting_menu == true)?'active open':'' }}">
                    <a href="javascript:void(0);" class="side-menu__item {{ (!empty($setting_menu) && $setting_menu == true)?'active':'' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M32,216V56a8,8,0,0,1,8-8H216a8,8,0,0,1,8,8V216l-32-16-32,16-32-16L96,216,64,200Z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><line x1="144" y1="112" x2="192" y2="112" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><line x1="144" y1="144" x2="192" y2="144" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></line><rect x="64" y="96" width="48" height="64" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></rect></svg>
                        <span class="side-menu__label">Settings</span>
                        <i class="ri-arrow-right-s-line side-menu__angle"></i>
                    </a>
                    <ul class="slide-menu child1 {{ (!empty($setting_menu) && $setting_menu == true)?'active':'' }}">
                        <li class="slide {{ (!empty($main_menu_menu) && $main_menu_menu == true)?'active':'' }}">
                            <a href="{{ url('create-main-menu') }}" class="side-menu__item {{ (!empty($main_menu_menu) && $main_menu_menu == true)?'active':'' }}">Create Main Menu</a>
                        </li>
                        <li class="slide {{ (!empty($menu_permission_menu) && $menu_permission_menu == true)?'active':'' }}">
                            <a href="{{ url('menu-permission') }}" class="side-menu__item {{ (!empty($menu_permission_menu) && $menu_permission_menu == true)?'active':'' }}">Menu Permission Setting</a>
                        </li>
                    </ul>
                </li>
                @endif
            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24" viewBox="0 0 24 24"> <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z"></path> </svg></div>
        </nav>

    </div>
    <!-- End::main-sidebar -->

</aside>
