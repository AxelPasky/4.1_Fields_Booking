<?php

namespace App\Livewire;

use App\Models\Field;
use Livewire\Component;
use Livewire\WithPagination;

class SearchFields extends Component
{
    use WithPagination;

    public string $search = '';

    public function render()
    {
        $fields = Field::where('name', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);

        return view('livewire.search-fields', [
            'fields' => $fields,
        ]);
    }
}
