let db;

const request = indexedDB.open("notesDB", 1);

request.onupgradeneeded = e => {
    db = e.target.result;

    if (!db.objectStoreNames.contains("notes")) {
        db.createObjectStore("notes", { keyPath: "id" });
    }
};

request.onsuccess = e => {
    db = e.target.result;
};

function saveNotesOffline(notes) {
    const tx = db.transaction("notes", "readwrite");
    const store = tx.objectStore("notes");

    notes.forEach(note => {
        store.put(note);
    });
}

function getOfflineNotes(callback) {
    const tx = db.transaction("notes", "readonly");
    const store = tx.objectStore("notes");

    const req = store.getAll();

    req.onsuccess = () => {
        callback(req.result);
    };
}