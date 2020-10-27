const mongoose = require("mongoose");

const student = new mongoose.Schema({
    code: String,
    name: String,
    email: String,
    school: String
});
module.exports = mongoose.model("Student", student);
