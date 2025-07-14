@props(['title' => 'Admin'])
<!DOCTYPE html>
<html lang="en" data-theme="">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $title }}</title>

  @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/crud-edit.js'])

  @stack('css')
  @yield('css')

  <script>
    /*to prevent Firefox Flash of unstyled content*/
    let FF_FOUC_FIX;
  </script>
</head>

<body class="h-full" tabindex="0">
  <div class="drawer xl:drawer-open">
    <input id="drawer-toggle" type="checkbox" class="drawer-toggle">

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
            <div tabindex="0" class="btn btn-ghost btn-circle avatar">
              <div class="w-10 rounded-full">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}" alt="Avatar">
              </div>
            </div>
            <ul tabindex="0" class="menu menu-md dropdown-content bg-base-100 rounded-box w-30 z-[1] mt-3 p-2 shadow">
              <li><a href=""><i class="fas fa-user mr-2"></i>Profile</a></li>
              <li><a href="{{ route('logout') }}"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a></li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="bg-base-200/70 p-6">
        {{ $slot }}
      </div>
    </div>

    <!-- Sidebar/Drawer -->
    <div class="drawer-side">
      <label for="drawer-toggle" class="drawer-overlay"></label>
      <aside class="bg-base-100 min-h-screen w-60">
        <div class="bg-base-100/90 navbar shadow-xs sticky top-0 z-20 items-center gap-2 px-4 py-2 backdrop-blur lg:flex">
          <a href="/" class="flex shrink-0 items-center gap-2">
            <img src="{{ Vite::asset('public/images/logo.png') }}" alt="Admin Panel" class="circle-avatar h-10 w-10">
            <h2 class="text-xl font-semibold">Admin Panel</h2>
          </a>
        </div>
        <ul class="menu w-full gap-2">
          <x-daisyui.menu-item icon="fas fa-home" text="Dashboard" route="admin.home" active="admin" />

          <x-daisyui.menu-separator />
          <x-daisyui.menu-item icon="fas fa-users" text="Users" href="newadmin" active="admin/users" />
          <x-daisyui.menu-item icon="fas fa-chart-bar" text="Analytics" href="" active="-analytics" />

          <x-daisyui.menu-separator />
          <x-daisyui.menu-item icon="fas fa-cog" text="Settings" href="" active="admin/settings" />

          <x-daisyui.menu-separator />
          <x-daisyui.menu-group icon="fas fa-folder" text="Content">
            <x-daisyui.menu-item icon="fas fa-pen-to-square" text="Posts" href="" active="admin-new3" />
            <x-daisyui.menu-item icon="fas fa-file-lines" text="Pages" href="" active="admin-new1" />
            <x-daisyui.menu-item icon="fas fa-images" text="Media" href="" active="admin-new2" />
          </x-daisyui.menu-group>

          <x-daisyui.menu-separator />
          <x-daisyui.menu-item icon="fas fa-sign-in-alt" class="w-full max-w-full" text="Logout" route="logout" active="logout" />
        </ul>
      </aside>
    </div>
  </div>

  @foreach (['success', 'info', 'warning', 'error'] as $type)
    @if (session($type))
      @php
        $message = session($type);
        if (is_array($message)) {
            $message = implode('<li>', $message);
        }
      @endphp
      <x-daisyui.toast timeout="5000">
        <x-daisyui.alert type="{{ $type }}" title="{{ $message }}" dismissable />
      </x-daisyui.toast>
    @endif
  @endforeach

  @stack('js')
  @yield('js')
</body>

</html>
