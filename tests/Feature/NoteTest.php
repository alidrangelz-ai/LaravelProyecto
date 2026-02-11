<?php

namespace Tests\Feature;

use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_notes()
    {
        $user = User::factory()->create();
        $notes = Note::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('notes.index'));

        $response->assertStatus(200);
    }

    public function test_user_can_create_note()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('notes.store'), [
            'title' => 'Test Note',
            'content' => 'Content of test note',
            'color' => '#ffffff',
            'is_pinned' => false,
        ]);

        $this->assertDatabaseHas('notes', [
            'title' => 'Test Note',
            'user_id' => $user->id,
        ]);

        $response->assertRedirect(route('notes.index'));
    }

    public function test_user_can_update_their_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put(route('notes.update', $note), [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'color' => '#000000',
            'is_pinned' => true,
        ]);

        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => 'Updated Title',
            'is_pinned' => true,
        ]);
    }

    public function test_user_cannot_update_others_note()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->put(route('notes.update', $note), [
            'title' => 'Hacked Title',
            'content' => 'Updated Content',
        ]);

        $response->assertForbidden();
        
        $this->assertDatabaseHas('notes', [
            'id' => $note->id,
            'title' => $note->title, // Should stick to original
        ]);
    }

    public function test_user_can_delete_their_note()
    {
        $user = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('notes.destroy', $note));

        $this->assertDatabaseMissing('notes', ['id' => $note->id]);
    }

    public function test_user_cannot_delete_others_note()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $note = Note::factory()->create(['user_id' => $user1->id]);

        $response = $this->actingAs($user2)->delete(route('notes.destroy', $note));

        $response->assertForbidden();
        $this->assertDatabaseHas('notes', ['id' => $note->id]);
    }
}
