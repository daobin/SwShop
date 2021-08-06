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
    private $db;
    /**
     * @var \PDOStatement
     */
    private $stmt;
    private $sqlBuild;
    private $tablePrefix;

    public function __construct()
    {
        $this->initDb();
    }

    private function initDb()
    {
        $config = ConfigHelper::get('database.mysql.master');
        $this->tablePrefix = trim($config['table_prefix']);

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
            $this->db = new \PDO($dns, $config['username'], $config['password'], $options);
        } catch (\PDOException $e) {
            throw new \PDOException('SQL: Connection Error');
        }
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }

    public function table(string $table, string $as = '')
    {
        $this->sqlBuild = [];

        if (empty($as)) {
            $this->sqlBuild['table'] = '`' . $this->tablePrefix . trim($table) . '`';
        } else {
            $this->sqlBuild['table'] = '`' . $this->tablePrefix . trim($table) . '` as `' . $as . '`';
        }

        return $this;
    }

    public function join(string $table, string $as = '', array $on = [])
    {
        $table = trim($table);
        if (isset($this->sqlBuild['join'][$table])) {
            return $this;
        }

        $onSql = [];
        if (!empty($on)) {
            foreach ($on as $field => $field2) {
                $field = str_replace('.', '`.`', $field);
                $field2 = str_replace('.', '`.`', $field2);

                $onSql[] = '`' . $field . '` = `' . $field2 . '`';
            }
            $onSql = implode(' AND ', $onSql);
        } else {
            $onSql = '';
        }

        if (empty($as)) {
            $this->sqlBuild['join'][$table] = [
                'table' => '`' . $this->tablePrefix . $table . '`',
                'on' => $onSql
            ];
        } else {
            $this->sqlBuild['join'][$table] = [
                'table' => '`' . $this->tablePrefix . $table . '` as `' . $as . '`',
                'on' => $onSql
            ];
        }
        return $this;
    }

    public function fields(array $fields)
    {
        if (empty($fields)) {
            return $this;
        }

        $selectFields = '`' . implode('`, `', $fields) . '`';
        $selectFields = str_replace(['`*`', '.'], ['*', '`.`'], $selectFields);

        $this->sqlBuild['fields'] = $selectFields;
        return $this;
    }

    public function where(array $where)
    {
        if (empty($where)) {
            return $this;
        }

        $this->sqlBuild['where'] ??= [];
        $this->sqlBuild['where'] = array_merge($this->sqlBuild['where'], $where);
        return $this;
    }

    public function whereOr(array $where)
    {
        if (empty($where)) {
            return $this;
        }

        $this->sqlBuild['where_or'] ??= [];
        $this->sqlBuild['where_or'] = array_merge($this->sqlBuild['where_or'], $where);
        return $this;
    }

    public function groupBy(array $groupBy)
    {
        if (empty($groupBy)) {
            return $this;
        }

        $this->sqlBuild['group_by'] = [];
        foreach ($groupBy as $field) {
            $field = str_replace('.', '`.`', $field);
            $this->sqlBuild['group_by'][] = '`' . $field . '`';
        }
        $this->sqlBuild['group_by'] = implode(', ', $this->sqlBuild['group_by']);

        return $this;
    }

    public function orderBy(array $orderBy)
    {
        if (empty($orderBy)) {
            return $this;
        }

        $this->sqlBuild['order_by'] = [];
        foreach ($orderBy as $field => $direct) {
            $direct = strtoupper($direct) == 'DESC' ? 'DESC' : 'ASC';
            $field = str_replace('.', '`.`', $field);
            $this->sqlBuild['order_by'][] = '`' . $field . '` ' . $direct;
        }
        $this->sqlBuild['order_by'] = implode(', ', $this->sqlBuild['order_by']);

        return $this;
    }

    public function limit(int $offset = 0, int $count = 10)
    {
        $this->sqlBuild['limit'] = $offset . ', ' . $count;
        return $this;
    }

    public function page(int $page = 1, int $pageSize = 10)
    {
        return $this->limit(($page - 1) * $pageSize, $pageSize);
    }

    public function select()
    {
        $preData = $this->buildSql('select');
        $this->stmt->execute($preData);
        $result = $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->resetSelectResult($result);
    }

    public function find()
    {
        $this->limit(0, 1);

        $preData = $this->buildSql('select');
        $this->stmt->execute($preData);
        $result = $this->stmt->fetch(\PDO::FETCH_ASSOC);
        return $this->resetSelectResult($result);
    }

    public function update(array $data)
    {
        $preData = $this->buildSql('update', $data);
        $this->stmt->execute($preData);
        return $this->stmt->rowCount();
    }

    public function delete()
    {
        $preData = $this->buildSql('delete');
        $this->stmt->execute($preData);
        return $this->stmt->rowCount();
    }

    public function insert(array $data)
    {
        $preData = $this->buildSql('insert', $data);
        $this->stmt->execute($preData);
        return $this->stmt->rowCount() > 0 ? (int)$this->db->lastInsertId() : 0;
    }

    private function resetSelectResult($result)
    {
        if (empty($result)) {
            return [];
        }

        if (isset($result[0])) {
            array_walk($result, function (&$item) {
                if (isset($item['value_type'])) {
                    switch (strtolower($item['value_type'])) {
                        case 'int':
                            if (isset($item['config_value'])) {
                                $item['config_value'] = (int)$item['config_value'];
                            }
                            break;
                        case 'password':
                            if (isset($item['config_value'])) {
                                $item['config_value'] = SafeHelper::decodeString($item['config_value']);
                            }
                            break;
                        case 'list':
                            if (isset($item['config_value'])) {
                                $item['config_value'] = json_decode($item['config_value'], true);
                            }
                            break;
                    }
                }

                $lastOperation = [];
                if (!empty($item['updated_at'])) {
                    $lastOperation[] = date('Y-m-d H:i', $item['updated_at']);
                }
                if (!empty($item['updated_by'])) {
                    $lastOperation[] = strtoupper($item['updated_by']);
                }
                $item['last_operation'] = implode(' By ', $lastOperation);
            });
        } else {
            if (isset($result['value_type'])) {
                switch (strtolower($result['value_type'])) {
                    case 'int':
                        if (isset($result['config_value'])) {
                            $result['config_value'] = (int)$result['config_value'];
                        }
                        break;
                    case 'password':
                        if (isset($result['config_value'])) {
                            $result['config_value'] = SafeHelper::decodeString($result['config_value']);
                        }
                        break;
                    case 'list':
                        if (isset($result['config_value'])) {
                            $result['config_value'] = json_decode($result['config_value'], true);
                        }
                        break;
                }
            }

            $lastOperation = [];
            if (!empty($result['updated_at'])) {
                $lastOperation[] = date('Y-m-d H:i', $result['updated_at']);
            }
            if (!empty($result['updated_by'])) {
                $lastOperation[] = strtoupper($result['updated_by']);
            }
            $result['last_operation'] = implode(' By ', $lastOperation);
        }

        return $result;
    }

    private function buildWhere()
    {
        $preSql = '';
        $preData = [];

        if (!empty($this->sqlBuild['where'])) {
            $where = [];
            foreach ($this->sqlBuild['where'] as $field => $value) {
                $field = str_replace('.', '`.`', $field);
                if (is_array($value)) {
                    $opt = trim(reset($value));
                    $value = end($value);
                    switch (strtolower($opt)) {
                        case 'in':
                            $inSql = '`' . $field . '` in (%s)';
                            $where[] = sprintf($inSql, implode(', ', array_fill(0, count($value), '?')));
                            break;
                        default:
                            $where[] = '`' . $field . '` ' . $opt . ' ?';
                    }
                } else {
                    $where[] = '`' . $field . '` = ?';
                }

                if (is_array($value)) {
                    foreach ($value as $val) {
                        $preData[] = $val;
                    }
                } else {
                    $preData[] = $value;
                }
            }
            $preSql = implode(' AND ', $where);
        }

        if (!empty($this->sqlBuild['where_or'])) {
            $where = [];
            foreach ($this->sqlBuild['where_or'] as $field => $value) {
                $field = str_replace('.', '`.`', $field);
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

        if (
            substr($this->sqlBuild['table'], 0, 8) != '`hd_sys_'
            && substr_count($preSql, '`shop_id`') < 1
        ) {
            throw new \PDOException('SQL: Condition Invalid');
        }

        return [$preSql, $preData];
    }

    private function buildSql(string $buildType, array $data = [])
    {
        if (empty($this->sqlBuild['table'])) {
            throw new \PDOException('SQL: Table Invalid');
        }

        $preData = [];
        $buildType = strtoupper($buildType);
        switch ($buildType) {
            case 'SELECT':
                if (empty($this->sqlBuild['fields'])) {
                    $this->sqlBuild['fields'] = '*';
                }
                list($preSql, $preData) = $this->buildWhere();
                $preSql = 'SELECT ' . $this->sqlBuild['fields'] . ' FROM ' . $this->sqlBuild['table'] . ' %s WHERE ' . $preSql;

                $joinSql = '';
                if (!empty($this->sqlBuild['join'])) {
                    foreach ($this->sqlBuild['join'] as $join) {
                        $joinSql .= 'JOIN ' . $join['table'];
                        if (!empty($join['on'])) {
                            $joinSql .= ' ON (' . $join['on'] . ')';
                        }
                    }
                }
                $preSql = sprintf($preSql, $joinSql);

                if (!empty($this->sqlBuild['group_by'])) {
                    $preSql .= ' GROUP BY ' . $this->sqlBuild['group_by'];
                }
                if (!empty($this->sqlBuild['order_by'])) {
                    $preSql .= ' ORDER BY ' . $this->sqlBuild['order_by'];
                }
                if (!empty($this->sqlBuild['limit'])) {
                    $preSql .= ' LIMIT ' . $this->sqlBuild['limit'];
                }
                break;
            case 'UPDATE':
                if (empty($data)) {
                    throw new \PDOException('SQL: Data Invalid');
                }

                $setSql = '';
                $setData = [];
                foreach ($data as $field => $value) {
                    $field = str_replace('.', '`.`', $field);
                    $setSql .= '`' . $field . '` = ?, ';
                    $setData[] = $value;
                }
                $setSql = trim($setSql, ', ');

                list($preSql, $preData) = $this->buildWhere();
                $preData = array_merge($setData, $preData);
                $preSql = 'UPDATE ' . $this->sqlBuild['table'] . ' SET ' . $setSql . ' WHERE ' . $preSql;
                break;
            case 'DELETE':
                list($preSql, $preData) = $this->buildWhere();
                $preSql = 'DELETE FROM ' . $this->sqlBuild['table'] . ' WHERE ' . $preSql;
                break;
            case 'INSERT':
                if (empty($data['shop_id'])) {
                    throw new \PDOException('SQL: Data Invalid');
                }

                $preSql = 'INSERT INTO ' . $this->sqlBuild['table'] . ' (';
                foreach ($data as $field => $value) {
                    $field = str_replace('.', '`.`', $field);
                    $preSql .= '`' . $field . '`, ';
                    $preData[] = $value;
                }
                $preSql = trim($preSql, ', ') . ') VALUES(' . str_repeat('?, ', count($preData));
                $preSql = trim($preSql, ', ') . ')';
                break;
            default:
                throw new \PDOException('SQL: Build Type Error');
        }

//        print_r($preSql . PHP_EOL);
//        print_r($preData);
        try {
            $this->stmt = $this->db->prepare($preSql);
        } catch (\PDOException $e) {
            // 断连重连机制
            if (strtoupper($e->getCode()) == 'HY000') {
                $this->db = null;
                $this->initDb();
                $this->stmt = $this->db->prepare($preSql);
            } else {
                throw $e;
            }
        }
        return $preData;
    }
}
