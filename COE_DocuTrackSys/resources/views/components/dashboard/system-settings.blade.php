<div class="systemSettings">
    {{-- System Settings
        Accounts Settings
            General
                Add Account
                Edit Account
                Set Account Status (deactivate/reactivate)
            Account Roles
                Secretary
                    Edit access
                Asst. Secretary
                Clerk
        Document Settings
            Sender and Recipients
                Uni. President
                Offices
                Office Heads
                Faculty
                Dept. Head
                Secretaries
                Others
                itanong to kay maam ivy...
            Document Category
                Incoming
                Outgoing
                Archived
                more...
            Document Type
                Memoranda
                Letter
                Request
                more...
            Document Status
                Accepted
                etc...
                more...
            File Extensions
                all
                pdf     (default)
                docx    (default)
                doc     (default)
                zip
                rar
                other...
    --}}
    {{-- Title --}}
    <h3 id="systemSettingsTitle">System Settings</h3>
    {{-- Settings, Place all components here --}}
    {{-- Account Settings --}}
    <div class="account-settings">
        <x-dashboard.system-settings.account />
    </div>

    {{-- Show the checklist of functions for each --}}
    {{-- List of functions:
        Upload document
        Edit document
        Move to Incoming/Outgoing
        Move to Archived
        Download document file
        Print document file
    --}}
    {{-- By default:
        Secretary:
        Upload document /
        Edit document /
        Move to Incoming/Outgoing /
        Move to Archived /
        Download document file /
        Print document file /

        Clerk: 
        Upload document
        Edit document /
        Move to Incoming/Outgoing /
        Move to Archived /
        Download document file /
        Print document file /
    --}}

    {{-- Document Settings --}}
    <div class="document-settings">
        <x-dashboard.system-settings.document />
    </div>
</div>