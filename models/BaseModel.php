<?php

class BaseModel
{

    protected $table;
    protected $pdo;

    public function __construct()
    {
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8', DB_HOST, DB_PORT, DB_NAME);
        try {
            $this->pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, DB_OPTIONS);
        } catch (PDOException $e) {
            die("Kết nối cơ sở dữ liệu không thành công: {$e->getMessage()}");
        }
    }

    public function __destruct()
    {
        $this->pdo = null;
    }

    /**
     * Hàm lấy Danh Sách
     *
     * @param string $columns Mặc định lấy các cột, truyền cột phân cách nhau bằng giấy phẩy
     * @param string $conditions Mệnh đề điều kiện đặt ở đây
     * @param array $params giá trị các tham số ảo của conditions
     * @return array 
     *
     * Khi dùng $obj->select('id,name', 'id>:id AND price>:price', ['id'=>3,'price'=>677999])

     */

    public function select($columns = '*', $conditions = null, $params = [])
    {

        $sql = "SELECT $columns FROM {$this->table}";

        if ($conditions) {
            $sql .= " WHERE $conditions";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    public function count($conditions = null, $params = [])
    {

        $sql = "SELECT COUNT(*) FROM {$this->table}";

        if ($conditions) {
            $sql .= " WHERE $conditions";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /** 
     * hàm lấy danh sách có phân trang
     * 
     * @param int $page Trang hiện tại
     * @param int $perPage Số bản ghi trên 1 trang
     * @param string $columns Mặc định lấy tất cả các cột, truyền cột phân cách nhau bằng dấu phẩy
     * @param string $conditions mệnh đề điều kiện đặt ở đây
     * @param array $params giá trị các tham số ảo trong $conditions 
     * @return array
     */

    public function paginate($page = 1, $perPage = 3, $columns = '*',  $conditions = null, $params = [])
    {
        $sql = "SELECT $columns FROM {$this->table}";

        if ($conditions) {
            $sql .= " WHERE $conditions";
        }

        $offset = ($page - 1) * $perPage;

        //PDO Không hỗ trợ trực tiếp bindParam cho LIMIT và OFFSET
        // Vì vật ta phải sử dụng bindValue or truyền thẳng giá trị luôn cx được.
        $sql .= " LIMIT $perPage OFFSET $offset";

        $stmt = $this->pdo->prepare($sql);

        // Chỉ dùng cách này khi không có param của limit và offset 
        // nếu có param của limit và offset thì phải dùng bindParam cho từng param 1
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


    /**
     * 
     */

    public function find($columns = '*', $conditions = null, $params = [])
    {

        $sql = "SELECT $columns FROM {$this->table}";

        if ($conditions) {
            $sql .= " WHERE $conditions";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Hàm Thêm Dữ liệu
     * 
     * @param array $data
     * @return int
     * 
     */
    public function insert($data)
    {
        $keys = array_keys($data);

        $columns = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }


    public function update($data, $conditions = null, $params = [])
    {
        $keys = array_keys($data);

        $arraySet = array_map(fn($key) => "$key = :set_$key", $keys);

        $sets = implode(', ', $arraySet);

        $sql = "UPDATE {$this->table} SET $sets";
        if ($conditions) {
            $sql .= " WHERE $conditions";
        }
        $stmt = $this->pdo->prepare($sql);
        //bindParams trong set 
        foreach ($data as $key => &$value) {
            $stmt->bindParam(":set_$key", $value);
        }
        // bindParam trong where
        foreach ($params as $key => &$value) {
            $stmt->bindParam(":$key", $value);
        }

        $stmt->execute();

        return $stmt->rowCount();
    }



    public function delete($conditions = null, $params = [])
    {

        $sql = "DELETE FROM {$this->table}";

        if ($conditions) {
            $sql .= " WHERE $conditions";
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
        //rowCount trả về số bản ghi tác động
    }
}
