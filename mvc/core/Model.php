<?php
class Model {
	protected $_pdo                 = NULL;
	protected $_sta                 = NULL;
	protected $_host                = DB_HOST;
	protected $_user                = DB_USER;
	protected $_pwd                 = DB_PWD;
    protected $_dbname              = DB_NAME;
    protected $_prefix              = TB_PREFIX;
	protected $_table               = '';
	protected $_tableTmp            = '';
    protected $_query               = '';             // query
    protected $_show;                                 // All query
    protected $_data                = [];             // array column    
    protected $_where               = '';             // where and | or | in
    protected $_orderby             = '';             // order by
    protected $_limit               = '';             // limit
	protected $_concat              = true;           // append where, order and limit
    protected $_transactionCount    = 0;              // 
    
	// Phương thức khởi tạo
	public function __construct($params = []) {        
        if (empty($params)) {
			$params = [
                'host' => $this->_host,
                'user' => $this->_user,
                'pwd' => $this->_pwd,
                'dbname' => $this->_dbname,
            ];
        }
		$this->connect ($params);
	}
	
	// Phương thức kết nối cơ sở dữ liệu
	protected function connect($params) {
		try {
			$this->_pdo = new \PDO ( 'mysql:host=' . $params['host'] . ';dbname=' . $params['dbname'], $params['user'], $params['pwd'] );
			$this->_pdo->query ( 'set names "utf8"' );
		} catch ( \PDOException $ex ) {
			die ( $ex->getMessage () );
		}
    }

    // Phương thức thiết lập câu truy vấn
	public function setQuery($query) {
        if($query){
            $this->_query = $query;
            if($this->_concat){  
                if($this->_where){
                    if(preg_match('#(WHERE|Where|where)#', $this->_query)){
                        $this->_query .=  $this->_where;
                    }else{
                        $this->_query .= ' WHERE ' . $this->_where;
                    }
                }
                $this->_query .= $this->_orderby . $this->_limit;                  
                $this->_show[$this->_query] = true;
                $this->resetParams();      
            }
            $this->_concat = true;
        }
        return $this;
    } 

    // Phương thức trả về câu truy vấn đầy đủ
    public function getQuery(){
        if($this->_concat && $this->_where.$this->_orderby.$this->_limit){
            if($lastkey = Func::getLastKeyArray($this->_show)){
                unset($this->_show[$lastkey]);
            }
            $this->setQuery($this->_query);
        }
    }

    // Phương thức reset query params
    protected function resetParams() { 
        $this->_tableTmp = '';
        $this->_data = '';
        $this->_where = '';
        $this->_orderby = '';
        $this->_limit = '';       
    }

    // Phương thức reset query
    protected function resetQuery() { 
        $this->_query = '';    
    }
	
	// Phương thức thực thi câu truy vấn
	protected function execute($options = array()) {
        $this->getQuery();
        if($this->_query){
            $this->_sta = $this->_pdo->prepare ( $this->_query );
            if ($options) {
                for($i = 0; $i < count ( $options ); $i ++) {
                    $this->_sta->bindParam ( $i + 1, $options [$i] );
                }
            }  
            $this->resetQuery();          
            $this->_sta->execute ();            
            return $this->_sta;
        }
    }

    // Phương thức pdoFetch lấy ra dữ liệu
    protected function pdoFetch($query, $fetchAll = true, $options = array()){
        if($query){   
            $this->setQuery($query);
        }else{
            if(!$this->_query){
                $data  = $this->selectColums($this->_data);
                $table = $this->getTable(true);
                if($data && $table){
                    $this->setQuery('SELECT '.$data.' FROM `'. $table .'`');
                }
            }
        }
        if (! $result = $this->execute ($options)) {
            return false;
        }
        if($fetchAll == true){
            return $result->fetchAll ( \PDO::FETCH_ASSOC );
        }else{
            return $result->fetch ( \PDO::FETCH_ASSOC );
        }
    }

    // Phương thức SAVEPOINT Trans
    public function beginTransaction()
    {
        if (!$this->_transactionCount++) {
            return $this->_pdo->beginTransaction();
        }
        $this->_pdo->exec('SAVEPOINT trans'.$this->_transactionCount);
        return $this->_transactionCount >= 0;
    }

    // Phương thức Commit
    public function commit()
    {
        if (!--$this->_transactionCount) {
            return $this->_pdo->commit();
        }
        return $this->_transactionCount >= 0;
    }

    // Phương thức rollback
    public function rollback()
    {
        if (--$this->_transactionCount) {
            $this->_pdo->exec('ROLLBACK TO trans'.($this->_transactionCount + 1));
            return true;
        }
        return $this->_pdo->rollback();
    }
	
