<?php

namespace App\Http\Controllers;

use App\DTOs\TicketData;
use App\Enums\TicketType;
use App\Http\Requests\TicketSubmissionRequest;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Controller for customer-facing ticket submission.
 */
class TicketSubmissionController extends Controller
{
    public function __construct(
        private readonly TicketService $ticketService
    ) {}

    /**
     * Display the ticket submission form.
     */
    public function create(): View
    {
        return view('tickets.create', [
            'ticketTypes' => TicketType::toArray(),
        ]);
    }

    /**
     * Handle the ticket submission.
     */
    public function store(TicketSubmissionRequest $request): RedirectResponse
    {
        try {
            $ticketData = TicketData::fromRequest($request->validated());

            $ticket = $this->ticketService->createTicket($ticketData);

            return redirect()
                ->route('tickets.create')
                ->with('success', sprintf(
                    'Your ticket #%d has been submitted successfully! We will get back to you soon.',
                    $ticket->id
                ));
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}
