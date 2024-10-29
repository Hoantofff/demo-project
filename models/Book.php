<?php

class Book extends BaseModel{
    protected $table = 'books';

    public function getAll()
    {
        $sql = "
            SELECT 
                b.id                b_id,
                b.title             b_title,
                b.author            b_author,
                b.img_cover         b_img_cover,
                b.published_year    b_published_year,
                b.created_at        b_created_at,
                b.updated_at        b_updated_at,
                c.id                c_id,
                c.name              c_name
            FROM books b
            JOIN categories c ON c.id = b.category_id
            ORDER BY b.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getByID($id)
    {
        $sql = "
            SELECT 
                b.id                b_id,
                b.title             b_title,
                b.author            b_author,
                b.img_cover         b_img_cover,
                b.published_year    b_published_year,
                b.created_at        b_created_at,
                b.updated_at        b_updated_at,
                c.id                c_id,
                c.name              c_name
            FROM books b
            JOIN categories c ON c.id = b.category_id
            WHERE b.id = :id;
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }
}
