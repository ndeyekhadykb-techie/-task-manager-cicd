<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-screen bg-gray-50 py-8 px-4">
    <div class="max-w-2xl mx-auto bg-white rounded-xl shadow-md overflow-hidden p-8">
        
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Créer une nouvelle tâche</h1>
            <p class="text-gray-500">Remplissez les informations ci-dessous pour organiser votre travail.</p>
        </div>

        <form action="{{ route('tasks.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Titre de la tâche</label>
                <input type="text" name="title" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition"
                    placeholder="Ex: Finir le rapport CI/CD">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optionnel)</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Détails de la tâche..."></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white outline-none">
                        <option value="todo">À faire</option>
                        <option value="in_progress">En cours</option>
                        <option value="done">Terminé</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                    <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-white outline-none">
                        <option value="low">Basse</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date limite</label>
                <input type="date" name="due_date" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="#" class="text-gray-600 hover:text-gray-800 font-medium">Annuler</a>
                <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                    Créer la tâche
                </button>
            </div>
        </form>
    </div>
</div>