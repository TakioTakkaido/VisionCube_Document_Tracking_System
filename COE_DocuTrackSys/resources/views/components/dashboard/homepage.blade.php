<div class="d-flex flex-column">
    <div class="flex-row">

        {{-- Upload Form --}}
        <x-dashboard.forms.upload />

        
        <div class="flex-column border rounded w-25 p-3">
            {{-- Card to show the recent updates --}}
            <div class="container recentUpdates">
                recentUpdates
            </div>

            {{-- Places to show the number of recent documets and the number of the other documents --}}
            @if($isAdmin)
            <div class="documentStatistics">
            </div>
            @endif
        </div>
    </div>

    
</div>