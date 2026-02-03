<aside class="h-full bg-white dark:bg-gray-800 shadow-lg flex flex-col">

    <!-- Brand -->
    <div class="px-6 py-6 bg-gradient-to-r from-indigo-600 to-indigo-500">
        <h2 class="text-xl font-bold text-white tracking-wide">
            Admin Panel
        </h2>
        <p class="text-xs text-indigo-100 mt-1">
            Management Console
        </p>
    </div>

    <!-- Menu -->
    <nav class="flex-1 px-3 py-4 space-y-1">

        @php
            $linkBase = 'flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition';
            $active   = 'bg-indigo-100 text-indigo-700 dark:bg-gray-700';
            $inactive = 'text-gray-700 dark:text-gray-200 hover:bg-indigo-50 dark:hover:bg-gray-700 hover:text-indigo-700';
        @endphp

        <a href="/admin/dashboard"
           class="{{ $linkBase }} {{ request()->is('admin/dashboard') ? $active : $inactive }}">
            <span class="text-lg">📊</span>
            Dashboard
        </a>

        <a href="#"
           class="{{ $linkBase }} {{ $inactive }}">
            <span class="text-lg">👥</span>
            Users
        </a>

        <a href="#"
           class="{{ $linkBase }} {{ $inactive }}">
            <span class="text-lg">⚙️</span>
            Settings
        </a>

    </nav>

    <!-- Footer -->
    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
        <p class="text-xs text-gray-500">Logged in as</p>
        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">
            {{ auth()->user()->name }}
        </p>
    </div>

</aside>
