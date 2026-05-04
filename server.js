const io = require("socket.io")(3000, {
    cors: { origin: "*" }
});

io.on("connection", socket => {
    console.log("User connected:", socket.id);

    socket.on("join-note", noteId => {
        socket.join("note_" + noteId);
    });

    socket.on("edit-note", data => {
        socket.to("note_" + data.noteId).emit("update-note", data);
    });
});