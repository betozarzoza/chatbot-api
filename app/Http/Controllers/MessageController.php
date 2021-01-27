<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Receive the message and execute the command.
     *
     * @param  request $request
     * @return \Illuminate\Http\Response
     */
    public function receive(Request $request)
    {
        if (isset($request->command)) {
            $user = $request->user();
            switch ($request->command) {
                case '/balance':
                    return User::find($user->id);
                    break;
                case '/add':
                    $user = User::find($user->id);
                    $user->balance += floatval($request->amount);
                    $user->save();
                    return User::find($user->id);
                    break;
                case '/withdraw':
                    $user = User::find($user->id);
                    $user->balance -= floatval($request->amount);
                    if ($user->balance < 0) {
                        return response()->json([
                            'message' => "You don't have enought money."
                        ], 400);
                    } else {
                        $user->save();
                        return User::find($user->id);
                    }
                    break;
                case '/exchange':
                    // I was going to put the AMDOREN key on the .env and set it from there but for testing purposes I keep it here, anyways I added it on the .env and .env.example but it's not loaded from there
                    $response = Http::get('https://www.amdoren.com/api/currency.php?api_key=mES32NsLt4HxuthvUNbjqmXKQsHKu4&from='.$request->from_currency.'&to='.$request->to_currency.'&amount='.$request->amount);
                    return $response->body();
                default:
                    return response()->json([
                        'message' => "Invalid command."
                    ], 400);
            }
        }
    }
}
