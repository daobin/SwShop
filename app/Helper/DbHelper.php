<?php
/**
 * 数据库操作助手
 * User: dao bin
 * Date: 2021/7/9
 * Time: 11:10
 */
declare(strict_types=1);

namespace App\Helper;

class DbHelper
{
    /**
     * @var \PDO
     */
    private static $db;
    /**
     * @var \PDOStatement
     */
    private static $stmt;
    private static $sqlBuild;
    private static $tablePrefix;
    private static $instance;

    private function __construct()
    {
    }

    public function __destruct()
    {
        self::$stmt = null;
        self::$sqlBuild = null;
    }

    /**
     * @return DbHelper
     */
    public static function connection()
    {
        if (empty(self::$db)) {
            $config = ConfigHelper::get('database.mysql.master');
            $dns = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );
            self::$tablePrefix = $config['table_prefix'];

            try {
                self::$db = new \PDO($dns, $config['username'], $config['password']);
            } catch (\PDOException $e) {
                throw new \PDOException('SQL: Connection Error');
            }
        }

        if (empty(self::$instance)) {
            self::$instance = new DbHelper();
        }

        return self::$instance;
    }

    public function table($table)
    {
        self::connection();

        self::$sqlBuild['table'] = '`' . trim(self::$tablePrefix) . trim($table) . '`';
        return $this;
    }

    public function fields(array $fields)
    {
        self::connection();

        if (empty($fields)) {
            return self::$instance;
        }

        $selectFields = '`' . implode('`, `', $fields) . '`';
        $selectFields = str_replace('`*`', '*', $selectFields);

        self::$sqlBuild['fields'] = $selectFields;
        return $this;
    }

    public function where(array $where)
    {
        self::connection();

        self::$sqlBuild['where'] ??= [];
        self::$sqlBuild['where'] = array_merge(self::$sqlBuild['where'], $where);
        return $this;
    }

    public function whereOr(array $where)
    {
        self::connection();

        self::$sqlBuild['where_or'] ??= [];
        self::$sqlBuild['where_or'] = array_merge(self::$sqlBuild['where_or'], $where);
        return $this;
    }

    public function orderBy(array $orderBy)
    {
        self::connection();
        if (empty($orderBy)) {
            return $this;
        }

        self::$sqlBuild['order_by'] = [];
        foreach ($orderBy as $field => $direct) {
            $direct = strtoupper($direct) == 'DESC' ? 'DESC' : 'ASC';
            self::$sqlBuild['order_by'][] = '`' . $field . '` ' . $direct;
        }
        self::$sqlBuild['order_by'] = implode(', ', self::$sqlBuild['order_by']);

        return $this;
    }

    public function limit(int $offset = 0, int $count = 10)
    {
        self::connection();

        self::$sqlBuild['limit'] = $offset . ', ' . $count;
        return $this;
    }

    public function page(int $page = 1, int $pageSize = 10)
    {
        return $this->limit(($page - 1) * $pageSize, $pageSize);
    }

    public function select()
    {
        self::connection();
        if (empty(self::$sqlBuild['limit'])) {
            self::limit();
        }

        $preData = self::buildSql('select');
        self::$stmt->execute($preData);
        return self::$stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function find()
    {
        self::connection();
        self::limit(0, 1);

        $preData = self::buildSql('select');
        self::$stmt->execute($preData);
        return self::$stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function update(array $data)
    {

    }

    public function delete()
    {

    }

    public function insert()
    {

    }

    private static function buildWhere()
    {
        $preSql = '';
        $preData = [];

        if (!empty(self::$sqlBuild['where'])) {
            $where = [];
            foreach (self::$sqlBuild['where'] as $field => $value) {
                $where[] = '`' . $field . '` = ?';
                $preData[] = $value;
            }
            $preSql = implode(' AND ', $where);
        }

        if (!empty(self::$sqlBuild['where_or'])) {
            $where = [];
            foreach (self::$sqlBuild['where_or'] as $field => $value) {
                $where[] = '`' . $field . '` = ?';
                $preData[] = $value;
            }
            if ($preSql) {
                $preSql .= ' AND (' . implode(' OR ', $where) . ')';
            } else {
                $preSql = implode(' OR ', $where);
            }

        }

        if (empty($preSql) || empty($preData)) {
            throw new \PDOException('SQL: Condition Build Invalid');
        }

        return [$preSql, $preData];
    }

    private static function buildSql(string $buildType, array $data = [])
    {
        if (empty(self::$sqlBuild['table'])) {
            throw new \PDOException('SQL: Table Invalid');
        }

        $preData = [];
        $buildType = strtoupper($buildType);
        switch ($buildType) {
            case 'SELECT':
                if (
                    substr(self::$sqlBuild['table'], 0, 8) != '`hd_sys_'
                    && !isset(self::$sqlBuild['where']['shop_id'])
                ) {
                    throw new \PDOException('SQL: Condition Invalid');
                }

                if (empty(self::$sqlBuild['fields'])) {
                    self::$sqlBuild['fields'] = '*';
                }
                list($preSql, $preData) = self::buildWhere();
                $preSql = 'SELECT ' . self::$sqlBuild['fields'] . ' FROM ' . self::$sqlBuild['table'] . ' WHERE ' . $preSql;
                if (!empty(self::$sqlBuild['order_by'])) {
                    $preSql .= ' ORDER BY ' . self::$sqlBuild['order_by'];
                }
                if (!empty(self::$sqlBuild['limit'])) {
                    $preSql .= ' LIMIT ' . self::$sqlBuild['limit'];
                }
                break;
            case 'UPDATE':
                if (
                    substr(self::$sqlBuild['table'], 0, 8) != '`hd_sys_'
                    && !isset(self::$sqlBuild['where']['shop_id'])
                ) {
                    throw new \PDOException('SQL: Condition Invalid');
                }
                if (empty($data)) {
                    throw new \PDOException('SQL: Data Invalid');
                }

                $setSql = '';
                list($preSql, $preData) = self::buildWhere();
                foreach ($data as $field => $value) {
                    $setSql .= '`' . $field . '` = ?, ';
                    $preData[] = $value;
                }
                $setSql = trim($setSql, ', ');

                $preSql = 'UPDATE ' . self::$sqlBuild['table'] . ' SET ' . $setSql . ' WHERE ' . $preSql;
                break;
            case 'DELETE':
                if (
                    substr(self::$sqlBuild['table'], 0, 8) != '`hd_sys_'
                    && !isset(self::$sqlBuild['where']['shop_id'])
                ) {
                    throw new \PDOException('SQL: Condition Invalid');
                }

                list($preSql, $preData) = self::buildWhere();
                $preSql = 'DELETE FROM ' . self::$sqlBuild['table'] . ' WHERE ' . $preSql;
                break;
            case 'INSERT INTO':
                if (!isset($data['shop_id'])) {
                    throw new \PDOException('SQL: Data Invalid');
                }

                $preSql = 'INSERT INTO ' . self::$sqlBuild['table'] . ' (';
                foreach ($data as $field => $value) {
                    $preSql .= '`' . $field . '`, ';
                    $preData[] = $value;
                }
                $preSql = trim($preSql, ', ') . ') VALUES(' . str_repeat('?, ', count($preData));
                $preSql = trim($preSql, '?, ') . ')';
                break;
            default:
                throw new \PDOException('SQL: Build Type Error');
        }

        self::$stmt = self::$db->prepare($preSql);
        return $preData;
    }
}
