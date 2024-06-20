<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Resource_;

class ContactsController extends Controller
{
    public function getContacts() {
        $user = Auth::user();

        $paginated = Contact::where("userId", $user->id)->paginate(3);

        return response()->json([
            "paginated" => $paginated
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $contact = Contact::create([
            "name" => $validated["name"],
            "email" => $validated["email"] ?? NULL,
            "company" => $validated["company"] ?? NULL,
            "phone" => $validated["phone"] ?? NULL,
            "userId" => $user->id
        ]);

        $resource = new ContactResource($contact);

        return response()->json([
            "contact" => $resource
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreContactRequest $request, string $id)
    {
        $contact = Contact::findOrFail($id);

        $validated = $request->validated();

        $contact->name = $validated["name"];
        $contact->email = $validated["email"] ?? NULL;
        $contact->company = $validated["company"] ?? NULL;
        $contact->phone = $validated["phone"] ?? NULL;
        $contact->save();

        $resource = new ContactResource($contact);

        return response()->json([
            "message" => "Contact updated.",
            "contact" => $resource
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);

        $contact->delete();

        return response()->json([
            "mesasge" => "Contact deleted."
        ]);
    }
}
