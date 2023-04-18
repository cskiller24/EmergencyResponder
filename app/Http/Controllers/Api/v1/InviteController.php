<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;
use App\Http\Resources\InviteResource;
use App\Mail\SendInvite;
use App\Models\Invite;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Uid\Ulid;

class InviteController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Invite::class);

        $invites = Invite::all();

        return InviteResource::collection($invites);
    }

    public function store(InviteRequest $request)
    {
        $this->authorize('store', Invite::class);

        $code = Ulid::generate();

        DB::beginTransaction();
        try {
            $invite = Invite::query()->create(array_merge(
                $request->validated(),
                compact('code')
            ));

            Mail::to($request->email)->send(new SendInvite($invite));

            DB::commit();

            return response()->json([
                'message' => 'Invite user successfully, please check the email'
            ], ResponseCodes::HTTP_CREATED);
        } catch (QueryException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong at creating invite'
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        } catch(TransportException $f) {
            DB::rollBack();

            return response()->json([
                'message' => 'Something went wrong sending mail'
            ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function process(Request $request, CreateNewUser $creator, Invite $invite)
    {
        $data = array_merge(
            $request->only(['name', 'password', 'password_confirmation']),
            ['email' => $invite->email]
        );

        DB::transaction(function () use ($data, $invite, $creator) {
            $creator->create($data)->assignRole($invite->role);

            $invite->delete();
        });

        return response()->json(['message' => 'User created succesfully']);
    }

    public function resend(Invite $invite)
    {
        Mail::to($invite->email)->send(new SendInvite($invite));

        \toastr()->success('Email resent successfully');

        return response()->json(['message' => 'Mail resend succesfully']);
    }

    public function destroy(Invite $invite)
    {
        $this->authorize('destroy', $invite);

        $invite->delete();

        return response()->json(['message' => 'Invite deleted successfully']);
    }
}
