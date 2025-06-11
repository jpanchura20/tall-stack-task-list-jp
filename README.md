# 📓 TALL Stack To-Do List App

I went off design here a bit and kinda just played around with some concepts. I like the idea of making the tasks sticky notes/notebook paper since its nostalgic and easy to look at.
I used the Tall stack here and added a JS library for sorting and dragging. Below are some of the instructions and features. I used Laragon for this as well for my server setup.

## 🛠 Features

- ✅ Create, update, and delete tasks
- 🔁 Drag-and-drop to reorder tasks
- 🎨 Sticky note-style task cards with randomized pastel border colors
- 🕒 Due date picker (click anywhere in the field to open the calendar)
- ⚙️ Live validation & form feedback
- ⚡ Instant UI updates via Livewire
- 🔄 Loading spinner while saving tasks
- 📂 Category-based color indicators (e.g., Urgent = Red)
- 📌 Completed tasks auto-sort to the bottom

## 🧱 Stack

- **Laravel 11**
- **Livewire 3**
- **Alpine.js 3**
- **Tailwind CSS 4**
- **Vite** (for asset building)
- **Sortable.js** (for drag-and-drop support)

## 🧩 Notes

-Category color classes (e.g. text-red-500, text-yellow-500) are stored in the DB for styling.
-Each task is assigned a random style_class (e.g. border-l-4 border-pink-600) when created.
-Uses x-ref and Alpine.js to make the entire due date input clickable.

## 🚀 Getting Started

1. composer install
2. npm install
3. php artisan key:generate
4. php artisan migrate
5. npm run dev or npm run build
6. php artisan serve


