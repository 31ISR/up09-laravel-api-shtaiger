<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request):JsonResponse

    {
        $bookings = $request->user()->bookings()->orderBy('created_at')->get();
        return response()->json($bookings,200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            "room_name" => 'required|string|max:100',
            "starts_at" => 'required|date|after:now',
            "ends_at" => 'required|date|after:starts_at',
            "note" => 'nullable|string|max:500',
        ]);
        $bookeng = $request->user()->bookings()->create($data);

        return response()->json($bookeng,201);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,  Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message'=> 'это не ты'],403);

        }
   


        return response()->json($booking,200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Booking  $booking )
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message'=> 'Встреча была заплонирована не с вами'],403);
        }
        
        $data = $request->validate([
            "room_name" => 'required|string|max:100',
            "starts_at" => 'required|date|after:now',
            "ends_at" => 'required|date|after:starts_at',
            "note" => 'nullable|string|max:500',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy (Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id) {
            return response()->json(['message'=> 'Встреча была заплонирована не с вами'],403);

        }
        $booking->delete();

        return response()->json([
            'message'=> 'Встреча успешна удалена '
        ],200);
    }

}
