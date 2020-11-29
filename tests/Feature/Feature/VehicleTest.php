<?php

namespace Tests\Feature\Feature;

use App\Models\Vehicle;
use Tests\TestCase;

class VehicleTest extends TestCase
{

    /**
     * A feature test.
     *
     * @return void
     */
    public function testRegisterVehicle()
    {
        $vehicle = Vehicle::factory()->create();
        $this->json('POST', '/api/register_vehicle/' . $vehicle->register_number)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
            ]);
    }

    /**
     * A feature test.
     *
     * @return void
     */
    public function testSignOutVehicle()
    {
        $vehicle = Vehicle::factory()->create();

        $this->json('POST', '/api/register_vehicle/' . $vehicle->register_number);

        $this->json('DELETE', '/api/sign_out_vehicle/' . $vehicle->register_number)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'register_number',
                    'fees',
                ],
            ]);
    }

    /**
     * A feature test.
     *
     * @return void
     */
    public function testCheckVehicleFees()
    {
        $vehicle = Vehicle::factory()->create();

        $this->json('POST', '/api/register_vehicle/' . $vehicle->register_number);

        $this->json('DELETE', '/api/sign_out_vehicle/' . $vehicle->register_number)
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'register_number',
                    'fees',
                ],
            ]);
    }

}
