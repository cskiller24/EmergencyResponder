<?php

namespace App\Http\Livewire;

use App\Models\EmergencyType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class SubmissionCreate extends Component
{
    public int $contactsFormCount = 1;

    public int $linksFormCount = 1;

    public Collection $emergencyTypes;

    public function render(): View
    {
        $this->emergencyTypes = EmergencyType::all(['id', 'name']);

        $render = [
            'contactsForm' => $this->contactsFormCount,
            'linksFormCount' => $this->linksFormCount,
            'emergencyTypes' => $this->emergencyTypes,
        ];

        return view('livewire.submission-create', $render);
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
