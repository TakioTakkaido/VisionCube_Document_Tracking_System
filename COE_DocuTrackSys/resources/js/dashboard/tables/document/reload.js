import { getNewDocuments } from "../../homepage";

export function reloadDocuments(){
    // Reload the dashboardtable

    // Send a notification

    // Reload document statistics
    documentStatistics(false);
    getNewDocuments();
}