import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
import { reloadDocuments } from './dashboard/tables/document';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Backend listeners
window.Echo.channel(`documentUploaded`)
.listen(`.document.uploaded`, (event) => {
    reloadDocuments();
    $('#dashboardTable').DataTable().ajax.reload();
});

// Document updated
window.Echo.channel(`documentUpdated`)
.listen(`.document.updated`, (event) => {
    reloadDocuments();
    $('#dashboardTable').DataTable().ajax.reload();
});

// Report generated
window.Echo.channel(`reportGenerated`)
.listen(`.report.generated`, (event) => {
    reloadDocuments();
});

// System after maintenance
window.Echo.channel(`maintenanceReverted`)
.listen(`.maintenance.reverted`, (event) => {
    reloadDocuments();
});

// Announce that there will be a maintenance
