const mongoose = require("mongoose");

const school = new mongoose.Schema({
    name: String,
});
module.exports = mongoose.model("School",school);