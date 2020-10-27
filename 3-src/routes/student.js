var express = require("express");
var router = express.Router();
var controller = require("../controller/student");

router.get("/", controller.pagination);
router.get("/add", controller.add);
router.post("/add", controller.insertStudent);
router.get("/edit/:id", controller.edit);
router.post("/edit", controller.editStudent);
router.get("/delete/:id", controller.deleteStudent);
router.get("/search", controller.search);
module.exports = router;

