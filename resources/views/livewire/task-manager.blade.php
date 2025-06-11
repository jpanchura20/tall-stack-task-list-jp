<!-- Indie_Flower is custom font from google -->
<div class="min-h-screen bg-gradient-to-br p-6 font-[Indie_Flower]">

    <!-- Header Section -->
    <h1 class="text-4xl text-center font-bold mb-8 text-black-500 z-10">📝 My Task Notepad</h1>

    <!-- Form Section-->
    <div class="max-w-xl mx-auto bg-white rounded-xl shadow-xl/30 p-6 mb-8 relative">
        <form wire:submit.prevent="{{ $isEditing ? 'updateTask' : 'addTask' }}" class="space-y-4">

            <!-- Loading Spinner thats super simple would expand on this if more time-->
            @if ($isLoading)
                <div class="absolute top-0 right-0 p-2 animate-spin">
                    <svg class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3.5-3.5L12 1v4a10 10 0 1010 10h-2a8 8 0 01-8 8v-4l-3.5 3.5L12 23v-4a10 10 0 00-8-8z"></path>
                    </svg>
                </div>
            @endif

            <!-- Input fields for task title with simple validation -->
            <input wire:model="title" type="text" placeholder="Task title" class="w-full p-3 border border-black-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400" />
            @error('title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <!--Pulls category list from the database with simple validation -->
            <select wire:model="category_id"
                class="w-full p-3 border border-black-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                <option value="">Select category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <!-- Date input with Alpine.js for better UX and simple validation-->
            <div class="relative" x-data="{ show: false }">
                <input
                    x-ref="dateInput"
                    wire:model="due_date"
                    type="date"
                    class="w-full p-3 border border-black-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400 pl-10"
                    @click="$refs.dateInput.showPicker()"
                />
                <div
                    class="absolute inset-0 cursor-pointer"
                    @click="$refs.dateInput.showPicker()"
                ></div>
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none">
                    📅
                </div>
            </div>
            @error('due_date') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror

            <!--Submit buttons that alter based on if in edit mode or not -->
            <div class="flex gap-2 justify-end">
                <button type="submit"
                    class="px-6 py-2 {{ $isEditing ? 'bg-green-600' : 'bg-blue-600' }} text-white rounded shadow hover:opacity-90 transition cursor-pointer">
                    {{ $isEditing ? 'Update Task' : 'Add Task' }}
                </button>
                @if ($isEditing)
                    <button type="button" wire:click="resetForm"
                        class="px-6 py-2 bg-gray-400 text-white rounded shadow hover:opacity-90 transition cursor-pointer">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <!-- Tasks that display as sticky notes/notebook paper below also using alpine and sortable.js -->
    <div x-data="sortableTasks()" x-init="init" class="max-w-xl mx-auto space-y-4">
        @forelse ($tasks as $task)
            <div data-id="{{ $task->id }}"
                x-data="{ show: false }"
                x-init="setTimeout(() => show = true, 100)"
                x-show="show"
                x-transition.duration.500ms
                class="bg-yellow-200 rounded-lg shadow-md p-4 transform rotate-[-1deg] {{ $task->style_class }} cursor-pointer">
                <div class="flex flex-col sm:flex-col md:flex-row flex-wrap space-y-1 space-x-3 {{ $task->completed ? 'line-through text-gray-400 italic' : '' }}">
                <!-- Task Title -->
                    <p><span class="text-black text-lg align-middle mr-1">◉</span> {{ $task->title }} </p>
                <!-- Task Category with level of urgency -->
                    <p>
                        @php
                         $emoji = match($task->category->color) {
                                'green' => '🟢',
                                'yellow' => '🟡',
                                'red' => '🔴',
                                default => '⚪',
                         };
                        @endphp
                        {{ $emoji }} <span class="{{ $task->category->color === 'green' ? 'text-green-800' : ($task->category->color === 'yellow' ? 'text-yellow-800' : 'text-red-800') }}">{{ $task->category->name }}</span>
                    </p>
                <!-- Task Due Date with a default message if no date is set -->
                    <p> 📅 {{ $task->due_date ?? 'No Due Date' }}</p>
                </div>

                <!--Buttons Under Each Task, edit, delete, or mark completed (when completed theyll be movedd to bottom of list and crossed out) -->
                <div class="flex items-center gap-2 mt-2">
                    <button wire:click="editTask({{ $task->id }})" class="cursor-pointer" title="Edit">✏️</button>
                    <button wire:click="deleteTask({{ $task->id }})" class="cursor-pointer" title="Delete">❌</button>
                    <button wire:click="toggleCompleted({{ $task->id }})" class="text-green-600 hover:text-green-800 transition cursor-pointer" title="Toggle Complete">✔</button>
                </div>
            </div>
            @empty
                <div class="text-center text-black-500 font-bold mt-6 bg-yellow-200 rounded-lg shadow-md p-4 transform rotate-[-1deg] border-l-4 border-black-600">
                    No tasks found. Add one above to get started!
                </div>
            @endforelse
    </div>

    <!--Sortable library that allows drag-and-drop sorting hooked up with Livewire and Alpine.js-->
    <script>
        function sortableTasks() {
            return {
                init() {
                    new Sortable(this.$el, {
                        animation: 0,
                        onEnd: function (evt) {
                            let ids = [...evt.to.children].map(el => el.dataset.id);
                            Livewire.dispatch('reorderTasks', { ids });
                        }
                    });
                }
            }
        }
    </script>
</div>
