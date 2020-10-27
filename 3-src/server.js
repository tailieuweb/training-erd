var express = require("express"); //ket noi express
var app = express();
var bodyParser = require("body-parser");


const routeStudent = require('./routes/student');
const routeSchool = require('./routes/school');


app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
app.set("view engine", "ejs");
app.use(express.static("public"));
app.set("views", "./views");
app.listen(3000);

//ket noi mongodb
const mongoose = require("mongoose");
const { render } = require("ejs");

mongoose.connect("mongodb://localhost/school",{
  useNewUrlParser: true,
  useUnifiedTopology: true,
}).then(()=> {
    console.log("mongo connection successfully");
});


app.use("/student",routeStudent);
app.use("/school",routeSchool);
