<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(private ContactService $contact)
    {
    }

    public function store(ContactRequest $request): JsonResponse
    {
        $this->contact->store($request->validated());

        return response()->json(['message' => 'Votre message a bien été envoyé.'], 201);
    }
}