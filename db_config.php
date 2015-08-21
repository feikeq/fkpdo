<?php
header("Content-Type: text/html; charset= UTF-8");
/** ********************************************************************************
* 废客快速操作数据库PHP操作MySql实例代码 - db_config 配置文件
* @fileName    db_config.php
* @author      feikeq
* @date        2015-08-20
* @input       require_once './db_config.php';
* @function    返回对象  = FK_PDO(表名,字段数组或字符串,条件数组或字符串,分组数组或字符串,排序数组或字符串,行数数组或字符串)
* @_functio    $dbConn = db_connect(); $query = $dbConn->query($SQL); 
**/

/* 定义数据库常量 */
define('DB_HOST', '数据库地址');
define('DB_USER','用户名');
define('DB_PWD','密码');
define('DB_DATABASE','数据库');


/* 定义数据库操作连接 */
function db_connect()
{
    //PDO(PHP Data Object) 是PHP 5新出来的东西，在PHP 6都要出来的时候，PHP 6只默认使用PDO来处理数据库，将把所有的数据库扩展移到了PECL，
    //那么默认就是没有了我们喜爱的php_mysql.dll之类的了，那怎么办捏，我们只有与时俱进了，我就小试了一把PDO。（本文只是入门级的，高手可以略过，呵呵）
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_DATABASE;
    $dbh = new PDO($dsn, DB_USER, DB_PWD); //连接数据库
    $dbh->exec("SET NAMES utf8"); //设置编码
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//设置错误提示方式：错误提示,抛出异常
    return $dbh;
};



