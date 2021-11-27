/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/joinandout", async (req, res) => {
    const studentid = req.body.studentid;

    if (!studentid) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }

    const day = new Date().getDay();
    const month = new Date().getMonth();
    const year = new Date().getFullYear() + 543;
    const hours = new Date().toLocaleTimeString();

    const date = `${day}/${month}/${year} ${hours}`;
    const targetdb = `loginlog.\`${month}/${year}\``;

    let rows = [await db.execute("SHOW TABLES LIKE ?", [targetdb])];
    if (rows.length <= 0) {
      const oldMonth = month - 1;
      const targetoldtable = `loginlog.${oldMonth}/${year}`;
      db.query(`CREATE TABLE ${targetdb} LIKE ${targetoldtable}`);
    }

    rows = [
      await db.execute("SELECT * FROM students.students WHERE id = ?", [
        studentid,
      ]),
    ];
    if (rows.length <= 0) {
      return res.status(404).send({
        content: "ไม่พบนักเรียน",
        code: 2,
      });
    }

    const studentname = rows[0][0].name;
    const studentclass = rows[0][0].class;

    rows = [
      await db.execute(
        `SELECT * FROM ${targetdb} WHERE id = ? AND outtime = ''`,
        [studentid]
      ),
    ];

    if (rows.length <= 0) {
      db.query(
        `INSERT INTO ${targetdb} (id, name, class, jointime) VALUES (?,?,?,?)`,
        [studentid, studentname, studentclass, date]
      );
      return res.send({
        content: `${studentname} เข้าห้องสมุดแล้ว`,
        code: 0,
      });
    } else {
      db.query(
        `UPDATE ${targetdb} SET outtime = ? WHERE id = ? AND outtime = ''`,
        [date, studentid]
      );
      return res.send({
        content: `${studentname} ออกห้องสมุดแล้ว`,
        code: 3,
      });
    }
  });
};
