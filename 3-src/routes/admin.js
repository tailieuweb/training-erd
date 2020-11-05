var express = require("express");
var router = express.Router();
var controller = require("../controller/admin");


router.get("/", controller.index);
router.get("/register", controller.register);
router.post("/register", controller.insertRegister);
module.exports = router;
