
# fkpdo 废客快捷操作修改数据库
## 废客快速操作数据库PHP操作MySql实例代码 - db_config 配置文件
返回对象  = FK_PDO(表名,字段数组或字符串,条件数组或字符串,分组数组或字符串,排序数组或字符串,行数数组或字符串);
实例:

   INSERT INTO `tabName` ( `name`, `sxe`) VALUES ( 'a', '2') ;
>   FK_PDO('tabName',array('name' =>'a' , 'sxe'=>'2')    );

   UPDATE `tabName` SET  `name`='b', `sxe`='1' WHERE `id` = '123' AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%' ;
>   FK_PDO('tabName',array('name' =>'b' , 'sxe'=>'1'),array('id' =>'123' , 'age'=>'["!=","2"]','name'=>array("=","a"), 'book' =>'["like","泉%"]'  ));
>
>   FK_PDO('tabName',array('name' =>'a' , 'sxe'=>'2'),"`id` = 123 AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%'" ) ;

   SELECT  *  FROM  `tabName`  ;
>   FK_PDO('tabName','*' );
>
>   FK_PDO('tabName',"" );
>
>   FK_PDO('tabName',FALSE );
>
>   FK_PDO('tabName');


   SELECT  `id`, `name`  FROM  `tabName` ;
>   FK_PDO('tabName',array('id','name') );
>
>   FK_PDO('tabName','`id`, name`' );


   SELECT  `id`, name`  FROM  `tabName` WHERE `id` = '123' AND  `age` != '2' AND  `name` = 'a' AND  `book` like '泉%'    ;
>   FK_PDO('tabName','`id`, name`',array('id' =>'123' , 'age'=>'["!=","2"]','name'=>array("=","a"), 'book' =>'["like","泉%"]'  )  );
>
>   FK_PDO('tabName','`id`, name`','`id` = 123 AND  `age` != 2 AND  `name` = "a" AND  `book` like "泉%"'  );


   SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe`   ;
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe')  );
>
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`'  );


   SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC  ;
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC')  );
>
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`', '`name` , `sxe` DESC, `case` ASC'  );


   SELECT  `name`, `sex`, COUNT(*) as "sum" FROM `tabName`  GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC LIMIT 100 ;
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',FALSE, array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC'),100  );
>
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',FALSE, '`name`, `sxe`', '`name` , `sxe` DESC ,`case` ASC','100' );


   SELECT  `name`, `sex`, COUNT(*) as "sum"  FROM  `tabName` WHERE `id` = 1234 GROUP BY `name`, `sxe` ORDER BY `name` , `sxe` DESC, `case` ASC LIMIT 0, 10 ;
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", array('name','sxe'), array('name' =>'' , 'sxe'=>'DESC' ,'case' =>'ASC'),array(0,10 )  );
>   FK_PDO( 'tabName','`name`, `sex`, COUNT(*) as "sum"',"`id` = 1234", '`name`, `sxe`', '`name` , `sxe` DESC ,`case` ASC','0 ,10'  );

   SELECT   tabName1.`name`, tabName2.`sex`  FROM `tabName1`,`tabName2` WHERE `id` = 1234   LIMIT 0 ,10 ;
>   FK_PDO(  'tabName1`,`tabName2',' tabName1.`name`, tabName2.`sex` ','`id` = 1234', FALSE,FALSE,'0 ,10'  );

```php
//也可用非FKPDO方法直接SQL语句执行
 $dbConn = db_connect(); //连接数据库
 $query = $dbConn->query($SQL); //查询语句
 $result = $query->fetchAll(PDO::FETCH_ASSOC);//FETCH_OBJ 将查询出来的数据对象转为数组 if($result){查询成功}
 $query_count = $dbConn->exec($sql_count); //执行语句 像删表建表用它比较好  if($query_count !== false){操作成功}
 $lastInsertId = $dbConn->lastInsertId(); //得到最后一个ID 做插入新数据主录时可返回插入的主键值if($lastInsertId){插入成功}
 $rowCount = $query->rowCount(); //当为查询或更新时本次操作响应的总行数 if($rowCount){更新成功}
```
