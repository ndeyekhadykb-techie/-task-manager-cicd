<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    // ─── 1. Listing des tâches ────────────────────────────────────────────────

    public function test_can_list_tasks(): void
    {
        Task::factory()->count(3)->create();

        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
        $response->assertViewHas('tasks');
        $this->assertDatabaseCount('tasks', 3);
    }

    // ─── 2. Consultation du détail d'une tâche ────────────────────────────────

    public function test_can_show_task(): void
    {
        $task = Task::factory()->create([
            'title'  => 'Tâche de détail',
            'status' => 'todo',
        ]);

        $response = $this->get(route('tasks.show', $task));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.show');
        $response->assertViewHas('task', $task);
    }

    // ─── 3. Création d'une tâche ─────────────────────────────────────────────

    public function test_can_create_task(): void
    {
        $data = [
            'title'       => 'Nouvelle tâche de test',
            'description' => 'Description de la tâche',
            'status'      => 'todo',
            'priority'    => 'medium',
            'due_date'    => '2026-12-31',
        ];

        $response = $this->post(route('tasks.store'), $data);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', [
            'title'    => 'Nouvelle tâche de test',
            'status'   => 'todo',
            'priority' => 'medium',
        ]);
        $this->assertDatabaseCount('tasks', 1);
    }

    // ─── 4. Modification d'une tâche ─────────────────────────────────────────

    public function test_can_update_task(): void
    {
        $task = Task::factory()->create([
            'title'  => 'Titre original',
            'status' => 'todo',
        ]);

        $updatedData = [
            'title'       => 'Titre modifié',
            'description' => 'Description mise à jour',
            'status'      => 'in_progress',
            'priority'    => 'high',
            'due_date'    => '2026-06-15',
        ];

        $response = $this->put(route('tasks.update', $task), $updatedData);

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tasks', [
            'id'       => $task->id,
            'title'    => 'Titre modifié',
            'status'   => 'in_progress',
            'priority' => 'high',
        ]);
        $this->assertDatabaseMissing('tasks', [
            'id'    => $task->id,
            'title' => 'Titre original',
        ]);
    }

    // ─── 5. Suppression d'une tâche ──────────────────────────────────────────

    public function test_can_delete_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('tasks.destroy', $task));

        $response->assertRedirect(route('tasks.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
        $this->assertDatabaseCount('tasks', 0);
    }
}
