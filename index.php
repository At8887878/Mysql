<?php
    require_once('config/db.php'); // 引入配置文件


    $student = new DB('student'); // 定义表名为'student'的数据表

    /**
     * 查询所有数据
     * @param string $columns 要查询的列，可以是单个列或多个列的逗号分隔
     * @param array $where 查询条件，使用关联数组表示字段名和对应的值
     * @return array 返回符合条件的学生数据的数组
     */

    $select = $student->select('*',array(
        'Ssex' => '男'
    ));
    $select_1 = $student->select('Sname, Sage','Ssex = "男"');
    echo "<pre>";
    var_dump($select, $select_1);
    

    /**
     * 查询单条数据
     * @param string $columns 要查询的列，可以是单个列或多个列的逗号分隔
     * @param array $where 查询条件，使用关联数组表示字段名和对应的值
     * @return array 返回符合条件的学生数据的数组
     */

    
    $an = $student->an('*',array(
        'Ssex' => '男'
    ));
    $an_1 = $student->an('Sname, Sage','Ssex = "男"');
    echo "<pre>";
    var_dump($an, $an_1);
    

    /**
     * 插入单条数据
     * @param array $value 需要插入表中的数据
     * @return array 插入成功的条数
     */

    
    $insert = $student->insert(
        array(
            'SNO' => '1009',
            'Sname'=> '张三',
            'Sage' => '1996-01-20 00:00:00',
            'Ssex' => '男'
        )
    );
    var_dump($insert);
    

    /**
     * 插入数据返回主键id
     * @param array $value 需要插入表中的数据
     * @return array 返回自增id
     */

    
    $insertGetId = $student->insert(
        array(
            'SNO' => '1010',
            'Sname'=> '李四',
            'Sage' => '1996-02-20 00:00:00',
            'Ssex' => '女'
        )
    );
    var_dump($insertGetId);
    

    /**
     * 插入多条数据
     * @param array $value 需要插入表中的数据
     * @return array 插入成功的条数
     */

    
    $insertAll = $student->insertAll(
        array(
            array(
                'SNO' => '1011',
                'Sname'=> '王五',
                'Sage' => '1996-03-20 00:00:00',
                'Ssex' => '男'
            ),
            array(
                'SNO' => '1012',
                'Sname'=> '武六',
                'Sage' => '1996-04-20 00:00:00',
                'Ssex' => '女'
            )
        )
    );
    var_dump($insertAll);
    

    /**
     * 更新数据
     * @param string $columns 要更新的列，可以是单个列或多个列的逗号分隔
     * @param array $where 查询条件，使用关联数组表示字段名和对应的值
     * @return array 更新成功的条数
     */
    
    
    $update = $student->update(
        array(
            'Ssex' => '女',
            'Sname' => '王老五'
        ),array(
            'SNO' => '1011'
        )
    );
    $update_1 = $student->update(
        array(
            'Ssex' => '男',
            'Sname' => '武大郎'
        ),'SNO = "1012"');
    var_dump($update,$update_1);
    

    /**
     * 删除数据
     * @param array $value 需要插入表中的数据
     * @return array 删除成功的条数
     */

    
    $delete = $student->delete(
        'SNO = "1011" AND Ssex = "女"'
    );
    $delete_1 = $student->delete(
        array(
            'SNO' => '1012',
            'Ssex' => '男'
        )
    );
    var_dump($delete, $delete_1);
    

    /**
     * 原生查询
     * @param string $value 需要查询表中的数据
     */

    
    $data = $student->getBySql('select * from student');
    $data_1 = $student->getBySql('select * from student where SNO = "1001"');
    echo "<pre>";
    var_dump($data, $data_1);
    


    

