const Admin = require("../models/admin");



module.exports.index = function (req, res) {

    Admin.find(function (err, data) {
      if (err) {
        res.json({ result: 0, error: err });
      } else {
        res.render("admin/index", { admins: data });
      }
    })
  }


module.exports.register = function (req, res) {
    res.render("admin/register");
  }

  module.exports.insertRegister = function (req, res) {
    var admins = [];
    let admin = {
        email:req.body.email,
        password: req.body.password,
        name: req.body.name,
    }
    admins.push(admin);

  Admin.collection.insertMany(admins, (err) => {
    if (err) {
      res.json({ result: 0, error: err });
    } else {
      console.log("finish");
      res.redirect("/admin");
    }
  });
  }
  