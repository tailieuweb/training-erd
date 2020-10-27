
const School = require("../models/school");

module.exports.index = function (req, res) {
  School.find(function (err, data) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("school/index", { schools: data });
    }
  })
}

module.exports.add = function (req, res) {
  res.render("school/add");
}

module.exports.insertClass = function (req, res) {
  var schools = [];
  let school = {
    name: req.body.name,
  }
  schools.push(school);

  School.collection.insertMany(schools, (err) => {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      console.log("finish");
      res.redirect("/school");
    }
  });
};

module.exports.edit = function (req, res) {
  School.findById(req.params.id, function (err, item) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("school/edit", { school: item });
    }
  });
}

module.exports.editClass = function (req, res) {
  School.updateOne(
    { _id: req.body.classSchoolID },
    {
      name: req.body.name,
    },
    function (err) {
      if (err) {
        res.json({ result: 0, error: err });
      } else {
        res.redirect("/school");
      }
    }
  );
}
module.exports.deleteClass = function (req, res) {
  School.deleteOne({ _id: req.params.id }, function (err) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.redirect("/school");
    }
  });
}