
const mongoose = require("mongoose");

const Classes = new mongoose.Schema({
    name: String,
});
module.exports = mongoose.model("classes", Classes);


