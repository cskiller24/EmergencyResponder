<?php

namespace App\Http\Controllers\Web\Admin;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Controllers\Controller;
use App\Http\Requests\InviteRequest;
use App\Mail\SendInvite;
use App\Models\Invite;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Uid\Ulid;

class InviteController extends Controller
{
    public function index(): View
    {
        $invites = Invite::all();
        $roles = Role::all();

        return view('admin.invites', compact('invites', 'roles'));
    }

    public function store(InviteRequest $request): RedirectResponse
    {
        $code = Ulid::generate();

        DB::beginTransaction();
        try {
            $invite = Invite::query()->create(array_merge(
                $request->validated(),
                compact('code')
            ));

            Mail::to($request->email)->send(new SendInvite($invite));

            DB::commit();

            \toastr()->success('Invite sent successfully');
        } catch (QueryException $e) {
            DB::rollBack();

            \toastr()->warning('Something went wrong please try again');
            throw $e;
        } catch(TransportException $f) {
            DB::rollBack();

            \toastr()->warning('Something when wrong in sending the email');
            throw $f;
        }

        return redirect()->route('admin.invites.index');
    }

    public function accept(Invite $invite): View
    {
        return view('auth.invite-accept', ['invite' => $invite]);
    }

    public function process(Request $request, CreateNewUser $creator, Invite $invite): RedirectResponse
    {
        $data = array_merge(
            $request->only(['name', 'password', 'password_confirmation']),
            ['email' => $invite->email]
        );

        $creator->create($data)->assignRole($invite->role);

        $invite->delete();

        \toastr()->success('User invited successfully');

        return redirect()->route('login');
    }

    public function resend(Invite $invite): RedirectResponse
    {
        Mail::to($invite->email)->send(new SendInvite($invite));

        \toastr()->success('Email resent successfully');

        return redirect()->back();
    }

    public function destroy(Invite $invite): RedirectResponse
    {
        $invite->delete();

        \toastr()->success('Invitation deleted successfully');

        return redirect()->back();
    }
}
