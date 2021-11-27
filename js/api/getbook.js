/**
 * @param {import('express').Application} app
 * @param {import('mysql2').Pool} db
 */
module.exports = (app, db) => {
  app.post("/api/getbook", async (req, res) => {
    const id = req.body.id;

    if (!id) {
      return res.status(400).send({
        content: "คำขอล้มเหลว",
        code: 1,
      });
    }

    let rows = await db.execute("SELECT * FROM books.books WHERE id = ?", [id]);
    if (rows.length <= 0) {
      return res.status(404).send({
        content: "ไม่พบข้อมูลของหนังสือ",
        code: 2,
      });
    }
    let book = new Book();
    book.id = rows[0][0].id;
    book.name = rows[0][0].name;
    book.getbooktime = rows[0][0].booktime;
    book.regisNumber = rows[0][0].regisnum;
    book.price = rows[0][0].price;

    return res.status(200).send({
      content: "สำเร็จ",
      code: 0,
      book: book,
    });
  });
};

class Book {
  id;
  name;
  getbooktime;
  regisNumber;
  price;
}
