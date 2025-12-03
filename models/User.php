<?php

class User extends BaseModel
{
    protected $table = 'users';

    public function all()
    {
        $sql = "SELECT u.*, r.name AS role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id ORDER BY u.id DESC";
        $stmt = $this->getPdo()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->getPdo()->prepare("SELECT u.*, r.name AS role_name FROM users u LEFT JOIN roles r ON r.id = u.role_id WHERE u.id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function create($data)
    {
        $stmt = $this->getPdo()->prepare(
            "INSERT INTO users (username, password_hash, image, role_id, tour_guide_id, is_active) VALUES (:username, :password_hash, :image, :role_id, :tour_guide_id, :is_active)"
        );
        $stmt->execute([
            'username' => $data['username'],
            'password_hash' => $data['password_hash'],
            'image' => $data['image'] ?? null,
            'role_id' => $data['role_id'],
            'tour_guide_id' => $data['tour_guide_id'] ?? null,
            'is_active' => $data['is_active'] ?? 1,
        ]);
        return $this->getPdo()->lastInsertId();
    }

    public function update($id, $data)
    {
        // Build dynamic update query based on provided fields
        $fields = [];
        $params = ['id' => $id];
        
        if (isset($data['username'])) {
            $fields[] = 'username = :username';
            $params['username'] = $data['username'];
        }
        
        if (isset($data['password_hash'])) {
            $fields[] = 'password_hash = :password_hash';
            $params['password_hash'] = $data['password_hash'];
        }
        
        if (isset($data['image'])) {
            $fields[] = 'image = :image';
            $params['image'] = $data['image'];
        }
        
        if (isset($data['role_id'])) {
            $fields[] = 'role_id = :role_id';
            $params['role_id'] = $data['role_id'];
        }
        
        if (isset($data['tour_guide_id'])) {
            $fields[] = 'tour_guide_id = :tour_guide_id';
            $params['tour_guide_id'] = $data['tour_guide_id'];
        }
        
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = :is_active';
            $params['is_active'] = $data['is_active'];
        }
        
        if (empty($fields)) {
            return false;
        }
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->getPdo()->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id)
    {
        $stmt = $this->getPdo()->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function toggleActive($id)
    {
        $user = $this->find($id);
        if (!$user) return false;
        $new = ($user['is_active'] ?? 1) ? 0 : 1;
        $stmt = $this->getPdo()->prepare("UPDATE users SET is_active=:new WHERE id=:id");
        return $stmt->execute(['new' => $new, 'id' => $id]);
    }

    public function findByUsername($username)
    {
        $stmt = $this->getPdo()->prepare("SELECT u.*, r.name AS role_name FROM users u LEFT JOIN roles r ON r.id=u.role_id WHERE u.username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function verifyPassword($username, $password)
    {
        $user = $this->findByUsername($username);
        if (!$user) return false;
        return password_verify($password, $user['password_hash']);
    }
}
