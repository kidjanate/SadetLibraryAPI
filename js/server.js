const express = require("express");
const app = express();
const db = require("./db/database");
const fs = require("fs");
const bodyParser = require("body-parser");

const port = process.env.PORT ?? 80;

app.use(bodyParser.json());

app.listen(port, () => {
  console.log(`Server is running on PORT: ${port} `);
});

fs.readdirSync(__dirname + "/api").forEach((file) => {
  require(`./api/${file}`)(app, db);
});
