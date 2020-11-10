var express = require("express");
var router = express.Router();
var controller = require("../controller/home");

router.get("/", controller.index);

module.exports = router;