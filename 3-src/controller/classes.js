

const Classes = require("../models/classes");

module.exports.index = function (req, res) {
  Classes.find(function (err, data) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("classes/index", { classess: data });
    }
  })
}

module.exports.add = function (req, res) {
  res.render("classes/add");
}

module.exports.insertClass = function (req, res) {
  var classess = [];
  let classes = {
    name: req.body.name,
  }
  classess.push(classes);

  Classes.collection.insertMany(classess, (err) => {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      console.log("finish");
      res.redirect("/classes");
    }
  });
};

module.exports.edit = function (req, res) {
  Classes.findById(req.params.id, function (err, item) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.render("classes/edit", { classes: item });
    }
  });
}

module.exports.editClass = function (req, res) {
  Classes.updateOne(
    { _id: req.body.classClassesID },
    {
      name: req.body.name,
    },
    function (err) {
      if (err) {
        res.json({ result: 0, error: err });
      } else {
        res.redirect("/classes");
      }
    }
  );
}
module.exports.deleteClass = function (req, res) {
  Classes.deleteOne({ _id: req.params.id }, function (err) {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      res.redirect("/classes");
    }
  });
}