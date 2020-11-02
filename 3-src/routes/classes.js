var express = require("express");
var router = express.Router();
var controller = require("../controller/classes");

router.get("/", controller.index);
router.get("/add", controller.add);
router.post("/add", controller.insertClass);
router.get("/edit/:id", controller.edit);
router.post("/edit", controller.editClass);
router.get("/delete/:id", controller.deleteClass);

module.exports = router;

