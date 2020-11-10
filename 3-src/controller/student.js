const Student = require("../models/student");
const Classes = require("../models/classes");




const bodyParser = require("body-parser");

module.exports.add = function (req, res) {
  Classes.find(function (err, dataclasses) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("student/add", { classess: dataclasses });
    }
  });
};

module.exports.insertStudent = function (req, res) {
  var students = [];
  //for (let index = 0; index < 10; index++) {
  let student = {
    name: req.body.name,
    email: req.body.email,
    class: req.body.classes,
  };
  students.push(student);
  //}
  Student.collection.insertMany(students, (err) => {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      console.log("finish");
      res.redirect("/student");
    }
  });
};


module.exports.edit = async function (req, res) {
  const data = await Classes.find(function (err, data) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      return data;
    }
  });
  const item = await Student.findById(req.params.id, function (err, item) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      return item;
    }
  });
  res.render("student/edit", { student: item, classes: data });
};

module.exports.editStudent = function (req, res) {
  Student.updateOne(
    { _id: req.body.student_id },
    {
      name: req.body.name,
      email: req.body.email,
      class: req.body.classes,
    },
    function (err) {
      if (err) {
        res.json({ result: 0, error: err });
      } else {
        res.redirect("/student");
      }
    }
  );
};

module.exports.deleteStudent = function (req, res) {
  let id = req.params.id;
  Student.findByIdAndDelete(id, function (err, doc) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.redirect("/student");
    }
  });
};

module.exports.pagination = async function (req, res) {
  var page = parseInt(req.query.page) || 1;
  var perPage = 10;
  var drop = (page - 1) * perPage;
  var numberPage = (await Student.estimatedDocumentCount()) / perPage;
  var start = 0;
  var studentPerPage = await Student.find(null, null, { skip: drop }).limit(
    perPage
  ).populate('class')

  var baseUrl = "?page=";
  res.render("student/index", {
    students: studentPerPage,
    baseUrl: baseUrl,
    page: page,
    total: Math.ceil(numberPage),
    start: start,
  });
};

module.exports.search = async function (req, res) {
  var q = req.query.search;
  var students = await Student.find();
  var page = parseInt(req.query.page) || 1;
  var perPage = 10;
  var drop = (page - 1) * perPage;
  var end = page * perPage;
  const studentsSearch = await Student.find({ name: { $regex: q } }, null, {
    skip: drop,
  }).limit(perPage).populate('class');

  var start = 0;
  var numberPage =
    (await Student.find({ name: { $regex: q } }).countDocuments()) / perPage;
  var baseUrl = "?search=" + q + "&page=";
  res.render("student/index", {
    students: studentsSearch,
    keyword: q,
    page: page,
    baseUrl: baseUrl,
    total: Math.ceil(numberPage),
    start: start,
  });
};
