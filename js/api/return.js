/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/return", async (req, res) => {
    const studentid = req.body.studentid;
    const bookid = req.body.bookid;

    if (!studentid || !bookid) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }

    const year = new Date().getFullYear() + 543;
    const currentTime = new Date().toLocaleDateString();
    const targetDB = `\`log\`.\`${year}\``;

    let rows = [
      await db.query(`SELECT * FROM \`books\`.\`books\` WHERE \`id\` = ?`, [
        bookid,
      ]),
    ];

    if (rows.length <= 0) {
      return res.status(404).send({
        content: "ไม่พบข้อมูลของหนังสือ",
        code: 4,
      });
    }

    const bookname = rows[0][0].name;

    rows = [
      await db.query("SELECT * FROM students.students WHERE id = ?", [
        studentid,
      ]),
    ];
    if (rows.length <= 0) {
      return res.status(404).send({
        content: "ไม่พบข้อมูลของนักเรียน",
        code: 3,
      });
    }

    const studentname = rows[0][0].name;

    db.execute(
      `UPDATE ${targetDB} SET returntime = ? WHERE bookname = ? AND name = ?`,
      [currentTime, bookname, studentname]
    )
      .then(() => {
        return res.status(200).send({
          content: "คืนหนังสือสำเร็จ",
          code: 0,
        });
      })
      .catch(() => {
        return res.status(500).send({
          content: "คืนหนังสือล้มเหลว",
          code: 2,
        });
      });
  });
};
