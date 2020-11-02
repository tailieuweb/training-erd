const Student = require("../models/student");
const School = require("../models/school");
const bodyParser = require("body-parser");

module.exports.add = function (req, res) {
  School.find(function (err, dataSchool) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("student/add", { schools: dataSchool });
    }
  });
};

module.exports.insertStudent = function (req, res) {
  var students = [];
  //for (let index = 0; index < 20000; index++) {
  let student = {
    code: req.body.code + index,
    name: req.body.name + index,
    email: req.body.email,
    school: req.body.school,
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
  const data = await School.find(function (err, data) {
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

  res.render("student/edit", { student: item, school: data });
};

module.exports.editStudent = function (req, res) {
  Student.updateOne(
    { _id: req.body.student_id },
    {
      code: req.body.code,
      name: req.body.name,
      email: req.body.email,
      school: req.body.school,
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
  var students = await Student.find();
  var numberPage = (await Student.estimatedDocumentCount()) / perPage;
  var start = 0;
  var studentPerPage = await Student.find(null, null, { skip: drop }).limit(
    perPage
  );
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
  // Tìm kiếm
  // searchStudent = students.filter(function (student) {
  //   numberSP++;
  //   return student.name.toLowerCase().indexOf(q.toLowerCase()) !== -1;
  // });
  var perPage = 2;
  var drop = (page - 1) * perPage;
  var end = page * perPage;
  const studentsSearch = await Student.find({ name: { $regex: q } }, null, {
    skip: drop,
  }).limit(perPage);

  var start = 0;
  var numberPage =
    (await Student.find({ name: { $regex: q } }).countDocuments()) / perPage;
  // var numberPage = await Student.estimatedDocumentCount() / perPage;
  // var studentPerPage = searchStudent.find(null, null, { skip: drop }).limit(perPage);
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
