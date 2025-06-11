<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tall Task List Tool By JP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!--Custom font that looks like scribbling -->
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">

    <!--Custom js library which allows drag-and-drop sorting -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">
    <div class="min-h-screen p-6">
        {{ $slot }}
    </div>
    @livewireScripts
</body>
</html>