/*

 废客快捷操作修改数据库
 返回对象  = FK_PDO(表名,字段数组或字符串,条件数组或字符串,分组数组或字符串,排序数组或字符串,行数数组或字符串);
 实例:

 //    SQL:  INSERT INTO `tabName` ( `name`, `sxe`) VALUES ( 'a', '2') ;
 // FK-PDO:  FK_PDO('tabName',array('name' =>'a' , 'sxe'=>'2')    );

 //    SQL:  UPDATE `tabName` SET  `name`='b', `sxe`='1' WHERE `id` = '123' AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%' ;
 // FK-PDO:  FK_PDO('tabName',array('name' =>'b' , 'sxe'=>'1'),array('id' =>'123' , 'age'=>'["!=","2"]','name'=>array("=","a"), 'book' =>'["like","泉%"]'  ));
 // FK-PDO:  FK_PDO('tabName',array('name' =>'a' , 'sxe'=>'2'),"`id` = 123 AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%'" ) ;

 //    SQL:  SELECT  *  FROM  `tabName`  ;
 // FK-PDO:  FK_PDO('tabName','*' );
 // FK-PDO:  FK_PDO('tabName',"" );
 // FK-PDO:  FK_PDO('tabName',FALSE );
 // FK-PDO:  FK_PDO('tabName');


 //    SQL:  SELECT  `id`, `name`  FROM  `tabName` ;
 // FK-PDO:  FK_PDO('tabName',array('id','name') );
 // FK-PDO:  FK_PDO('tabName','`id`, name`' );


 //    SQL:  SELECT  `id`, name`  FROM  `tabName` WHERE `id` = '123' AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%'    ;
 // FK-PDO:  FK_PDO('tabName','`id`, name`',array('id' =>'123' , 'age'=>'["!=","2"]','name'=>array("=","a"), 'book' =>'["like","泉%"]'  )  );
 // FK-PDO:  FK_PDO('tabName','`id`, name`','`id` = 123 AND  `age` != 2 AND  `name` = "a" AND  `book` like "泉%"'  );


 //    SQL:  SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe`   ;
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe')  );
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`'  );


 //    SQL:  SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC  ;
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC')  );
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`', '`name` , `sxe` DESC, `case` ASC'  );


 //    SQL:  SELECT  `name`, `sex`, COUNT(*) as "sum" FROM `tabName`  GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC LIMIT 100 ;
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',FALSE, array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC'),100  );
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',FALSE, '`name`, `sxe`', '`name` , `sxe` DESC ,`case` ASC','100' );


 //    SQL:  SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC LIMIT 0, 10 ;
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC'),array(0,10 )  );
 // FK-PDO:  FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`', '`name` , `sxe` DESC ,`case` ASC','0 ,10'  );

 //    SQL:  SELECT   tabName1.`name`, tabName2.`sex`  FROM `tabName1`,`tabName2` WHERE `id` = 1234   LIMIT 0 ,10 ;
 // FK-PDO:  FK_PDO(  'tabName1`,`tabName2',' tabName1.`name`, tabName2.`sex` ','`id` = 1234', FALSE,FALSE,'0 ,10'  );

 //也可用非FKPDO方法直接SQL语句执行
     $dbConn = db_connect(); //连接数据库
     $query = $dbConn->query($SQL); //查询语句
     $result = $query->fetchAll(PDO::FETCH_ASSOC);//FETCH_OBJ 将查询出来的数据对象转为数组 if($result){查询成功}
     $query_count = $dbConn->exec($sql_count); //执行语句 像删表建表用它比较好  if($query_count !== false){操作成功}
     $lastInsertId = $dbConn->lastInsertId(); //得到最后一个ID 做插入新数据主录时可返回插入的主键值if($lastInsertId){插入成功}
     $rowCount = $query->rowCount(); //当为查询或更新时本次操作响应的总行数 if($rowCount){更新成功}

*/
function FK_PDO($TabName = '', $Field = '', $Where = '', $GroupBy = '', $OrderBy = '',$Limit = ''){
    //API数据输出格式
    $_API_RESULT = array(
        'meta' => array(
            'error' => 0, // 0无错误，表示成功
            'msg'   => '', //请求操作的相关消息
            'query'  => '' //返回 最后一个ID 或总行数
        ),
        'data' => '' //返回数据
    );

    function getField($Field_data,$type){
        $_i='';$_data='';$_value='';
        if($type == 'WHERE') $_i = 'WHERE';
        if($type == 'GROUP') $_i = 'GROUP BY';
        if($type == 'ORDER') $_i = 'ORDER BY';
        if($type == 'LIMIT') $_i = 'LIMIT';

        if(is_array($Field_data)){


            foreach ($Field_data as $key => $value) {
                if($type == 'INSERT'){
                    @$_data[0] .= $_i . " `$key`";
                    @$_data[1] .= $_i . " '". addslashes($value) ."'";
                }elseif($type == 'UPDATE'){
                    $_data .= $_i." `$key`='". addslashes($value) ."'";
                }elseif($type == 'SELECT'){
                    $_data .= $_i . " `".addslashes($value)."`";
                }elseif($type == 'GROUP'){
                    $_data .= $_i . " `".addslashes($value)."`";
                }elseif($type == 'ORDER'){
                    $_data .= $_i." `$key` ". addslashes($value);
                }elseif($type == 'WHERE'){
                    $_value = json_decode($value,TRUE); //当该参数为 TRUE 时，将返回 array 而非 object 。
                    if($_value && is_array($_value)){
                        $_match = addslashes($_value[0]);
                        $_value = addslashes($_value[1]);
                    }elseif(is_array($value)){
                        $_match = addslashes($value[0]);
                        $_value = addslashes($value[1]);
                    }else{
                        $_match = "=";
                        $_value = $value;
                    }
                    $_data .= $_i." `$key` {$_match} '{$_value}'";
                    $_i = ' AND ';
                }elseif($type == 'LIMIT'){
                     $_data .= $_i." ".addslashes($value);
                }
                if($type != 'WHERE') $_i = ',';
            }//foreach

        }else{
             $_data .= $_i." ".$Field_data;
        }//is_array
        return $_data;
    };

    

      


    if ($TabName == ''){
        $_API_RESULT['meta']['error'] = 1;
        $_API_RESULT['meta']['msg'] = 'No `TabName';
        return $_API_RESULT;
    }
    


    //定义变量
    $_field = $_where = $_groupby = $_orderby = $_limit = $sql = $type = '';


    //INSERT
    if( is_array($Field) && !isset($Field[0]) && !$Where && !$GroupBy && !$OrderBy && !$Limit ){
        //插入数据：有字段 没有条件 没有分组 没有排序 没有页数
        $_API_RESULT['meta']['type'] = "INSERT";
        $_field = getField($Field,'INSERT');
        $sql = "INSERT INTO `{$TabName}` (".$_field[0].") VALUES (".$_field[1].") ;";
        $type = "INSERT";


    //UPDATE
    }else if(is_array($Field) && !isset($Field[0]) && $Where && !$GroupBy && !$OrderBy && !$Limit ){
        //更新数据：有字段 有条件
        $_API_RESULT['meta']['type'] = "UPDATE";

        $_field = getField($Field,'UPDATE');

        $_where = getField($Where,'WHERE');
        
        $sql  = "UPDATE `{$TabName}` SET {$_field} {$_where} ;";
        $type = "UPDATE";


    //SELECT
    }else{
        //查询数据：其它条件
        $_API_RESULT['meta']['type'] = "SELECT";


        if($Field){
            $_field = getField($Field,'SELECT');
        }else{
            $_field="*";
        } 

        if($Where) $_where = getField($Where,'WHERE');
        if($GroupBy) $_groupby = getField($GroupBy,'GROUP');
        if($OrderBy) $_orderby = getField($OrderBy,'ORDER');
        if($Limit) $_limit = getField($Limit,'LIMIT');

        $sql = "SELECT $_field FROM `{$TabName}` $_where $_groupby $_orderby $_limit;";
        $type = "SELECT";

        

    };

    


    try {
        $dbConn = db_connect(); //连接数据库

        $_API_RESULT['meta']['msg'] = $sql;
        $query = $dbConn->query($sql);
        //$result = $query->fetchAll(PDO::FETCH_ASSOC);//FETCH_OBJ
        //$query_count = $dbConn->exec($sql_count); //执行
        //$lastInsertId = $dbConn->lastInsertId(); //得到最后一个ID 
        //$rowCount = $query->rowCount(); //总行数
        

        
    

        if( $type == "SELECT"){
            $result = $query->fetchAll(PDO::FETCH_ASSOC);//FETCH_OBJ
            $_API_RESULT['meta']['query'] = $query->rowCount(); //总行数
        }elseif( $type == "INSERT"){
            $result = $dbConn->lastInsertId(); //得到最后一个ID 
            $_API_RESULT['meta']['query'] = $result;
        }elseif( $type == "UPDATE"){
            $result = $query->rowCount(); //影响总行数
            $_API_RESULT['meta']['query'] = $result;
        }
        $_API_RESULT['data'] = $result;

        $query = null; //释放DB资源
    }catch (PDOException $e) {
        $_API_RESULT['meta']['error'] =  $e->getMessage();
    }


    

    return $_API_RESULT;
};


 
?>
