/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/getstudent", async (req, res) => {
    const studentid = req.body.id;

    if (!studentid) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }

    let rows = [
      await db.execute("SELECT * FROM students.students WHERE id = ?", [
        studentid,
      ]),
    ];
    if (rows.length <= 0) {
      return res.status(500).send({
        content: "มีข้อผิดพลาด",
        code: 1,
      });
    }

    return res.send({
      content: "สำเร็จ",
      name: rows[0][0].name,
      class: rows[0][0].class,
      code: 0,
    });
  });
};
