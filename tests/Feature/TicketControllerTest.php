<?php

namespace Tests\Feature;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Should be able to register a new ticket as expected.
     *
     * @return void
     */
    public function testRegisterNewTicket()
    {
        $response = $this->post('/api/create-ticket', [
            'name' => 'User Test',
            'numbers' => [1, 2, 3, 4, 5, 60],
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * Should not be able to register a new ticket with invalid numbers.
     *
     * @return void
     */
    public function testRegisterNewTicketWithInvalidNumbers()
    {
        $response = $this->post('/api/create-ticket', [
            'name' => 'User Test',
            'numbers' => [0, 2, 3, 4, 5, 61],
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Should be able to show a ticket as expected.
     *
     * @return void
     */
    public function testShowTicket()
    {
        $ticketCode = Ticket::factory()->create()->uuid;

        $response = $this->get("/api/tickets/{$ticketCode}");

        $response->assertStatus(Response::HTTP_OK);
    }
}