	// Phương thức thiết lập tên database
	public function setDatabase($database) {
		$this->_dbname = $database;
    }
    
    // Phương thức trả về tên database
	public function getDatabase() {
		return $this->_dbname;
	}
	
	// Phương thức thiết lập tên bảng
	public function setTable($table = NULL, $tmp = false) {
		if (! empty ( $table )) {
            $table = $this->_prefix . $table;
            if($tmp == false){
                $this->_table = $table;
            }else{
                $this->_tableTmp = $table;
            }
            return $table;
        }
        return $this->_table;
    }
    
    // Phương thức trả về tên bảng
	public function getTable($currentQuery = false) {
        if($currentQuery == true && $this->_tableTmp){
            return $this->_tableTmp;
        }
        return $this->_table;
    }

     // Phương thức tất cả bảng của database
     public function getTablesName()
     {
        $this->_concat = false;
        $query = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='".$this->_dbname."'";
        return $this->fetchAll($query);
     }
 
     // Phương thức trả về tất cả cột của một bảng
     public function getColumnsName($table = '')
     {
        $this->_concat = false;
        $query  = "SELECT COLUMN_NAME, DATA_TYPE, COLUMN_DEFAULT, NUMERIC_PRECISION, COLUMN_TYPE, COLUMN_KEY, EXTRA FROM INFORMATION_SCHEMA.COLUMNS ";
        $query .= "WHERE TABLE_SCHEMA='".$this->_dbname."' AND TABLE_NAME='" . $this->setTable($table, true) . "'";
        return $this->fetchAll($query);
     }

    // Phương thức trả về trường giá trị select
    public function selectColums($options = array(), $prefix = ''){
        $listColumns = ['*'];
        if($options){
            $listColumns = [];
            if($prefix){  $prefix = $prefix.'.'; }
            foreach($options as $column){
                $listColumns[] = $prefix.'`'.$column.'`';
            }
        }
        return \implode(',', $listColumns);
    }

    // Phương thức trường giá trị select và đổi tên trường mới
    public function selectAsColumns($options, $prefix){
        $columns = [];
        if(is_array($options)){            
            foreach($options as $value){
                $columns[] = $prefix.'.`'.$value.'` as `'.$prefix.'_'.$value.'`';
            }            
        }elseif(is_string($options)){
            $items = $this->getColumnsName($options);
            if($items){
                foreach($items as $value){
                    $columns[] = $prefix.'.`'.$value['COLUMN_NAME'].'` as `'.$prefix.'_'.$value['COLUMN_NAME'].'`';
                }
            }
        }
        return \implode(',', $columns);
    }
     
    // Phương thức tạo thiết lập giá trị cột cho thêm dữ liệu vào bảng
    public function getColumnsInsert($table = ''){
        $columns = [];
        $items = $this->getColumnsName($table);
        if($items){
            foreach($items as $value){
                $type = '';
                if($value['COLUMN_DEFAULT']){    // default
                    $type = $value['COLUMN_DEFAULT'];
                    if($type == 'NULL' || $type == 'null'){
                        $type = NULL;
                    }
                }
                if($value['NUMERIC_PRECISION']){
                    if($value['COLUMN_KEY'] && $value['EXTRA']){
                        $type = NULL; // interger PRI auto_increment
                    }else{
                        $type = 0; // interger
                    }
                }                 
                $columns[$value['COLUMN_NAME']] = $type;
            }
        }
        return  $columns;
    }

    // Phương thức tạo ra câu truy vấn insert
    /*
        $options = [
            'table'=>'{table}', 
            'data'=>[
                '{column1}'=>'{value1}',
                '{column2}'=>'{value2}',
                ....
            ]
            // Or
            'data'=>[
                '{column1}',
                '{column2}',
                ....
            ]
        ];
    */
    public function query($options = array()){
        // Set table
        if(isset($options['table'])){
            $this->setTable($options['table'], true);           
        }
        // Set data
        if(isset($options['data'])){
            $this->_data = $options['data'];
        }
        return $this;
    }

    // Phương thức tạo dữ liệu insert
    public function insertValue($options = array()){
        if($options){
            $cols = $vals = '';
            foreach ( $options as $key => $value ) {              
                $cols .= ", `$key`";               
                $vals .= ", ". (\is_null ( $value ) ? ("NULL") : ("'" . \addslashes($value). "'"));
            }
            return [
                        'cols'  =>  '(' . \substr($cols, 2) . ')',
                        'vals'  =>  '(' . \substr($vals, 2) . ')'                
                   ];
        }
    }

