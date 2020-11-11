<div>
    {{-- Be like water. --}}
    <form wire:submit.prevent="save">
    <p wire:model="session.sessionValue">{{ $sessionValue ?? '' }}</p>
        Session Private: <input type="checkbox" wire:model="session.private">
        <button type="submit">Save</button> {{ $result }}
    </form>
</div>
