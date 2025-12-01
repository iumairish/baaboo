<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketNoteRequest;
use App\Services\DBConnectService;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for admin ticket management.
 */
class TicketController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService,
        private readonly DBConnectService $databaseService
    ) {}

    /**
     * Display all tickets from all departments.
     */
    public function index(): View
    {
        $tickets = $this->ticketService->getAllTickets();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * Display a specific ticket.
     */
    public function show(Request $request, int $id): View|RedirectResponse
    {
        $connection = $request->query('connection');

        if (! $connection) {
            // Try to find the ticket across all databases
            $ticket = $this->ticketService->findTicketAcrossAllDatabases($id);
        } else {
            $ticket = $this->ticketService->findTicket($id, $connection);
        }

        if (! $ticket) {
            return redirect()
                ->route('admin.tickets.index')
                ->with('error', 'Ticket not found.');
        }

        // Load notes
        $ticket->load('notes');

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'connection' => $ticket->getConnectionName(),
        ]);
    }

    /**
     * Add a note to a ticket.
     */
    public function addNote(
        TicketNoteRequest $request,
        int $id
    ): RedirectResponse {
        $connection = $request->input('connection');

        try {
            $this->ticketService->addNoteToTicket(
                ticketId: $id,
                connection: $connection,
                noteContent: $request->input('note')
            );

            return redirect()
                ->back()
                ->with('success', 'Note added successfully. Ticket status updated to "Noted".');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to add note. Please try again.');
        }
    }
}
