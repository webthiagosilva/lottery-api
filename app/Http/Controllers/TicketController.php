<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\LotteryService;
use App\Models\Ticket;
use Symfony\Component\HttpFoundation\Response;
use App\Jobs\LotteryDrawJob;

/**
 * @group Api - Tickets
 * @authenticated
 *
 * APIs for managing tickets
 */
class TicketController extends Controller
{
    /**
     * Service to manage tickets
     * @var LotteryService
     */
    protected LotteryService $service;

    /**
     * TicketController constructor.
     * @param LotteryService $service
     */
    public function __construct(LotteryService $service)
    {
        $this->service = $service;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @header Authorization: Bearer {token}
     *
     * @bodyParam name string required The name of the ticket. Example: John Doe
     * @bodyParam numbers array required The numbers of the ticket. Example: [1, 2, 3, 4, 5, 6]
     *
     * @responseFile responses/tickets.post.json
     *
     * @param  StoreTicketRequest  $request
     * @return TicketResource
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        return response()->json([
            'success' => true,
            'message' => __('lottery.ticket.store.success'),
            'data' => [
                'ticket_code' => $this->service->storeTicket($data)->uuid,
            ],
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @header Authorization: Bearer {token}
     *
     * @urlParam ticket string required The code of the ticket. Example: d11ba63d-7671-4eb4-98ce...
     *
     * @responseFile responses/tickets.get.json
     *
     * @param  Ticket  $ticket
     * @return TicketResource
     */
    public function show(Ticket $ticket)
    {
        return new TicketResource(
            $this->service->showTicket($ticket->uuid)
        );
    }
}
