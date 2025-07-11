@props(['title' => ''])
<!DOCTYPE html>
<html lang="en" data-theme="cupcake">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
  {{-- <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
                
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script> --}}
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  @stack('styles')
  @yield('styles')
</head>

<body>
  <div class="drawer xl:drawer-open">
    <input id="drawer-toggle" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex min-h-screen flex-col">
      <!-- Navbar -->
      <div class="navbar bg-base-100 shadow-xs">
        <div class="flex-none xl:hidden">
          <label for="drawer-toggle" class="btn btn-square btn-ghost">
            <i class="fas fa-bars"></i>
          </label>
        </div>
        <div class="flex-1">
          <a class="text-xl normal-case"></a>
        </div>
        <div class="flex-none gap-2">
          <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle">
              <div class="indicator">
                <i class="fas fa-bell"></i>
                <div class="badge badge-xs badge-info indicator-item">8</div>
              </div>
            </label>
            <div tabindex="0" class="card card-compact dropdown-content bg-base-100 z-[1] mt-3 w-52 shadow">
              <div class="card-body">
                <span class="text-lg font-bold">8 Notifications</span>
                <span class="text-info">View all notifications</span>
              </div>
            </div>
          </div>
          <div class="dropdown dropdown-end">
            <label tabindex="0" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img src="https://ui-avatars.com/api/?name=Admin+User" />
              </div>
            </label>
            <ul tabindex="0" class="menu menu-md dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
              <li><a><i class="fas fa-user mr-2"></i>Profile</a></li>
              <li><a><i class="fas fa-cog mr-2"></i>Settings</a></li>
              <li><a><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="bg-base-200/70 p-6">
        {{ $slot }}
      </div>

      <!-- Footer -->
      <footer class="footer sm:footer-horizontal bg-base-200 text-base-content mt-6 p-10">
        <aside>
          <svg width="50" height="50" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"
            class="fill-current">
            <path
              d="M22.672 15.226l-2.432.811.841 2.515c.33 1.019-.209 2.127-1.23 2.456-1.15.325-2.148-.321-2.463-1.226l-.84-2.518-5.013 1.677.84 2.517c.391 1.203-.434 2.542-1.831 2.542-.88 0-1.601-.564-1.86-1.314l-.842-2.516-2.431.809c-1.135.328-2.145-.317-2.463-1.229-.329-1.018.211-2.127 1.231-2.456l2.432-.809-1.621-4.823-2.432.808c-1.355.384-2.558-.59-2.558-1.839 0-.817.509-1.582 1.327-1.846l2.433-.809-.842-2.515c-.33-1.02.211-2.129 1.232-2.458 1.02-.329 2.13.209 2.461 1.229l.842 2.515 5.011-1.677-.839-2.517c-.403-1.238.484-2.553 1.843-2.553.819 0 1.585.509 1.85 1.326l.841 2.517 2.431-.81c1.02-.33 2.131.211 2.461 1.229.332 1.018-.21 2.126-1.23 2.456l-2.433.809 1.622 4.823 2.433-.809c1.242-.401 2.557.484 2.557 1.838 0 .819-.51 1.583-1.328 1.847m-8.992-6.428l-5.01 1.675 1.619 4.828 5.011-1.674-1.62-4.829z">
            </path>
          </svg>
          <p>
            ACME Industries Ltd.
            <br />
            Providing reliable tech since 1992
          </p>
        </aside>
        <nav>
          <h6 class="footer-title">Services</h6>
          <a class="link link-hover">Branding</a>
          <a class="link link-hover">Design</a>
          <a class="link link-hover">Marketing</a>
          <a class="link link-hover">Advertisement</a>
        </nav>
        <nav>
          <h6 class="footer-title">Company</h6>
          <a class="link link-hover">About us</a>
          <a class="link link-hover">Contact</a>
          <a class="link link-hover">Jobs</a>
          <a class="link link-hover">Press kit</a>
        </nav>
        <nav>
          <h6 class="footer-title">Legal</h6>
          <a class="link link-hover">Terms of use</a>
          <a class="link link-hover">Privacy policy</a>
          <a class="link link-hover">Cookie policy</a>
        </nav>
      </footer>
    </div>

    <!-- Sidebar/Drawer -->
    <div class="drawer-side">
      <label for="drawer-toggle" class="drawer-overlay"></label>
      <aside class="bg-base-100 min-h-screen w-60">
        <div class="bg-base-100/90 navbar shadow-xs sticky top-0 z-20 items-center gap-2 px-4 py-2 backdrop-blur lg:flex">
          <a href="/" class="flex shrink-0 items-center gap-2">
            <img src="/images/logo.png" alt="Admin Panel" class="circle-avatar h-10 w-10">
            <h2 class="text-xl font-semibold">MentorHer</h2>
          </a>
        </div>
        <ul class="menu mt-5 w-full px-4 py-0">
          <x-daisyui.menu-item icon="fas fa-home" text="Dashboard" href="" active="admin-dashboard" />
          <x-daisyui.menu-item icon="fas fa-users" text="Users" href="newadmin" active="newadmin" />

          <x-daisyui.menu-item icon="fas fa-chart-bar" text="Analytics" href="" active="-analytics" />
          <x-daisyui.menu-item icon="fas fa-cog" text="Settings" href="" active="admin-settings" />
          <x-daisyui.menu-item icon="fas fa-sign-in-alt" text="Login" href="login" active="login" />
          <x-daisyui.menu-group icon="fas fa-folder" text="Content">
            <x-daisyui.menu-item icon="fas fa-pen-to-square" text="Posts" href="" active="admin-new3" />
            <x-daisyui.menu-item icon="fas fa-file-lines" text="Pages" href="" active="admin-new1" />
            <x-daisyui.menu-item icon="fas fa-images" text="Media" href="" active="admin-new2" />
          </x-daisyui.menu-group>
        </ul>
      </aside>
    </div>
  </div>
  @stack('js')
  @yield('js')
</body>

</html>
