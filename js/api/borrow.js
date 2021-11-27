/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/borrow", async (req, res) => {
    const studentId = req.body.sid;
    const bookId = req.body.bid;

    if (!studentId || !bookId) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }

    const year = new Date().getUTCFullYear() + 543;
    const currentTime = new Date().toLocaleDateString();
    const deadline = new Date(
      new Date().setDate(new Date().getDate() + 6)
    ).toLocaleDateString();
    const targetDB = "`log`.`$year`";
    const oldyear = year - 1;

    let rows = [
      await db.execute("SELECT * FROM `books`.`books` WHERE `id` = ?", [
        bookId,
      ]),
    ];
    if (rows <= 0) {
      return res.status(400).send({
        content: "ไม่มีหนังสือนี้ในระบบ",
        code: 4,
      });
    }
    const bookname = rows[0][0].name;
    const bookregisnum = rows[0][0].regisnum;
    const bookcategory = rows[0][0].category;

    rows = [
      await db.execute("SELECT * FROM students.students WHERE id = ?", [
        studentId,
      ]),
    ];
    if (rows <= 0) {
      return res.status(400).send({
        content: "ไม่มีรายชื่อนักเรียนนี้ในระบบ",
        code: 3,
      });
    }

    const studentname = rows[0][0]["name"];
    const studentclass = rows[0][0]["class"];
    const studentnumber = rows[0][0]["number"];

    if (await db.execute("SHOW TABLE LIKE ?", [targetDB])) {
      db.execute("CREATE TABLE `log`.? LIKE `log`.?", [year, oldyear]);
    }

    db.execute(
      "INSERT INTO ? (borrowtime, name, class, number, bookname, category, regisnum, deadline) VALUES (?,?,?,?,?,?,?,?)",
      [
        targetDB,
        currentTime,
        studentname,
        studentclass,
        studentnumber,
        bookname,
        bookcategory,
        bookregisnum,
        deadline,
      ]
    )
      .then(() => {
        return res.status(200).send({
          content: "ยืมหนังสือสำเร็จ",
          code: 0,
        });
      })
      .catch((err) => {
        return res.status(500).send({
          content: `ล้มเหลว! ${err}`,
          code: 2,
        });
      });
  });
};
