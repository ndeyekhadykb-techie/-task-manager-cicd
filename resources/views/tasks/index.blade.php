<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 py-10 px-4">
    <div class="max-w-6xl mx-auto">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-600">
                    Tableau de Bord
                </h1>
                <p class="text-gray-500 font-medium mt-2">Vous avez <span class="text-indigo-600 font-bold">{{ $tasks->count() }}</span> tâches en cours.</p>
            </div>
            <a href="{{ route('tasks.create') }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-bold rounded-2xl hover:bg-indigo-700 hover:scale-105 transition-all duration-300 shadow-xl shadow-indigo-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Ajouter une tâche
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white/60 backdrop-blur-md p-6 rounded-3xl border border-white shadow-sm">
                <p class="text-sm text-gray-500 font-semibold uppercase tracking-wider">À faire</p>
                <p class="text-3xl font-black text-gray-800">{{ $tasks->where('status', 'todo')->count() }}</p>
            </div>
            <div class="bg-blue-50/50 backdrop-blur-md p-6 rounded-3xl border border-blue-100 shadow-sm">
                <p class="text-sm text-blue-500 font-semibold uppercase tracking-wider">En cours</p>
                <p class="text-3xl font-black text-blue-800">{{ $tasks->where('status', 'in_progress')->count() }}</p>
            </div>
            <div class="bg-green-50/50 backdrop-blur-md p-6 rounded-3xl border border-green-100 shadow-sm">
                <p class="text-sm text-green-500 font-semibold uppercase tracking-wider">Terminées</p>
                <p class="text-3xl font-black text-green-800">{{ $tasks->where('status', 'done')->count() }}</p>
            </div>
        </div>

        <div class="bg-white/80 backdrop-blur-lg rounded-3xl shadow-2xl shadow-gray-200/50 border border-white overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-indigo-900/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-sm font-bold">Détails</th>
                        <th class="px-8 py-5 text-sm font-bold">Progression</th>
                        <th class="px-8 py-5 text-sm font-bold">Urgence</th>
                        <th class="px-8 py-5 text-sm font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
    @forelse($tasks as $task)
    <tr class="bg-white hover:bg-gray-50/50 transition-colors">
        
        <td class="px-8 py-6">
            <div class="text-lg font-bold text-gray-800">{{ $task->title }}</div>
            <div class="text-sm text-gray-400 mt-1 italic">{{ $task->description }}</div>
        </td>

        <td class="px-8 py-6">
            <span class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 text-[10px] font-black uppercase ring-1 ring-blue-200">
                {{ $task->status }}
            </span>
        </td>

        <td class="px-8 py-6">
            <span class="text-xs font-bold text-gray-400 uppercase">{{ $task->priority }}</span>
        </td>

        <td class="px-8 py-6 text-right">
            <div class="flex justify-end items-center gap-4">
                <a href="#" class="text-indigo-500 hover:text-indigo-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </a>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Supprimer ?')" class="text-red-500 hover:text-red-700 transition-colors">
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
        <td colspan="4" class="px-8 py-10 text-center text-gray-400 font-medium italic">
            Aucune tâche disponible.
        </td>
    </tr>
    @endforelse
</tbody>
            </table>
        </div>
    </div>
</div>