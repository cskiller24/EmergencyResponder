<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('invites.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="invite-email" class="form-label">Email</label>
                            <input type="email" name="email" id="invite-email" @class(['form-control', 'is-invalid' => $errors->has('email')])
                                value="{{ old('error') }}">

                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        </div>
                        <div class="mb-3">
                            <label for="invite-roles">Roles</label>
                            <select name="role" id="invite-roles" class="form-control">
                                @foreach ($roles as $role)
                                    @unless ($role->name === 'admin')
                                        <option value="{{ $role->name }}">{{ str_title($role->name) }}</option>
                                    @endunless
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{ $modalSaveButton }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
