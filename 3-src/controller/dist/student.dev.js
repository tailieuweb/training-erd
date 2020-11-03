"use strict";

var Student = require("../models/student");

var Classes = require("../models/classes");

var bodyParser = require("body-parser");

module.exports.add = function (req, res) {
  Classes.find(function (err, dataclasses) {
    if (err) {
      res.json({
        result: 0,
        error: err
      });
    } else {
      res.render("student/add", {
        classess: dataclasses
      });
    }
  });
};

module.exports.insertStudent = function (req, res) {
  var students = []; //for (let index = 0; index < 10; index++) {

  var student = {
    name: req.body.name,
    email: req.body.email,
    "class": req.body.classes
  };
  students.push(student); //}

  Student.collection.insertMany(students, function (err) {
    if (err) {
      res.json({
        result: 0,
        error: err
      });
    } else {
      console.log("finish");
      res.redirect("/student");
    }
  });
};

module.exports.edit = function _callee(req, res) {
  var data, item;
  return regeneratorRuntime.async(function _callee$(_context) {
    while (1) {
      switch (_context.prev = _context.next) {
        case 0:
          _context.next = 2;
          return regeneratorRuntime.awrap(Classes.find(function (err, data) {
            if (err) {
              res.json({
                result: 0,
                error: err
              });
            } else {
              return data;
            }
          }));

        case 2:
          data = _context.sent;
          _context.next = 5;
          return regeneratorRuntime.awrap(Student.findById(req.params.id, function (err, item) {
            if (err) {
              res.json({
                result: 0,
                error: err
              });
            } else {
              return item;
            }
          }));

        case 5:
          item = _context.sent;
          res.render("student/edit", {
            student: item,
            classes: data
          });

        case 7:
        case "end":
          return _context.stop();
      }
    }
  });
};

module.exports.editStudent = function (req, res) {
  Student.updateOne({
    _id: req.body.student_id
  }, {
    name: req.body.name,
    email: req.body.email,
    "class": req.body.classes
  }, function (err) {
    if (err) {
      res.json({
        result: 0,
        error: err
      });
    } else {
      res.redirect("/student");
    }
  });
};

module.exports.deleteStudent = function (req, res) {
  var id = req.params.id;
  Student.findByIdAndDelete(id, function (err, doc) {
    if (err) {
      res.json({
        result: 0,
        error: err
      });
    } else {
      res.redirect("/student");
    }
  });
};

module.exports.pagination = function _callee2(req, res) {
  var page, perPage, drop, numberPage, start, studentPerPage, baseUrl;
  return regeneratorRuntime.async(function _callee2$(_context2) {
    while (1) {
      switch (_context2.prev = _context2.next) {
        case 0:
          page = parseInt(req.query.page) || 1;
          perPage = 10;
          drop = (page - 1) * perPage;
          _context2.next = 5;
          return regeneratorRuntime.awrap(Student.estimatedDocumentCount());

        case 5:
          _context2.t0 = _context2.sent;
          _context2.t1 = perPage;
          numberPage = _context2.t0 / _context2.t1;
          start = 0;
          _context2.next = 11;
          return regeneratorRuntime.awrap(Student.find(null, null, {
            skip: drop
          }).limit(perPage).populate('class'));

        case 11:
          studentPerPage = _context2.sent;
          baseUrl = "?page=";
          res.render("student/index", {
            students: studentPerPage,
            baseUrl: baseUrl,
            page: page,
            total: Math.ceil(numberPage),
            start: start
          });

        case 14:
        case "end":
          return _context2.stop();
      }
    }
  });
};

module.exports.search = function _callee3(req, res) {
  var q, students, page, perPage, drop, end, studentsSearch, start, numberPage, baseUrl;
  return regeneratorRuntime.async(function _callee3$(_context3) {
    while (1) {
      switch (_context3.prev = _context3.next) {
        case 0:
          q = req.query.search;
          _context3.next = 3;
          return regeneratorRuntime.awrap(Student.find());

        case 3:
          students = _context3.sent;
          page = parseInt(req.query.page) || 1; // Tìm kiếm
          // searchStudent = students.filter(function (student) {
          //   numberSP++;
          //   return student.name.toLowerCase().indexOf(q.toLowerCase()) !== -1;
          // });

          perPage = 10;
          drop = (page - 1) * perPage;
          end = page * perPage;
          _context3.next = 10;
          return regeneratorRuntime.awrap(Student.find({
            name: {
              $regex: q
            }
          }, null, {
            skip: drop
          }).limit(perPage).populate('class'));

        case 10:
          studentsSearch = _context3.sent;
          start = 0;
          _context3.next = 14;
          return regeneratorRuntime.awrap(Student.find({
            name: {
              $regex: q
            }
          }).countDocuments());

        case 14:
          _context3.t0 = _context3.sent;
          _context3.t1 = perPage;
          numberPage = _context3.t0 / _context3.t1;
          // var numberPage = await Student.estimatedDocumentCount() / perPage;
          // var studentPerPage = searchStudent.find(null, null, { skip: drop }).limit(perPage);
          baseUrl = "?search=" + q + "&page=";
          res.render("student/index", {
            students: studentsSearch,
            keyword: q,
            page: page,
            baseUrl: baseUrl,
            total: Math.ceil(numberPage),
            start: start
          });

        case 19:
        case "end":
          return _context3.stop();
      }
    }
  });
};