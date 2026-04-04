<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">

        {{-- En-tête --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600">
                    Tableau de Bord
                </h1>
                <p class="text-gray-500 font-medium mt-2">
                    Vous avez <span class="text-indigo-600 font-bold">{{ $allTasks->count() }}</span> tâches au total.
                </p>
            </div>

            {{-- Bouton ajouter --}}
            <a href="{{ route('tasks.create') }}"
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nouvelle tâche
            </a>
        </div>

        {{-- Filtres --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php
                $filters = [
                    null => 'Toutes',
                    'todo' => 'À faire',
                    'in_progress' => 'En cours',
                    'done' => 'Terminées',
                ];
            @endphp

            @foreach($filters as $value => $label)
                <a href="{{ $value ? route('tasks.index', ['status' => $value]) : route('tasks.index') }}"
                   class="px-4 py-2 rounded-lg border text-sm font-semibold transition-all
                   {{ $status === $value || ($value === null && $status === null)
                        ? 'bg-indigo-600 text-white border-indigo-600 shadow-md'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-indigo-300 hover:bg-indigo-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="mb-6 flex items-center p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded-r-lg shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full border-collapse">
                <thead class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left font-bold">Détails</th>
                        <th class="px-6 py-4 text-center font-bold">Statut</th>
                        <th class="px-6 py-4 text-center font-bold">Priorité</th>
                        <th class="px-6 py-4 text-center font-bold">Échéance</th>
                        <th class="px-6 py-4 text-right font-bold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($tasks as $task)
                    <tr class="hover:bg-indigo-50/30 transition-colors">

                        {{-- Détails --}}
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $task->title }}</div>
                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ $task->description }}</div>
                        </td>

                        {{-- Statut avec Badge --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusClasses = [
                                    'todo' => 'bg-slate-100 text-slate-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'done' => 'bg-green-100 text-green-700',
                                ]
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClasses[$task->status] ?? 'bg-gray-100' }}">
                                {{ str_replace('_', ' ', $task->status) }}
                            </span>
                        </td>

                        {{-- Priorité avec Point --}}
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            <div class="flex items-center justify-center">
                                <span class="h-2 w-2 rounded-full mr-2 {{ $task->priority === 'high' ? 'bg-red-500' : ($task->priority === 'medium' ? 'bg-orange-400' : 'bg-green-400') }}"></span>
                                {{ ucfirst($task->priority) }}
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 text-center text-sm text-gray-500 font-medium">
                            {{ $task->due_date ? date('d/m/Y', strtotime($task->due_date)) : '-' }}
                        </td>

                       {{-- Actions --}}
<td class="px-6 py-4">
    <div class="flex justify-end items-center gap-2">
        
        {{-- Bouton Modifier --}}
        <a href="{{ route('tasks.edit', $task->id) }}" 
           class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 text-indigo-600 rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
        </a>

        {{-- Bouton Supprimer --}}
        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" 
              onsubmit="return confirm('Supprimer ?')" class="flex items-center m-0">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="inline-flex items-center justify-center w-9 h-9 bg-white border border-gray-200 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </form>

    </div>
</td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="bg-gray-50 p-4 rounded-full mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 font-medium italic">Aucune tâche disponible dans cette catégorie.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>