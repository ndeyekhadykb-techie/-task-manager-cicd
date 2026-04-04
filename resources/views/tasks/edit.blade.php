<script src="https://cdn.tailwindcss.com"></script>

<div class="container mx-auto max-w-2xl py-8 px-4">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier la tâche</h1>
        <a href="{{ route('tasks.show', $task) }}" class="text-sm text-gray-500 hover:text-gray-700">
            ← Retour
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-300 text-red-700 rounded p-4 mb-6">
            <ul class="list-disc list-inside space-y-1 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update', $task) }}" method="POST"
          class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')

        {{-- Titre --}}
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                Titre <span class="text-red-500">*</span>
            </label>
            <input type="text" id="title" name="title"
                   value="{{ old('title', $task->title) }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500
                          @error('title') border-red-500 @enderror">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                Description
            </label>
            <textarea id="description" name="description" rows="4"
                      class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                             focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Description optionnelle...">{{ old('description', $task->description) }}</textarea>
        </div>

        {{-- Statut & Priorité --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">
                    Statut <span class="text-red-500">*</span>
                </label>
                <select id="status" name="status"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['todo' => 'À faire', 'in_progress' => 'En cours', 'done' => 'Terminé'] as $value => $label)
                        <option value="{{ $value }}"
                            {{ old('status', $task->status) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">
                    Priorité <span class="text-red-500">*</span>
                </label>
                <select id="priority" name="priority"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                               focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute'] as $value => $label)
                        <option value="{{ $value }}"
                            {{ old('priority', $task->priority) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Date limite --}}
        <div>
            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
                Date limite
            </label>
            <input type="date" id="due_date" name="due_date"
                   value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                   class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Boutons --}}
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('tasks.show', $task) }}"
               class="px-4 py-2 text-sm text-gray-600 border border-gray-300 rounded-md hover:bg-gray-50">
                Annuler
            </a>
            <button type="submit"
                    class="px-5 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                💾 Enregistrer
            </button>
        </div>
    </form>
</div>

