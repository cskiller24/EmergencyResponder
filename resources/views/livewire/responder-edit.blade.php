<div class="card border border-dark">
    <div class="card-body">
        <h1 class="text-center">Edit {{ $responder->name }}</h1>
        <form action="{{ route('moderator.responders.update', $responder->id) }}" method="post">
            @method('PUT')
            @csrf
            <div class="card p-2 bg-gray-200 mb-3">
                <h3>Information</h3>
                <div class="row">
                    <div class="col-md-12 mb-2">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('name')]) name="name" id="name" aria-describedby="name" placeholder="Red Cross Sta Ana." value="{{ $responder->name }}">
                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" rows="5" @class(['form-control', 'is-invalid' => $errors->has('description')]) >{{ $responder->description }}</textarea>
                        <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="emergency_type_id" class="form-label">Emergency Type</label>
                        <select @class(['form-select', 'is-invalid' => $errors->has('emergency_type_id')]) name="emergency_type_id" id="emergency_type_id">
                            @foreach ($emergencyTypes as $emergencyType)
                                <option value="{{ $emergencyType->id }}" @selected($responder->emergencyType->id === $emergencyType->id)>{{ $emergencyType->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('emergency_type_id') }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="status" class="form-label">Status</label>
                        <select @class(['form-select', 'is-invalid' => $errors->has('status')]) name="status" id="status">
                            <option selected disabled>Select Status</option>
                            <option value="{{ App\Enums\ResponderStatusEnum::ACTIVE }}" @selected($responder->status->value === App\Enums\ResponderStatusEnum::ACTIVE->value) >{{ App\Enums\ResponderStatusEnum::ACTIVE->titleCase() }}</option>
                            <option value="{{ App\Enums\ResponderStatusEnum::INACTIVE }}"  @selected($responder->status->value === App\Enums\ResponderStatusEnum::INACTIVE->value)>{{ App\Enums\ResponderStatusEnum::INACTIVE->titleCase() }}</option>
                            <option value="{{ App\Enums\ResponderStatusEnum::ARCHIVED }}"  @selected($responder->status->value === App\Enums\ResponderStatusEnum::ARCHIVED->value)>{{ App\Enums\ResponderStatusEnum::ARCHIVED->titleCase() }}</option>
                        </select>
                        <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                    </div>
                </div>
            </div>

            <div class="card p-2 bg-gray-200 mb-3">
                <h3>Location</h3>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="latidude" class="form-label">Latitude</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('latitude')]) name="latitude" id="latitude" aria-describedby="latitude" placeholder="54.000" value="{{ $responder->location->latitude }}">
                        <div class="invalid-feedback">{{ $errors->first('latitude') }}</div>
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('longitude')]) name="longitude" id="longitude" aria-describedby="longitude" placeholder="105.000" value="{{ $responder->location->longitude }}">
                        <div class="invalid-feedback">{{ $errors->first('longitude') }}</div>
                    </div>
                    <div class="col-12 mb-2">
                        <label for="city" class="form-label">Line</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('line')]) name="line" id="line" aria-describedby="line" placeholder="777 Super St." value="{{ $responder->location->line }}">
                        <div class="invalid-feedback">{{ $errors->first('line') }}</div>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="city" class="form-label">City</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('city')]) name="city" id="city" aria-describedby="city" placeholder="Manila" value="{{ $responder->location->city }}">
                        <div class="invalid-feedback">{{ $errors->first('city') }}</div>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="region" class="form-label">Region</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('region')]) name="region" id="region" aria-describedby="region" placeholder="NCR" value="{{ $responder->location->region }}">
                        <div class="invalid-feedback">{{ $errors->first('region') }}</div>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="country" class="form-label">Region</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('country')]) name="country" id="country" aria-describedby="country" placeholder="Philippines" value="{{ $responder->location->country }}">
                        <div class="invalid-feedback">{{ $errors->first('country') }}</div>
                    </div>
                    <div class="col-lg-3 mb-2">
                        <label for="zip" class="form-label">Zip</label>
                        <input type="text" @class(['form-control', 'is-invalid' => $errors->has('zip')]) name="zip" id="zip" aria-describedby="zip" placeholder="100X" value="{{ $responder->location->zip }}">
                        <div class="invalid-feedback">{{ $errors->first('zip') }}</div>
                    </div>
                </div>
            </div>


            <div class="d-md-flex justify-content-between align-items-center mb-2">
                <h3>Contacts</h3>
                <button type="button" wire:click="addContact" class="btn btn-primary py-1 px-2">Add Another Contact</button>
            </div>
            @if($errors->has('contacts.*.type') || $errors->has('contacts.*.detail'))
            <div class="card bg-red-lt my-2">
                <div class="card-body">
                    <ul>
                        @if ($errors->has('contacts.*.type'))
                            <li>{{ $errors->first('contacts.*.type') }}</li>
                        @endif

                        @if ($errors->has('contacts.*.detail'))
                            <li>{{ $errors->first('contacts.*.detail') }}</li>
                        @endif
                    </ul>
                </div>
            </div>
            @endif

            @for ($i = 0; $i < $contactsFormCount; $i++)
            <div class="card bg-gray-200 p-2 mb-3">
                <h4>Contact {{ $i + 1 }}</h4>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <label for="contact-type-{{ $i + 1 }}" class="form-label">Contact Type</label>
                        <input type="text" class="form-control" name="contacts[{{ $i }}][type]" id="contact-type-{{ $i + 1 }}" aria-describedby="contact-type" placeholder="Email/Contact Number" value="{{ $responder->contacts[$i]?->type ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-2">
                        <label for="contact-detail-{{ $i + 1 }}" class="form-label">Contact Detail</label>
                        <input type="text" class="form-control" name="contacts[{{ $i }}][detail]" id="contact-detail-{{ $i + 1 }}" aria-describedby="contact-detail" placeholder="redcross@emergency.com" value="{{ $responder->contacts[$i]?->detail ?? '' }}">
                    </div>
                </div>
            </div>
            @endfor

            <div class="d-md-flex justify-content-between align-items-center mb-2">
                <h3>Links</h3>
                <button type="button" wire:click="addLink" class="btn btn-primary py-1 px-2">Add Another Link</button>
            </div>
            @if($errors->has('links.*') || $errors->has('links.*'))
            <div class="card bg-red-lt my-2">
                <div class="card-body">
                    <ul>
                        <li>{{ $errors->first('links.*') }}</li>
                    </ul>
                </div>
            </div>
            @endif
            @for ($i = 0; $i < $linksFormCount; $i++)
            <div class="card bg-gray-200 p-2 mb-3">
                <h4>Link {{ $i + 1 }}</h4>
                <div class=" mb-2">
                    <label for="link-{{ $i + 1 }}" class="form-label">URL</label>
                    <input type="text" class="form-control" name="links[][link]" id="link-{{ $i + 1 }}" aria-describedby="link" placeholder="https://example.com/" value="{{ $responder->relatedLinks[$i]?->link ?? '' }}">
                </div>
            </div>
            @endfor

            <input type="submit" value="Submit" class="btn btn-success w-100">
        </form>
    </div>
</div>
