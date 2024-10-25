<div class="d-flex flex-column">
    <div class="flex-row">

        {{-- Upload Form --}}
        <x-dashboard.forms.upload />

        
        {{-- <div class="flex-column border rounded w-25 p-3">--}}
        
        <div class="container recentUpdates">
            recentUpdates
        </div>
        @if($isAdmin)
        <div class="documentStatistics">
        </div>
        @endif
        
    </div>
</div>