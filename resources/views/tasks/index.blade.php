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
        </div>

        {{-- Cartes statistiques --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white shadow-sm">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">À faire</p>
                <p class="text-3xl font-black text-gray-800">{{ $allTasks->where('status', 'todo')->count() }}</p>
            </div>
            <div class="bg-blue-50/50 backdrop-blur-md p-6 rounded-3xl border border-blue-100 shadow-sm">
                <p class="text-sm text-blue-500 font-semibold uppercase tracking-wider">En cours</p>
                <p class="text-3xl font-black text-blue-800">{{ $allTasks->where('status', 'in_progress')->count() }}</p>
            </div>
            <div class="bg-green-50/50 backdrop-blur-md p-6 rounded-3xl border border-green-100 shadow-sm">
                <p class="text-sm text-green-500 font-semibold uppercase tracking-wider">Terminées</p>
                <p class="text-3xl font-black text-green-800">{{ $allTasks->where('status', 'done')->count() }}</p>
            </div>
        </div>

        {{-- Onglets de filtre par statut --}}
        <div class="flex flex-wrap gap-2 mb-6">
            @php
                $filters = [
                    null          => ['label' => 'Toutes', 'active' => 'bg-indigo-600 text-white shadow-indigo-200', 'inactive' => 'bg-white text-gray-600 hover:bg-indigo-50'],
                    'todo'        => ['label' => 'À faire', 'active' => 'bg-gray-700 text-white', 'inactive' => 'bg-white text-gray-600 hover:bg-gray-50'],
                    'in_progress' => ['label' => 'En cours', 'active' => 'bg-blue-600 text-white shadow-blue-200', 'inactive' => 'bg-white text-blue-600 hover:bg-blue-50'],
                    'done'        => ['label' => 'Terminées', 'active' => 'bg-green-600 text-white shadow-green-200', 'inactive' => 'bg-white text-green-600 hover:bg-green-50'],
                ];
            @endphp

            @foreach($filters as $value => $filter)
                @php $isActive = $status === $value || ($value === null && $status === null); @endphp
                <a href="{{ $value ? route('tasks.index', ['status' => $value]) : route('tasks.index') }}"
                   class="px-5 py-2 rounded-xl font-semibold text-sm border transition-all duration-200 shadow-sm
                          {{ $isActive ? $filter['active'] . ' shadow-md' : $filter['inactive'] . ' border-gray-200' }}">
                    {{ $filter['label'] }}
                    @if($value === null)
                        <span class="ml-1 text-xs opacity-75">({{ $allTasks->count() }})</span>
                    @elseif($value === 'todo')
                        <span class="ml-1 text-xs opacity-75">({{ $allTasks->where('status', 'todo')->count() }})</span>
                    @elseif($value === 'in_progress')
                        <span class="ml-1 text-xs opacity-75">({{ $allTasks->where('status', 'in_progress')->count() }})</span>
                    @elseif($value === 'done')
                        <span class="ml-1 text-xs opacity-75">({{ $allTasks->where('status', 'done')->count() }})</span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Message de filtre actif --}}
        @if($status)
            <p class="text-sm text-gray-500 mb-4">
                Affichage : <span class="font-semibold text-indigo-600">{{ $tasks->count() }}</span> tâche(s) filtrée(s).
                <a href="{{ route('tasks.index') }}" class="ml-2 text-indigo-400 hover:text-indigo-600 underline">Réinitialiser</a>
            </p>
        @endif

        {{-- Message flash succès --}}
        @if(session('success'))
            <div class="mb-4 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- Tableau des tâches --}}
        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl shadow-gray-200/50 border border-white overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-indigo-900/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-sm font-bold">Détails</th>
                        <th class="px-8 py-5 text-sm font-bold">Statut</th>
                        <th class="px-8 py-5 text-sm font-bold">Urgence</th>
                        <th class="px-8 py-5 text-sm font-bold">Échéance</th>
                        <th class="px-8 py-5 text-sm font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($tasks as $task)
                    <tr class="bg-white hover:bg-gray-50/50 transition-colors">

                       {{-- Dans ton index.blade.php, remplace la ligne du titre par celle-ci --}}
<td class="px-6 py-4">
    <a href="{{ route('tasks.show', $task->id) }}" class="group block">
        <div class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
            {{ $task->title }}
        </div>
        <div class="text-sm text-gray-500 truncate max-w-xs">
            {{ $task->description }}
        </div>
    </a>
</td>

                        <td class="px-8 py-6">
                            @if($task->status === 'todo')
                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-[10px] font-black uppercase ring-1 ring-gray-200">
                                    À faire
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-black uppercase ring-1 ring-blue-200">
                                    En cours
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-[10px] font-black uppercase ring-1 ring-green-200">
                                    Terminée
                                </span>
                            @endif
                        </td>

                        <td class="px-8 py-6">
                            @if($task->priority === 'high')
                                <span class="text-xs font-bold text-red-500 uppercase">Haute</span>
                            @elseif($task->priority === 'medium')
                                <span class="text-xs font-bold text-yellow-500 uppercase">Moyenne</span>
                            @else
                                <span class="text-xs font-bold text-gray-400 uppercase">Basse</span>
                            @endif
                        </td>

                        <td class="px-8 py-6">
                            <span class="text-sm text-gray-500">
                                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d/m/Y') : '—' }}
                            </span>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end items-center gap-4">
                                <a href="#" class="text-indigo-500 hover:text-indigo-700 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </a>
                                <form action="#" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Supprimer cette tâche ?')" class="text-red-500 hover:text-red-700 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-gray-400 font-medium italic">
                            Aucune tâche{{ $status ? ' pour ce statut' : '' }}.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>
