/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/addstudent", (req, res) => {
    const studentId = req.body.sid;
    const studentName = req.body.sname;
    const studentClass = req.body.sclass;
    const studentNumber = req.body.snumber;

    if (!studentId || !studentName || !studentClass || !studentNumber) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }
    db.execute("SELECT * FROM students.students WHERE id = ?", [
      studentId,
    ]).then(([rows]) => {
      if (rows.length > 0) {
        return res.status(400).send({
          content: "มีรายชื่อนักเรียนนี้อยู่แล้วโปรดใช้รหัสนักเรียนอื่น",
          code: 2,
        });
      }
    });

    db.execute(
      "INSERT INTO students.students (id, name, class, number) VALUES (?, ?, ?, ?)",
      [studentId, studentName, studentClass, studentNumber]
    ).catch((err) => {
      console.log(err);
      return res.status(500).send({
        content: "อัพเดทรายชื่อนักเรียนล้มเหลว",
        code: 3,
      });
    });
    return res.status(200).send({
      content: "อัพเดทรายชื่อนักเรียนสำเร็จ",
      code: 0,
    });
  });
};
