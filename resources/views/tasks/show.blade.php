<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-cyan-50 py-10 px-4">
    <div class="max-w-3xl mx-auto">
        
        {{-- Retour --}}
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800 mb-6 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Retour au tableau de bord
        </a>

        <div class="bg-white rounded-3xl shadow-xl shadow-indigo-100/50 overflow-hidden border border-gray-100">
            {{-- Header de la carte --}}
            <div class="p-8 border-b border-gray-50 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $task->title }}</h1>
                    <div class="flex gap-3">
                        {{-- Badge Statut --}}
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ str_replace('_', ' ', $task->status) }}
                        </span>
                        {{-- Badge Priorité --}}
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-slate-100 text-slate-700">
                            Priorité : {{ $task->priority }}
                        </span>
                    </div>
                </div>
                
                {{-- Actions rapides --}}
                <div class="flex gap-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="p-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Corps --}}
            <div class="p-8 space-y-8">
                {{-- Description --}}
                <section>
                    <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Description</h2>
                    <p class="text-gray-700 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        {{ $task->description ?: 'Aucune description fournie.' }}
                    </p>
                </section>

                <div class="grid grid-cols-2 gap-8">
                    {{-- Date Échéance --}}
                    <section>
                        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Date limite</h2>
                        <div class="flex items-center text-gray-700 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d F Y') : 'Non définie' }}
                        </div>
                    </section>

                    {{-- Date Création --}}
                    <section>
                        <h2 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-3">Créée le</h2>
                        <div class="text-gray-500 italic">
                            {{ $task->created_at->format('d/m/Y à H:i') }}
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>