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
        self::$db = null;
        self::$stmt = null;
        self::$sqlBuild = null;
        self::$instance = null;
    }

    public static function connection($init = true)
    {
        if ($init) {
            self::$stmt = null;
            self::$sqlBuild = null;
        }

        if (empty(self::$db)) {
            $config = ConfigHelper::get('database.mysql.master');
            self::$tablePrefix = $config['table_prefix'];

            $dns = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                $options = [
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_CASE => \PDO::CASE_NATURAL,
                ];
                self::$db = new \PDO($dns, $config['username'], $config['password'], $options);
            } catch (\PDOException $e) {
                throw new \PDOException('SQL: Connection Error');
            }
        }

        if (empty(self::$instance)) {
            self::$instance = new DbHelper();
        }

        return self::$instance;
    }

    public function table(string $table)
    {
        self::connection(false);
        self::$sqlBuild['table'] = '`' . trim(self::$tablePrefix) . trim($table) . '`';
        return $this;
    }

    public function fields(array $fields)
    {
        self::connection(false);
        if (empty($fields)) {
            return $this;
        }

        $selectFields = '`' . implode('`, `', $fields) . '`';
        $selectFields = str_replace('`*`', '*', $selectFields);

        self::$sqlBuild['fields'] = $selectFields;
        return $this;
    }

    public function where(array $where)
    {
        self::connection(false);
        self::$sqlBuild['where'] ??= [];
        self::$sqlBuild['where'] = array_merge(self::$sqlBuild['where'], $where);
        return $this;
    }

    public function whereOr(array $where)
    {
        self::connection(false);
        self::$sqlBuild['where_or'] ??= [];
        self::$sqlBuild['where_or'] = array_merge(self::$sqlBuild['where_or'], $where);
        return $this;
    }

    public function orderBy(array $orderBy)
    {
        self::connection(false);
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
        self::connection(false);
        self::$sqlBuild['limit'] = $offset . ', ' . $count;
        return $this;
    }

    public function page(int $page = 1, int $pageSize = 10)
    {
        return $this->limit(($page - 1) * $pageSize, $pageSize);
    }

    public function select()
    {
        self::connection(false);
        if (empty(self::$sqlBuild['limit'])) {
            self::limit();
        }

        $preData = self::buildSql('select');
        self::$stmt->execute($preData);
        $result = self::$stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->resetSelectResult($result);
    }

    public function find()
    {
        self::connection(false);
        self::limit(0, 1);

        $preData = self::buildSql('select');
        self::$stmt->execute($preData);
        $result = self::$stmt->fetch(\PDO::FETCH_ASSOC);
        return $this->resetSelectResult($result);
    }

    public function update(array $data)
    {
        self::connection(false);
        $preData = self::buildSql('update', $data);
        self::$stmt->execute($preData);
        return self::$stmt->rowCount();
    }

    public function delete()
    {
        self::connection(false);
        $preData = self::buildSql('delete');
        self::$stmt->execute($preData);
        return self::$stmt->rowCount();
    }

    public function insert(array $data)
    {
        self::connection(false);
        $preData = self::buildSql('insert', $data);
        self::$stmt->execute($preData);
        return self::$stmt->rowCount() > 0 ? self::$db->lastInsertId() : 0;
    }

    private function resetSelectResult($result){
        if(empty($result)){
            return [];
        }

        if(isset($result[0])){
            array_walk($result, function(&$item){
                $lastOperation = [];
                if(!empty($item['updated_at'])){
                    $lastOperation[] = date('Y-m-d H:i', $item['updated_at']);
                }
                if(!empty($item['updated_by'])){
                    $lastOperation[] = $item['updated_by'];
                }
                $item['last_operation'] = implode('<br/>', $lastOperation);
            });
        }else{
            $lastOperation = [];
            if(!empty($result['updated_at'])){
                $lastOperation[] = date('Y-m-d H:i', $result['updated_at']);
            }
            if(!empty($result['updated_by'])){
                $lastOperation[] = $result['updated_by'];
            }
            $result['last_operation'] = implode('<br/>', $lastOperation);
        }

        return $result;
    }

    private static function buildWhere()
    {
        $preSql = '';
        $preData = [];

        if (!empty(self::$sqlBuild['where'])) {
            $where = [];
            foreach (self::$sqlBuild['where'] as $field => $value) {
                if (is_array($value)) {
                    $opt = trim(reset($value));
                    $value = end($value);
                    $where[] = '`' . $field . '` ' . $opt . ' ?';
                } else {
                    $where[] = '`' . $field . '` = ?';
                }

                $preData[] = $value;
            }
            $preSql = implode(' AND ', $where);
        }

        if (!empty(self::$sqlBuild['where_or'])) {
            $where = [];
            foreach (self::$sqlBuild['where_or'] as $field => $value) {
                if (is_array($value)) {
                    $opt = trim(reset($value));
                    $value = end($value);
                    $where[] = '`' . $field . '` ' . $opt . ' ?';
                } else {
                    $where[] = '`' . $field . '` = ?';
                }

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
                $setData = [];
                foreach ($data as $field => $value) {
                    $setSql .= '`' . $field . '` = ?, ';
                    $setData[] = $value;
                }
                $setSql = trim($setSql, ', ');

                list($preSql, $preData) = self::buildWhere();
                $preData = array_merge($setData, $preData);
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
            case 'INSERT':
                if (!isset($data['shop_id'])) {
                    throw new \PDOException('SQL: Data Invalid');
                }

                $preSql = 'INSERT INTO ' . self::$sqlBuild['table'] . ' (';
                foreach ($data as $field => $value) {
                    $preSql .= '`' . $field . '`, ';
                    $preData[] = $value;
                }
                $preSql = trim($preSql, ', ') . ') VALUES(' . str_repeat('?, ', count($preData));
                $preSql = trim($preSql, ', ') . ')';
                break;
            default:
                throw new \PDOException('SQL: Build Type Error');
        }

//        print_r($preSql);
//        print_r(PHP_EOL);
//        print_r($preData);
//        print_r(PHP_EOL);
        try {
            self::$stmt = self::$db->prepare($preSql);
        } catch (\PDOException $e) {
            // 断连重连机制
            if (strtoupper($e->getCode()) == 'HY000') {
                self::$db = null;
                self::connection();
                self::$stmt = self::$db->prepare($preSql);
            } else {
                throw $e;
            }
        }
        return $preData;
    }
}
