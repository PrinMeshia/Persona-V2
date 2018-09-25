<?php
namespace Models;

use Framework\Lib\Database\Model;


class PostsModel extends Model

{
    public function FindPaginatedPublic(int $limit)
    {
        return $this->query("SELECT p.* ,c.title category_title,c.slug category_slug
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                     ORDER BY created_at DESC LIMIT $limit");
    }
    public function FindPaginatedPublicForCategory(int $limit, int $categoryId)
    {
        return $this->query("SELECT p.* 
                    FROM {$this->table} p
                    WHERE p.category_id = ?
                    ORDER BY created_at DESC LIMIT $limit", [$categoryId]);
    }
    public function FindPaginated(int $limit) : array
    {
        return $this->query("SELECT p.id,p.title,p.slug,c.title category 
                    FROM {$this->table} p
                    LEFT JOIN categories c ON p.category_id = c.id
                     ORDER BY created_at DESC LIMIT $limit");
    }
}