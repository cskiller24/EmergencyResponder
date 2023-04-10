<?php

namespace App\Http\Livewire;

use App\Models\EmergencyType;
use App\Models\Responder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class ResponderEdit extends Component
{
    public int $contactsFormCount = 1;
    public int $linksFormCount = 1;
    public Responder $responder;
    public Collection $emergencyTypes;

    public function mount(
        Responder $responder
    ) {
        $this->responder = $responder;
        $this->contactsFormCount = $responder->contacts->count() ?? 1;
        $this->linksFormCount = $responder->relatedLinks->count() ?? 1;
        $this->emergencyTypes = EmergencyType::all(['id', 'name']);
    }

    public function render(): View
    {
        $data = [
            'emergencyTypes' => $this->emergencyTypes,
            'responder' => $this->responder,
            'contactsFormCount' => $this->contactsFormCount,
            'linksFormCount' => $this->linksFormCount
        ];

        return view('livewire.responder-edit', $data);
    }

    public function addContact(): void
    {
        $this->contactsFormCount++;
    }

    public function addLink(): void
    {
        $this->linksFormCount++;
    }
}
