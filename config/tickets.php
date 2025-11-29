<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Ticket Types and Database Mapping
    |--------------------------------------------------------------------------
    |
    | This configuration maps each ticket type to its corresponding database
    | connection. Each department maintains its own isolated database for
    | scalability and data separation.
    |
    */
    'types' => [
        'Technical Issues' => 'technical_department',
        'Account & Billing' => 'account_department',
        'Product & Service' => 'product_department',
        'General Inquiry' => 'general_department',
        'Feedback & Suggestions' => 'feedback_department',
    ],

    /*
    |--------------------------------------------------------------------------
    | Ticket Statuses
    |--------------------------------------------------------------------------
    |
    | Available ticket statuses throughout the system.
    |
    */
    'statuses' => [
        'open' => 'Open',
        'noted' => 'Noted',
        'in_progress' => 'In Progress',
        'resolved' => 'Resolved',
        'closed' => 'Closed',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Status
    |--------------------------------------------------------------------------
    |
    | The default status for newly created tickets.
    |
    */
    'default_status' => 'open',

    /*
    |--------------------------------------------------------------------------
    | Admin Pagination
    |--------------------------------------------------------------------------
    |
    | Number of tickets to display per page in the admin panel.
    |
    */
    'pagination' => [
        'per_page' => 50,
    ],
];