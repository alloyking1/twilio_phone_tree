
<div>
    <h1 class="text-4xl font-bold text-gray-800 mb-4">Call Center</h1>

    <div class="my-4 flex justify-between">
        {{-- <div>
            @if ($agentAvailable)
            <button class="p-4 bg-red-500 text-white rounded-md" wire:click="toggleAgentAvailability">Make agent unavailable</button>
        @else
            <button class="p-4 bg-green-500 text-white rounded-md" wire:click="toggleAgentAvailability">Make agent available</button>
        @endif
        </div> --}}
    </div>

    
    <hr class="py-4">
    <div class="flex justify-between">
        {{-- @if ($agentAvailable) --}}
            <button class="p-4 bg-green-500 text-white rounded-md" wire:click="makeCall">Accept call</button>
            {{-- <button class="p-4 bg-red-500 text-white rounded-md" wire:click="agentEndCall">Reject call</button> --}}
        {{-- @endif --}}
    </div>
</div>
