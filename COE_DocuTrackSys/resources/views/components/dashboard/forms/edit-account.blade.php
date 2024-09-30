<div class="modal fade" id="editAccount" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="editAccount">
        <div class="modal-content">
        <div class="modal-header custom-modal-header">
            <h5 class="modal-title">Edit Account</h5>
        </div>
        <div class="modal-body custom-modal-body">
            <!-- Modal content goes here -->
            <p>Edit account details here.</p>
        </div>
        <div class="modal-footer custom-modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <form method="POST" action="{{route('account.logout')}}">
                @csrf
                @method('POST')
                <button type="submit" class="btn btn-primary">Log Out</button>
            </form>
        </div>
        </div>
    </div>
</div>