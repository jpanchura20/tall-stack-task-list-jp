<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Category;

use Livewire\Attributes\Layout;

class TaskManager extends Component
{
    //variables for the task form add/edit
    public $title = '';
    public $category_id;
    public $due_date;
    public $categories = [];
    public $isEditing = false;
    public $editTaskId = null;
    //variable to show loading state
    public bool $isLoading = false;
    //listeners for task reordering via drag and drop
    protected $listeners = ['reorderTasks'];

    protected $rules = [
        'title' => 'required|string|min:3',
        'category_id' => 'required|exists:categories,id',
        'due_date' => 'nullable|date',
    ];

    public function mount()
    {
        $this->categories = Category::all();
    }

    // Method to reset the form fields after adding or editing a task
    public function resetForm()
    {
        $this->isEditing = false;
        $this->editTaskId = null;
        $this->title = '';
        $this->category_id = '';
        $this->due_date = '';
    }

    // Method to generate a random style class for the task border, youll see more when you look at the view
    // This is used to give each task a unique border color
    private function generateTaskStyle(): string
    {
        $borderColors = [
            'border-l-4 border-yellow-600',
            'border-l-4 border-pink-600',
            'border-l-4 border-green-600',
            'border-l-4 border-blue-600',
            'border-l-4 border-purple-600',
            'border-l-4 border-red-600',
        ];

        return $borderColors[array_rand($borderColors)];
    }

    // Method to handle form submission for adding a new task
    public function addTask()
    {
        $this->validate();

        // Increment position of all existing tasks since the new task will be added at the top
        Task::query()->increment('position');

        Task::create([
            'title' => $this->title,
            'category_id' => $this->category_id,
            'due_date' => $this->due_date,
            'position' => 0,
            'style_class' => $this->generateTaskStyle(),
        ]);

        $this->resetForm();
        $this->dispatch('taskUpdated');
    }

    // Method to handle editing an existing task
    public function editTask($id)
    {
        //Looks for the task by ID and sets the form fields for editing
        $task = Task::findOrFail($id);

        $this->isEditing = true;
        $this->editTaskId = $task->id;
        $this->title = $task->title;
        $this->category_id = $task->category_id;
        $this->due_date = $task->due_date;

        logger("Editing Task ID: $id");
    }

    // Method to handle updating an existing task via push
    public function updateTask()
    {
        $this->isLoading = true;
        $this->validate();

        // Update the task with the new values
        Task::where('id', $this->editTaskId)->update([
            'title' => $this->title,
            'category_id' => $this->category_id,
            'due_date' => $this->due_date,
        ]);

        $this->resetForm();
        $this->isLoading = false;
        $this->dispatch('taskUpdated');
    }

    // Method to handle deleting a task
    public function deleteTask($id)
    {
        logger("Deleting task ID: $id");
        Task::findOrFail($id)->delete();

        $this->dispatch('taskUpdated');
    }

    // Method to toggle the completion status of a task which will cross off the task
    public function toggleCompleted($taskId)
    {
        logger("Worked");
        $task = Task::findOrFail($taskId);
        $task->completed = !$task->completed;
        $task->save();

        $this->dispatch('taskUpdated');
    }

    // Method to reorder tasks based on the provided IDs from drag and drop
    public function reorderTasks($ids)
    {
        foreach ($ids as $index => $id) {
            Task::where('id', $id)->update(['position' => $index]);
        }
    }

    // Method to render the component view also loads by completed and position
    public function render()
    {
        $tasks = Task::with('category')
            ->orderByRaw('completed ASC, position ASC')
            ->get();

        return view('livewire.task-manager', compact('tasks'));
    }


}
