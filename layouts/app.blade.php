@php
    // Read theme cookie
    $theme = request()->cookie('theme', 'light');
@endphp

<!doctype html>
<html lang="en" class="{{ $theme === 'dark' ? 'dark' : '' }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>@yield('title', 'Dark Mode Example')</title>

  <!-- Tailwind via CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
  
  <script>
    tailwind.config = { darkMode: 'class' }
  </script>

  <style>
    body {
      transition: background-color 0.3s, color 0.3s;
    }
  </style>
</head>

<body class="min-h-screen bg-white text-black dark:bg-gray-900 dark:text-gray-100 p-8">

  <div class="flex justify-between items-center mb-8">
    <button id="themeToggle" class="px-4 py-2 border rounded-md dark:border-gray-700 bg-gray-200 dark:bg-gray-800">
      Toggle Theme
    </button>
  </div>

  <div class="p-6 bg-gray-100 dark:bg-gray-800 rounded shadow">
      @yield('content')
  </div>

  <script>
    // Cookie helpers
    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        const d = new Date();
        d.setTime(d.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + d.toUTCString();
      }
      document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
      const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
      return v ? v.pop() : "";
    }

    const html = document.documentElement;

    // Apply saved theme on load
    const saved = getCookie('theme');
    if (saved === 'dark') html.classList.add('dark');

    // Toggle theme
    document.getElementById('themeToggle').addEventListener('click', () => {
      html.classList.toggle('dark');
      const isDark = html.classList.contains('dark');
      setCookie('theme', isDark ? 'dark' : 'light', 365);
    });
  </script>

</body>
</html>


