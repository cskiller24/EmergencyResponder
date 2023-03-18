<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ $route }}">
                    @csrf
                    @if ($putMethod ?? null)
                        @method('PUT')
                    @endif
                    <div class="modal-body">
                        <label for="permission-name" class="form-label">Name</label>
                        <input
                            type="text"
                            name="name"
                            id="permission-name"
                            @class(['form-control', 'is-invalid' => $errors->has('name')])
                            @if ($putMethod ?? null)
                                value="{{ $updateValue }}"
                            @else
                                value="{{ old('error') }}"

                            @endif
                        >

                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
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
