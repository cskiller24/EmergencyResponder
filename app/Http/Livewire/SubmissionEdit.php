<?php

namespace App\Http\Livewire;

use App\Models\EmergencyType;
use App\Models\Submission;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class SubmissionEdit extends Component
{
    public int $contactsFormCount = 1;

    public int $linksFormCount = 1;

    public Submission $submission;

    public Collection $emergencyTypes;

    public function mount(
        Submission $submission
    ) {
        $this->submission = $submission;
        $this->contactsFormCount = $submission->contacts->count() ?? 1;
        $this->linksFormCount = $submission->relatedLinks->count() ?? 1;
        $this->emergencyTypes = EmergencyType::all(['id', 'name']);
    }

    public function render(): View
    {
        $data = [
            'emergencyTypes' => $this->emergencyTypes,
            'submission' => $this->submission,
            'contactsFormCount' => $this->contactsFormCount,
            'linksFormCount' => $this->linksFormCount,
        ];

        return view('livewire.submission-edit', $data);
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
