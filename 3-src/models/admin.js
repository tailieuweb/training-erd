const mongoose = require("mongoose");

const admin = new mongoose.Schema({
    email: String,
    password: String,
    name: String,

});
module.exports = mongoose.model("Admin", admin);