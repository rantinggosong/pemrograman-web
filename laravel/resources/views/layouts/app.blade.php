<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Pesan Makanan & Minuman' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'primary-light': '#D5B99F',
              'dark': '#1A110A',
              'gray-500': '#5C4A3D',
              'gray-800': '#2C1E16',
            },
            fontFamily: {
              'sans': ['"Plus Jakarta Sans"', 'sans-serif'],
              'serif': ['"Playfair Display"', 'serif'],
            }
          }
        }
      }
    </script>
</head>
<body class="font-sans text-gray-800 bg-[#D5B99F] antialiased min-h-screen bg-[radial-gradient(circle_at_10%_20%,rgba(255,255,255,0.15)_0%,transparent_40%),radial-gradient(circle_at_90%_80%,rgba(255,255,255,0.15)_0%,transparent_40%)]">
    @yield('content')
</body>
</html>
