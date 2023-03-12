<div class="col-12 col-lg-3 col-navbar d-none d-xl-block">
    <aside class="sidebar">
        <a href="#" class="sidebar-logo">
            <div class="d-flex justify-content-start align-items-center">
                <img src="./assets/img/home/icon.png" class="logo" alt="" />
                <span>Freshmarket</span>
            </div>

            <button id="toggle-navbar" onclick="toggleNavbar()">
                <img src="./assets/img/global/navbar-times.svg" alt="" />
            </button>
        </a>

        <h5 class="sidebar-title">Product Management</h5>

        {{-- <a href="./index.html" class="sidebar-item{{ Request::is('/') ? ' active' : '' }}" onclick="toggleActive(this)">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Overview</span>
        </a> --}}

        <!-- <a href="./employees.html" class="sidebar-item"> -->
        <!-- <img src="./assets/img/global/users.svg" alt=""> -->
        <a href="{{ route('categories.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'categories' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 14H14V21H21V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 14H3V21H10V14Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M21 3H14V10H21V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M10 3H3V10H10V3Z" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            <span>Category</span>
        </a>


       <img src="./assets/img/global/users.svg" alt="">
         <a href="{{ route('products.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'products' ? ' active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="" width="24" height="24" viewBox="0 0 24 24"
                stroke-width="2" stroke="currentColor" fill="none">
                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5"></path>
                <path d="M12 12l8 -4.5"></path>
                <path d="M12 12l0 9"></path>
                <path d="M12 12l-8 -4.5"></path>
            </svg>
            <span>Product</span>
        </a>

         <img src="./assets/img/global/users.svg" alt="">
        <a href="{{ route('productDiscount') }}"
            class="sidebar-item{{ Request::segment(1) == 'ProductDiscount' ? ' active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-discount-2-off" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M9 15l3 -3m2 -2l1 -1"></path>
                <path d="M9.148 9.145a.498 .498 0 0 0 .352 .855a.5 .5 0 0 0 .35 -.142"></path>
                <path d="M14.148 14.145a.498 .498 0 0 0 .352 .855a.5 .5 0 0 0 .35 -.142"></path>
                <path
                    d="M8.887 4.89a2.2 2.2 0 0 0 .863 -.53l.7 -.7a2.2 2.2 0 0 1 3.12 0l.7 .7c.412 .41 .97 .64 1.55 .64h1a2.2 2.2 0 0 1 2.2 2.2v1c0 .58 .23 1.138 .64 1.55l.7 .7a2.2 2.2 0 0 1 0 3.12l-.7 .7a2.2 2.2 0 0 0 -.528 .858m-.757 3.248a2.193 2.193 0 0 1 -1.555 .644h-1a2.2 2.2 0 0 0 -1.55 .64l-.7 .7a2.2 2.2 0 0 1 -3.12 0l-.7 -.7a2.2 2.2 0 0 0 -1.55 -.64h-1a2.2 2.2 0 0 1 -2.2 -2.2v-1a2.2 2.2 0 0 0 -.64 -1.55l-.7 -.7a2.2 2.2 0 0 1 0 -3.12l.7 -.7a2.2 2.2 0 0 0 .64 -1.55v-1c0 -.604 .244 -1.152 .638 -1.55">
                </path>
                <path d="M3 3l18 18"></path>
            </svg>
            <span>Product discount </span>
        </a>
        <a href="{{ route('product-groups.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'product-groups' ? ' active' : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-box-seam" width="24"
                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 3l8 4.5v9l-8 4.5l-8 -4.5v-9l8 -4.5"></path>
                <path d="M12 12l8 -4.5"></path>
                <path d="M8.2 9.8l7.6 -4.6"></path>
                <path d="M12 12v9"></path>
                <path d="M12 12l-8 -4.5"></path>
            </svg>
            <span>Product Group </span>
        </a>
        <h5 class="sidebar-title">General</h5>

        <a href="{{ route('locations.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'locations' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M20 4H4C2.89543 4 2 4.89543 2 6V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V6C22 4.89543 21.1046 4 20 4Z"
                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M2 6L12 13L22 6" stroke="white" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>

            <span>Location</span>
        </a>

        <a href="{{ route('store.index') }}"
            class="sidebar-item{{ Request::segment(1) == 'store' ? ' active' : '' }}">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M8 20H4V4H16V8M8 20V12H16M8 20V16H12M20 4H8M20 4V16H16M20 4V8H16" stroke="white"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <span>Store</span>
        </a>
    </aside>
</div>
