const mongoose = require("mongoose");

const Student = new mongoose.Schema({
    name: String,
    email: String,
    class: { type: mongoose.Schema.Types.ObjectId, ref: 'classes' }
});
module.exports = mongoose.model("student", Student);
