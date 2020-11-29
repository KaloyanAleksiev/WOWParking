<?php

namespace Tests\Feature\Feature;

use Tests\TestCase;

class SlotTest extends TestCase
{
    /**
     * A feature test.
     *
     * @return void
     */
    public function testAvailableSlots()
    {
        $this->json('GET', 'api/available_slots')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'free_slots_count',
                ],
            ]);
    }
}