    // Phương thức tạo dữ liệu update
    public function updateValue($options = array()){
        if($options){
            $setValue = '';
            foreach ($options as $key => $value) {
                $setValue .= ",`" . \addslashes($key) . "`='" . \addslashes($value) . "'";
            }
            return \substr($setValue, 1);
        }
    }

    // Phương thức tạo điều kiện truy vấn
    public function where($column, $operator, $value)
    {
        $this->_where .= " `" . \addslashes($column) . "` $operator '" . \addslashes($value) . "' ";
        return $this;
    }

    // Phương thức tạo điều kiện câu truy vấn AND
    public function and($column, $operator, $value){
        $this->_where .= " AND ";
        return $this->where($column, $operator, $value);        
    }

    // Phương thức tạo điều kiện câu truy vấn OR
    public function or($column, $operator, $value){
        $this->_where .= " OR ";
        return $this->where($column, $operator, $value);
    }

     // Phương thức tạo điều kiện truy vấn string
     public function whereStr($str = '')
     {
         $this->_where .= ' '.$str;
         return $this;
     }

    // Phương thức sắp xếp dữ liệu
    public function orderby($column, $type = 'DESC', $prefix = ''){
        if($prefix){
            $prefix = $prefix.'.';
        }
        if(!$this->_orderby) {
            $this->_orderby .=' ORDER BY ';
        } else {
            $this->_orderby .= ', ';
        }
        $this->_orderby .= $prefix.'`' . \addslashes($column) . '` ' . \addslashes($type);
        return $this;
    }

    // Phương thức giới hạn dòng dữ liệu
    public function limit($position, $length = RD_LIMIT){
        $this->_limit = ' LIMIT ' . \addslashes($position) . ', ' . \addslashes($length);
        return $this;
    }

    // Phương thức tạo string từ mảng truy vấn
    public function convertValueIn($option = array())
    {
        $array = array();
        if($option){
	        foreach ($option as $value) {
	            $value = trim($value);
	            if (! empty($value)) {
	                $array[] = "'" . \addslashes(trim($value)) . "'";
	            }
	        }
        }
        return implode(',', $array);
    }

    // Phương thức đọc một dòng dữ liệu $assoc=true trả về Array; $assoc=false trả về Object
    public function fetch($query = '', $options = array()){
        return $this->pdoFetch($query, false, $options);
    }

    // Phương thức đọc nhiều dòng dữ liệu $assoc=true trả về Array; $assoc=false trả về Object
    public function fetchAll($query = '', $options = array()){
        return $this->pdoFetch($query, true, $options);
    }

    // Phương thức thêm dữ liệu
    public function insert($query = '', $options = array()){
        if($query){   
            $this->setQuery($query);
        }else{
            if(!$this->_query){
                $table = $this->getTable(true);
                if($table && $this->_data){
                    $default = $this->getColumnsInsert($table); 
                    $data = array_merge($default, $this->_data);
                    $data = $this->insertValue($data);
                    $this->setQuery('INSERT INTO `'. $table .'`' . $data['cols'] . ' VALUES ' . $data['vals']);
                } 
            }
        }
        $this->execute($options);
        return $this->getLastId();
    }

    // Phương thức cập nhật dữ liệu
    public function update($query = '', $options = array()){
        if($query){   
            $this->setQuery($query);
        }else{
            if(!$this->_query){
                $data = $this->updateValue($this->_data);
                $table = $this->getTable(true);
                if($table && $data){
                    $this->setQuery('UPDATE `'. $table .'` SET ' . $data);
                } 
            }
        }
        $this->execute($options);
        return $this->getRowCount();
    }

    // Phương thức xóa dữ liệu
    public function delete($query = '', $options = array()){
        if($query){   
            $this->setQuery($query);
        }else{
            $table = $this->getTable(true);
            if(!$this->_query && $table){
                $this->setQuery('DELETE FROM `'. $table .'` ');
            }
        }
        $this->execute($options);
        return $this->getRowCount();
    }

    // Phương thức đếm dòng trả về khi truy vấn
    public function getRowCount()
    {
        return $this->_sta->rowCount();
    }

    // Phương thức trả về id cuối cùng của bảng
    public function getLastId()
    {
        return $this->_pdo->lastInsertId();
    }

    // Phương thức hiển thị câu truy vấn
    public function show(){
        $list = '';
        if($this->_show){
            foreach($this->_show as $query => $value){
                $list .= '<h3>'.$query.'</h3>';
            }
        }
        return $list;
    }
   
    // Phước thức ngắt kết nối database
    public function disconnect()
    {
        $this->_pdo = NULL;
    }

    // Phương thức ngắt kết nối database tự động
    public function __destruct()
    {
       $this->disconnect();
    }

}
?>